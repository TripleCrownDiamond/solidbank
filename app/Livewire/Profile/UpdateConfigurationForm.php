<?php

namespace App\Livewire\Profile;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UpdateConfigurationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $logo;
    public $icon;
    public $favicon;
    public $config;

    public function mount()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }

        $this->config = Config::first() ?? new Config();
        
        $this->state = [
            'iban_country_code' => $this->config->iban_country_code,
            'iban_bank_code' => $this->config->iban_bank_code,
            'iban_branch_code' => $this->config->iban_branch_code,
            'iban_account_length' => $this->config->iban_account_length,
            'iban_prefix' => $this->config->iban_prefix,
            'bank_name' => $this->config->bank_name,
            'bank_swift' => $this->config->bank_swift,
            'bank_country' => $this->config->bank_country,
            'bank_address' => $this->config->bank_address,
            'bank_phone' => $this->config->bank_phone,
            'bank_email' => $this->config->bank_email,
            'bank_website' => $this->config->bank_website,
            'logo_url' => $this->config->logo_url,
            'icon_url' => $this->config->icon_url,
            'favicon_url' => $this->config->favicon_url,
            'notification_email' => $this->config->notification_email,
            'two_factor_auth' => $this->config->two_factor_auth ?? false,
            'account_prefix' => $this->config->account_prefix,
            'account_length' => $this->config->account_length,
            'transaction_validation_method' => $this->config->transaction_validation_method ?? 'email',
            'brand_color' => $this->config->brand_color,
            'brand_primary_hover' => $this->config->brand_primary_hover,
            'brand_primary_light' => $this->config->brand_primary_light,
            'brand_primary_dark' => $this->config->brand_primary_dark,
            'brand_secondary' => $this->config->brand_secondary,
            'brand_accent' => $this->config->brand_accent,
            'brand_success' => $this->config->brand_success,
            'brand_warning' => $this->config->brand_warning,
            'brand_error' => $this->config->brand_error,
        ];
    }

    public function updateConfiguration()
    {
        $this->resetErrorBag();

        $this->validate([
            'state.iban_country_code' => ['nullable', 'string', 'max:2'],
            'state.iban_bank_code' => ['nullable', 'string', 'max:10'],
            'state.iban_branch_code' => ['nullable', 'string', 'max:10'],
            'state.iban_account_length' => ['nullable', 'integer', 'min:1', 'max:50'],
            'state.iban_prefix' => ['nullable', 'string', 'max:10'],
            'state.bank_name' => ['required', 'string', 'max:255'],
            'state.bank_swift' => ['nullable', 'string', 'max:11'],
            'state.bank_country' => ['nullable', 'string', 'max:255'],
            'state.bank_address' => ['nullable', 'string', 'max:500'],
            'state.bank_phone' => ['nullable', 'string', 'max:20'],
            'state.bank_email' => ['nullable', 'email', 'max:255'],
            'state.bank_website' => ['nullable', 'url', 'max:255'],
            'state.notification_email' => ['nullable', 'email', 'max:255'],
            'state.two_factor_auth' => ['boolean'],
            'state.account_prefix' => ['nullable', 'string', 'max:10'],
            'state.account_length' => ['nullable', 'integer', 'min:1', 'max:50'],
            'state.transaction_validation_method' => ['required', 'in:email,sms,both'],
            'state.brand_color' => ['nullable', 'string', 'max:7'],
            'state.brand_primary_hover' => ['nullable', 'string', 'max:7'],
            'state.brand_primary_light' => ['nullable', 'string', 'max:7'],
            'state.brand_primary_dark' => ['nullable', 'string', 'max:7'],
            'state.brand_secondary' => ['nullable', 'string', 'max:7'],
            'state.brand_accent' => ['nullable', 'string', 'max:7'],
            'state.brand_success' => ['nullable', 'string', 'max:7'],
            'state.brand_warning' => ['nullable', 'string', 'max:7'],
            'state.brand_error' => ['nullable', 'string', 'max:7'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'icon' => ['nullable', 'image', 'max:1024'],
            'favicon' => ['nullable', 'image', 'max:512'],
        ]);

        // Upload logo if provided
        if (isset($this->logo)) {
            if ($this->config->logo_url) {
                Storage::disk('public')->delete($this->config->logo_url);
            }
            $path = $this->logo->store('config/logos', 'public');
            $this->state['logo_url'] = $path;
        }

        // Upload icon if provided
        if (isset($this->icon)) {
            if ($this->config->icon_url) {
                Storage::disk('public')->delete($this->config->icon_url);
            }
            $path = $this->icon->store('config/icons', 'public');
            $this->state['icon_url'] = $path;
        }

        // Upload favicon if provided
        if (isset($this->favicon)) {
            if ($this->config->favicon_url) {
                Storage::disk('public')->delete($this->config->favicon_url);
            }
            $path = $this->favicon->store('config/favicons', 'public');
            $this->state['favicon_url'] = $path;
        }

        // Update or create config
        if ($this->config->exists) {
            $this->config->fill($this->state);
            $this->config->save();
        } else {
            $this->state['user_id'] = Auth::id();
            Config::create($this->state);
            $this->config = Config::first();
        }

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => __('Configuration mise à jour avec succès')
        ]);

        // Recharger la page après la mise à jour
        $this->js('setTimeout(() => window.location.reload(), 1500);');

        $this->dispatch('saved');
    }

    public function deleteLogo()
    {
        if ($this->config->logo_url) {
            Storage::disk('public')->delete($this->config->logo_url);
            $this->config->fill(['logo_url' => null]);
            $this->config->save();
        }
        $this->state['logo_url'] = null;
    }

    public function deleteIcon()
    {
        if ($this->config->icon_url) {
            Storage::disk('public')->delete($this->config->icon_url);
            $this->config->fill(['icon_url' => null]);
            $this->config->save();
        }
        $this->state['icon_url'] = null;
    }

    public function deleteFavicon()
    {
        if ($this->config->favicon_url) {
            Storage::disk('public')->delete($this->config->favicon_url);
            $this->config->fill(['favicon_url' => null]);
            $this->config->save();
        }
        $this->state['favicon_url'] = null;
    }

    public function render()
    {
        return view('livewire.profile.update-configuration-form');
    }
}