<?php

namespace App\Services;

use App\Mail\NewPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetService
{
    /**
     * Send a new password to the user's email
     *
     * @param string $email
     * @param string|null $locale
     * @return bool
     */
    public function sendNewPassword($email, $locale = null)
    {
        try {
            // Normalisation de l'email
            $normalizedEmail = strtolower(trim($email));
            Log::info('=== DÉBUT RÉINITIALISATION MOT DE PASSE ===');
            Log::info("Email soumis: '" . $email . "'");
            Log::info("Email normalisé: '" . $normalizedEmail . "'");

            // Vérification directe de l'utilisateur
            $user = User::where('email', $normalizedEmail)->first();

            // Si non trouvé, essayer avec la recherche insensible à la casse
            if (!$user) {
                Log::info('Recherche insensible à la casse...');
                $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();
            }

            // Debug: Afficher tous les utilisateurs pour vérification
            $allUsers = User::all(['id', 'email']);
            Log::info('Tous les utilisateurs dans la base de données:');
            foreach ($allUsers as $u) {
                Log::info(sprintf("- ID: %d, Email: '%s'", $u->id, $u->email));
                Log::info(sprintf("  Email en minuscules: '%s'", strtolower($u->email)));
                Log::info(sprintf("  Comparaison avec '%s': %s",
                    $normalizedEmail,
                    (strtolower($u->email) === $normalizedEmail) ? 'MATCH' : 'PAS DE MATCH'));
            }

            if (!$user) {
                Log::warning("Aucun utilisateur trouvé pour l'email: '" . $normalizedEmail . "'");
                Log::info('=== FIN RÉINITIALISATION MOT DE PASSE (ÉCHEC) ===');
                return false;
            }

            Log::info(sprintf("Utilisateur trouvé - ID: %d, Email: '%s'", $user->id, $user->email));

            // Generate a new random password
            $newPassword = Str::password(12);  // 12 characters with letters, numbers, and symbols

            // Update user's password
            $user->password = Hash::make($newPassword);
            $user->save();

            // Use provided locale or default to current locale
            $emailLocale = $locale ?: app()->getLocale();

            // Send email with new password
            Mail::to($user->email)->send(new NewPasswordMail($newPassword, $emailLocale));

            Log::info("New password sent to user: {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error('Error sending password reset email: ' . $e->getMessage(), [
                'email' => $email,
                'exception' => $e
            ]);
            return false;
        }
    }
}
