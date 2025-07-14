<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountStatusNotification;

class DeleteUserByEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete-by-email {email : The email address of the user to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a user by their email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $normalizedEmail = strtolower(trim($email));

        // Find user by email (case-insensitive)
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        if (!$user) {
            $this->error("Aucun utilisateur trouvé avec l'email: {$email}");
            return 1;
        }

        // Check if user is admin
        if ($user->is_admin) {
            $this->error("Impossible de supprimer un administrateur.");
            return 1;
        }

        $this->info("Utilisateur trouvé: {$user->name} ({$user->email})");
        
        if (!$this->confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
            $this->info('Suppression annulée.');
            return 0;
        }

        try {
            DB::transaction(function () use ($user) {
                // Send email notification before deletion
                foreach ($user->accounts as $account) {
                    try {
                        Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'deleted'));
                        $this->info("Email de notification envoyé pour le compte: {$account->account_number}");
                    } catch (\Exception $e) {
                        // Log email error but don't fail the operation
                        Log::error('Failed to send account deletion email: ' . $e->getMessage());
                        $this->warn("Échec de l'envoi de l'email de notification: {$e->getMessage()}");
                    }
                }

                // Delete user (related models will be deleted automatically via model boot method)
                $user->delete();
            });

            $this->info("Utilisateur {$user->email} supprimé avec succès.");
            return 0;
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            $this->error("Échec de la suppression de l'utilisateur: {$e->getMessage()}");
            return 1;
        }
    }
}