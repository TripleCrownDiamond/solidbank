<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Config;
use App\Models\Rib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AccountActivationController extends Controller
{
    /**
     * Activer le compte utilisateur via le lien d'activation
     */
    public function activate($locale, $id, $hash)
    {
        $request = request();
        
        // Définir la locale si elle n'est pas déjà définie
        if (!app()->getLocale() || app()->getLocale() === config('app.locale')) {
            app()->setLocale($locale);
        }
        
        // Utiliser la locale de l'application si elle est déjà définie
        $locale = app()->getLocale();
        
        Log::info('AccountActivationController::activate called', [
            'locale' => $locale,
            'id' => $id,
            'hash' => $hash,
            'url' => $request->fullUrl(),
            'signature_valid' => URL::hasValidSignature($request)
        ]);

        // Vérifier que l'URL est valide et non expirée
        if (!URL::hasValidSignature($request)) {
            Log::warning('Invalid or expired activation link signature', [
                'locale' => $locale,
                'id' => $id,
                'hash' => $hash,
                'url' => $request->fullUrl()
            ]);
            return to_route('locale.login', ['locale' => $locale])
                ->with('error', __('auth.activation_link_invalid'));
        }

        // Trouver l'utilisateur
        $user = User::find($id);
        if (!$user) {
            Log::error('User not found during activation', [
                'locale' => $locale,
                'id' => $id,
                'hash' => $hash
            ]);
            return to_route('locale.login', ['locale' => $locale])
                ->with('error', __('auth.user_not_found'));
        }

        Log::info('User found for activation', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'email_verified_at' => $user->email_verified_at
        ]);

        // Vérifier le hash
        $expectedHash = sha1($user->getEmailForVerification());
        if (!hash_equals($hash, $expectedHash)) {
            Log::error('Hash mismatch during activation', [
                'provided_hash' => $hash,
                'expected_hash' => $expectedHash,
                'user_email' => $user->email
            ]);
            return to_route('locale.login', ['locale' => $locale])
                ->with('error', __('auth.activation_link_invalid'));
        }

        Log::info('Hash verification successful');

        // Vérifier si le compte est déjà activé
        if ($user->hasVerifiedEmail()) {
            $account = $user->accounts()->first();
            if ($account && $account->status === 'ACTIVE') {
                return to_route('locale.login', ['locale' => $locale])
                    ->with('info', __('auth.account_already_activated'))
                    ->with('email', $user->email);
            }
        }

        // Marquer l'email comme vérifié
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            Log::info('Email marked as verified for user', ['user_id' => $user->id]);
        } else {
            Log::info('Email already verified for user', ['user_id' => $user->id]);
        }

        // Activer le compte
        $account = $user->accounts()->first();
        if ($account) {
            $account->update(['status' => 'ACTIVE']);
            Log::info('Account activated', ['account_id' => $account->id, 'user_id' => $user->id]);
            
            // Générer automatiquement le RIB si il n'existe pas
            if (!$account->rib) {
                try {
                    $this->generateRib($account);
                    Log::info('RIB generated automatically for account: ' . $account->account_number);
                } catch (\Exception $e) {
                    Log::error('Failed to generate RIB for account ' . $account->account_number . ': ' . $e->getMessage());
                }
            } else {
                Log::info('Account already has RIB', ['account_id' => $account->id]);
            }
        } else {
            Log::error('No account found for user during activation', ['user_id' => $user->id]);
        }

        // Stocker l'email en session pour l'auto-remplissage
        session(['email' => $user->email]);

        Log::info('Activation process completed successfully, redirecting to login', [
            'user_id' => $user->id,
            'locale' => $locale
        ]);

        // Rediriger vers la page de connexion avec un message de succès
        return to_route('locale.login', ['locale' => $locale])
            ->with('success', __('auth.account_activated_successfully'));
    }
    
    /**
     * Générer un RIB pour le compte selon les règles de configuration
     */
    private function generateRib($account)
    {
        $config = Config::first();

        if (!$config) {
            throw new \Exception(__('messages.bank_config_not_found'));
        }

        // Générer l'IBAN selon les règles de configuration
        $iban = $config->iban_prefix
            . $config->iban_bank_code
            . $config->iban_branch_code
            . str_pad($account->account_number, $config->iban_account_length, '0', STR_PAD_LEFT);

        // Générer le SWIFT (BIC)
        $swift = $config->bank_swift;

        // Créer le RIB
        Rib::create([
            'account_id' => $account->id,
            'iban' => $iban,
            'swift' => $swift,
            'bank_name' => $config->bank_name,
        ]);
    }
}