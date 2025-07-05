<?php

namespace App\Livewire;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminConfigManagement extends Component
{
    use WithFileUploads;

    public $config;
    public $logo;
    public $icon;
    public $favicon;
    
    // Configuration fields
    public $iban_country_code;
    public $iban_bank_code;
    public $iban_branch_code;
    public $iban_account_length;
    public $iban_prefix;
    public $bank_name;
    public $bank_swift;
    public $bank_country;
    public $bank_address;
    public $bank_phone;
    public $bank_email;
    public $bank_website;
    public $logo_url;
    public $icon_url;
    public $favicon_url;
    public $notification_email;
    public $two_factor_auth;
    public $account_prefix;
    public $account_length;
    public $transaction_validation_method;
    // Propriétés des couleurs de marque supprimées (colonnes supprimées de la DB)
    // Les couleurs sont maintenant gérées via CSS avec des valeurs par défaut

    public function mount()
    {
        // Vérifier que l'utilisateur connecté est un administrateur
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer ou créer la configuration
        $this->config = Config::first() ?? new Config();
        
        // Initialiser les propriétés avec les valeurs existantes
        $this->iban_country_code = $this->config->iban_country_code;
        $this->iban_bank_code = $this->config->iban_bank_code;
        $this->iban_branch_code = $this->config->iban_branch_code;
        $this->iban_account_length = $this->config->iban_account_length;
        $this->iban_prefix = $this->config->iban_prefix;
        $this->bank_name = $this->config->bank_name;
        $this->bank_swift = $this->config->bank_swift;
        $this->bank_country = $this->config->bank_country;
        $this->bank_address = $this->config->bank_address;
        $this->bank_phone = $this->config->bank_phone;
        $this->bank_email = $this->config->bank_email;
        $this->bank_website = $this->config->bank_website;
        $this->logo_url = $this->config->logo_url;
        $this->icon_url = $this->config->icon_url;
        $this->favicon_url = $this->config->favicon_url;
        $this->notification_email = $this->config->notification_email;
        $this->two_factor_auth = $this->config->two_factor_auth;
        $this->account_prefix = $this->config->account_prefix;
        $this->account_length = $this->config->account_length;
        $this->transaction_validation_method = $this->config->transaction_validation_method;
        // Les couleurs de marque ont été supprimées de la base de données
        // Utilisation des valeurs par défaut CSS
    }

    public function updateConfig()
    {
        Log::info('AdminConfigManagement: Début de la mise à jour de la configuration', [
            'user_id' => Auth::id(),
            'config_exists' => $this->config->exists
        ]);

        try {
            $this->validate([
                'iban_country_code' => 'nullable|string|max:2',
                'iban_bank_code' => 'nullable|string|max:10',
                'iban_branch_code' => 'nullable|string|max:10',
                'iban_account_length' => 'nullable|integer|min:1|max:50',
                'iban_prefix' => 'nullable|string|max:10',
                'bank_name' => 'nullable|string|max:255',
                'bank_swift' => 'nullable|string|max:11',
                'bank_country' => 'nullable|string|max:255',
                'bank_address' => 'nullable|string|max:500',
                'bank_phone' => 'nullable|string|max:20',
                'bank_email' => 'nullable|email|max:255',
                'bank_website' => 'nullable|url|max:255',
                'notification_email' => 'nullable|email|max:255',
                'two_factor_auth' => 'nullable|boolean',
                'account_prefix' => 'nullable|string|max:10',
                'account_length' => 'nullable|integer|min:1|max:50',
                'transaction_validation_method' => 'nullable|string|in:manual,automatic',
                // Validation des couleurs de marque supprimée (colonnes supprimées de la DB)
                'logo' => 'nullable|image|max:2048',
                'icon' => 'nullable|image|max:1024',
                'favicon' => 'nullable|image|max:512',
            ]);
            
            Log::info('AdminConfigManagement: Validation réussie');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('AdminConfigManagement: Erreur de validation', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            throw $e;
        }

        try {
            Log::info('AdminConfigManagement: Début de la gestion des fichiers');
            
            // Gérer l'upload du logo
            if ($this->logo) {
                Log::info('AdminConfigManagement: Upload du logo en cours');
                if ($this->config->logo_url) {
                    Storage::disk('public')->delete($this->config->logo_url);
                    Log::info('AdminConfigManagement: Ancien logo supprimé', ['old_logo' => $this->config->logo_url]);
                }
                $this->logo_url = $this->logo->store('config/logos', 'public');
                Log::info('AdminConfigManagement: Nouveau logo uploadé', ['logo_url' => $this->logo_url]);
            }

            // Gérer l'upload de l'icône
            if ($this->icon) {
                Log::info('AdminConfigManagement: Upload de l\'icône en cours');
                if ($this->config->icon_url) {
                    Storage::disk('public')->delete($this->config->icon_url);
                    Log::info('AdminConfigManagement: Ancienne icône supprimée', ['old_icon' => $this->config->icon_url]);
                }
                $this->icon_url = $this->icon->store('config/icons', 'public');
                Log::info('AdminConfigManagement: Nouvelle icône uploadée', ['icon_url' => $this->icon_url]);
            }

            // Gérer l'upload du favicon
            if ($this->favicon) {
                Log::info('AdminConfigManagement: Upload du favicon en cours');
                if ($this->config->favicon_url) {
                    Storage::disk('public')->delete($this->config->favicon_url);
                    Log::info('AdminConfigManagement: Ancien favicon supprimé', ['old_favicon' => $this->config->favicon_url]);
                }
                $this->favicon_url = $this->favicon->store('config/favicons', 'public');
                Log::info('AdminConfigManagement: Nouveau favicon uploadé', ['favicon_url' => $this->favicon_url]);
            }

            // Mettre à jour ou créer la configuration
            Log::info('AdminConfigManagement: Préparation des données de configuration');
            
            $configData = [
                'user_id' => Auth::id(),
                'iban_country_code' => $this->iban_country_code,
                'iban_bank_code' => $this->iban_bank_code,
                'iban_branch_code' => $this->iban_branch_code,
                'iban_account_length' => $this->iban_account_length,
                'iban_prefix' => $this->iban_prefix,
                'bank_name' => $this->bank_name,
                'bank_swift' => $this->bank_swift,
                'bank_country' => $this->bank_country,
                'bank_address' => $this->bank_address,
                'bank_phone' => $this->bank_phone,
                'bank_email' => $this->bank_email,
                'bank_website' => $this->bank_website,
                'logo_url' => $this->logo_url,
                'icon_url' => $this->icon_url,
                'favicon_url' => $this->favicon_url,
                'notification_email' => $this->notification_email,
                'two_factor_auth' => $this->two_factor_auth,
                'account_prefix' => $this->account_prefix,
                'account_length' => $this->account_length,
                'transaction_validation_method' => $this->transaction_validation_method,
                // Couleurs de marque supprimées de la base de données
            ];

            Log::info('AdminConfigManagement: Données de configuration préparées', [
                'config_exists' => $this->config->exists,
                'config_id' => $this->config->id ?? 'nouveau'
            ]);

            if ($this->config->exists) {
                Log::info('AdminConfigManagement: Mise à jour de la configuration existante');
                $this->config->update($configData);
                Log::info('AdminConfigManagement: Configuration mise à jour avec succès');
            } else {
                Log::info('AdminConfigManagement: Création d\'une nouvelle configuration');
                $this->config = Config::create($configData);
                Log::info('AdminConfigManagement: Nouvelle configuration créée avec succès', ['config_id' => $this->config->id]);
            }

            // Réinitialiser les fichiers uploadés
            $this->logo = null;
            $this->icon = null;
            $this->favicon = null;

            Log::info('AdminConfigManagement: Configuration sauvegardée avec succès');
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => __('messages.config_updated_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('AdminConfigManagement: Erreur lors de la mise à jour de la configuration', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('messages.config_update_failed') . ' : ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin-config-management');
    }
}