<?php

namespace Database\Seeders;

use App\Models\AccountBlock;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user for demonstration
        $user = User::first();

        if ($user) {
            // Read bank configuration
            $bankConfig = json_decode(file_get_contents(base_path('bank-config.json')), true);
            $bankName = $bankConfig['bank_name'] ?? 'Nouvelle Banque';

            $accountBlock = AccountBlock::create([
                'reason' => "Vérification d'identité",
                'description' => "Votre compte a été temporairement bloqué en attente de vérification d'identité. Il s'agit d'une mesure de sécurité standard pour protéger votre compte et respecter la réglementation bancaire.",
                'instructions' => "Pour lever ce blocage et vérifier votre identité, vous devez effectuer un virement bancaire depuis un compte bancaire existant qui porte les mêmes nom et prénom que votre compte chez " . env('APP_NAME') . ". Veuillez transférer le montant exact spécifié ci-dessous vers notre compte bancaire. Une fois que nous aurons reçu et vérifié le virement, votre compte sera débloqué dans les 24 à 48 heures.",
                'amount_to_pay' => 100.0,
                'currency' => 'EUR',
                'show_rib' => true,
                'request_id_document' => false,
            ]);

            // Associer le blocage au premier compte de l'utilisateur avec un statut inactif par défaut
            $firstAccount = $user->accounts->first();
            if ($firstAccount) {
                $firstAccount->accountBlocks()->attach($accountBlock->id, ['status' => 'inactive']);
            }
        }
    }
}
