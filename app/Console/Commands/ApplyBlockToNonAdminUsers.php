<?php

namespace App\Console\Commands;

use App\Models\AccountBlock;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApplyBlockToNonAdminUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:apply-block-to-non-admin-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply account block to all non-admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Read bank configuration
        $bankConfig = json_decode(file_get_contents(base_path('bank-config.json')), true);
        $bankName = $bankConfig['bank_name'] ?? 'Nouvelle Banque';
        
        // Get all non-admin users who have at least one account
        $users = User::where('is_admin', false)
            ->whereHas('accounts')
            ->get();
            
        if ($users->isEmpty()) {
            $this->error('No non-admin users with accounts found.');
            return 1;
        }
        
        $this->info('Found ' . $users->count() . ' non-admin users with accounts.');
        
        // Start a transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            $blockCount = 0;
            
            foreach ($users as $user) {
                // Create a block for each user
                $block = AccountBlock::create([
                    'reason' => 'Vérification d\'identité',
                    'description' => 'Votre compte a été temporairement bloqué en attente de vérification d\'identité. Il s\'agit d\'une mesure de sécurité standard pour protéger votre compte et respecter la réglementation bancaire.',
                    'instructions' => "Pour lever ce blocage et vérifier votre identité, vous devez effectuer un virement bancaire depuis un compte bancaire existant qui porte les mêmes nom et prénom que votre compte chez {$bankName}. Veuillez transférer le montant exact spécifié ci-dessous vers notre compte bancaire. Une fois que nous aurons reçu et vérifié le virement, votre compte sera débloqué dans les 24 à 48 heures.",
                    'amount_to_pay' => 1.00, // 1 euro pour la vérification
                    'show_rib' => true,
                    'request_id_document' => false,
                    'status' => 'active',
                ]);
                
                // Associate the block with all of the user's accounts
                foreach ($user->accounts as $account) {
                    $account->accountBlocks()->attach($block->id);
                }
                
                $blockCount++;
            }
            
            DB::commit();
            $this->info("Successfully applied blocks to {$blockCount} users.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}