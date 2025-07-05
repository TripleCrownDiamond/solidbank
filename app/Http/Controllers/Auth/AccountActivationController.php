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
    public function activate(Request $request, $locale, $id, $hash)
    {
        // Vérifier que l'URL est valide et non expirée
        if (!URL::hasValidSignature($request)) {
            return redirect()->route('locale.login', ['locale' => $locale])
                ->with('error', __('auth.activation_link_invalid'));
        }

        // Trouver l'utilisateur
        $user = User::findOrFail($id);

        // Vérifier le hash
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('locale.login', ['locale' => $locale])
                ->with('error', __('auth.activation_link_invalid'));
        }

        // Vérifier si le compte est déjà activé
        if ($user->hasVerifiedEmail()) {
            $account = $user->accounts()->first();
            if ($account && $account->status === 'ACTIVE') {
                return redirect()->route('locale.login', ['locale' => $locale])
                    ->with('info', __('auth.account_already_activated'))
                    ->with('email', $user->email);
            }
        }

        // Marquer l'email comme vérifié
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Activer le compte
        $account = $user->accounts()->first();
        if ($account) {
            $account->update(['status' => 'ACTIVE']);
            
            // Générer automatiquement le RIB si il n'existe pas
            if (!$account->rib) {
                try {
                    $this->generateRib($account);
                    Log::info('RIB generated automatically for account: ' . $account->account_number);
                } catch (\Exception $e) {
                    Log::error('Failed to generate RIB for account ' . $account->account_number . ': ' . $e->getMessage());
                }
            }
        }

        // Stocker l'email en session pour l'auto-remplissage
        session(['email' => $user->email]);

        // Rediriger vers la page de connexion avec un message de succès
        return redirect()->route('locale.login', ['locale' => $locale])
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