<?php

namespace App\Livewire\Profile;

use App\Mail\AccountStatusNotification;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;
    public $identityDocument;
    public $addressDocument;
    public $verificationLinkSent = false;
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->state = [
            'name' => Auth::user()->name,
            'first_name' => Auth::user()->first_name,
            'last_name' => Auth::user()->last_name,
            'email' => Auth::user()->email,
            'birth_date' => Auth::user()->birth_date,
            'phone_number' => Auth::user()->phone_number,
            'city' => Auth::user()->city,
            'postal_code' => Auth::user()->postal_code,
            'address' => Auth::user()->address,
            'identity_document_url' => Auth::user()->identity_document_url,
            'address_document_url' => Auth::user()->address_document_url,
        ];
    }

    public function updateProfileInformation()
    {
        $this->resetErrorBag();
        $user = Auth::user();
        $originalEmail = $user->email;

        $this->validate([
            'state.name' => ['required', 'string', 'max:255'],
            'state.first_name' => ['nullable', 'string', 'max:255'],
            'state.last_name' => ['nullable', 'string', 'max:255'],
            'state.email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'state.birth_date' => ['nullable', 'date'],
            'state.phone_number' => ['nullable', 'string', 'max:20'],
            'state.city' => ['nullable', 'string', 'max:255'],
            'state.postal_code' => ['nullable', 'string', 'max:20'],
            'state.address' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'max:1024'],
            'identityDocument' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'addressDocument' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        // Update profile photo if provided
        // The updateProfilePhoto method is provided by the HasProfilePhoto trait
        if (isset($this->photo)) {
            /** @var \Laravel\Jetstream\HasProfilePhoto $user */
            $user->updateProfilePhoto($this->photo);
        }

        if (isset($this->identityDocument)) {
            if ($user->identity_document_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->identity_document_url);
            }
            $path = $this->identityDocument->store('documents/identity', 'public');
            $this->state['identity_document_url'] = $path;
        }

        if (isset($this->addressDocument)) {
            if ($user->address_document_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->address_document_url);
            }
            $path = $this->addressDocument->store('documents/address', 'public');
            $this->state['address_document_url'] = $path;
        }

        // Check if email has changed
        $emailChanged = $originalEmail !== $this->state['email'];

        // Fill and save user data - methods provided by Eloquent Model
        /** @var \App\Models\User $user */
        $user->fill([
            'name' => $this->state['name'],
            'first_name' => $this->state['first_name'] ?? null,
            'last_name' => $this->state['last_name'] ?? null,
            'email' => $this->state['email'],
            'birth_date' => $this->state['birth_date'] ?? null,
            'phone_number' => $this->state['phone_number'] ?? null,
            'city' => $this->state['city'] ?? null,
            'postal_code' => $this->state['postal_code'] ?? null,
            'address' => $this->state['address'] ?? null,
            'identity_document_url' => $this->state['identity_document_url'] ?? $user->identity_document_url,
            'address_document_url' => $this->state['address_document_url'] ?? $user->address_document_url,
        ]);
        // Check if email was changed
        $emailChanged = $originalEmail !== $this->state['email'];

        $user->save();

        // If email was changed, mark as unverified and send confirmation
        if ($emailChanged) {
            $user->email_verified_at = null;
            $user->save();

            // Send verification email to new email using our custom mail
            if ($user->accounts()->exists()) {
                $account = $user->accounts()->first();
                Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'email_verification'));
            }

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => __('common.email_updated_verification_sent')
            ]);

            // Recharger la page après la mise à jour
            $this->js('setTimeout(() => window.location.reload(), 1500);');
        } else {
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => __('common.profile_updated_successfully')
            ]);

            // Recharger la page après la mise à jour
            $this->js('setTimeout(() => window.location.reload(), 1500);');
        }

        $this->dispatch('profile-updated', name: $user->name);
        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    public function sendEmailVerification()
    {
        $user = Auth::user();

        // Envoyer l'email de vérification
        /** @var \App\Models\User $user */
        if ($user->accounts()->exists()) {
            $account = $user->accounts()->first();
            Mail::to($user->email)->send(new AccountStatusNotification($user, $account, 'email_verification'));
        }

        $this->verificationLinkSent = true;

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => __('common.verification_email_sent')
        ]);
    }

    public function deleteProfilePhoto()
    {
        // The deleteProfilePhoto method is provided by the HasProfilePhoto trait
        /** @var \Laravel\Jetstream\HasProfilePhoto $user */
        $user = Auth::user();
        $user->deleteProfilePhoto();
        $this->dispatch('refresh-navigation-menu');
    }

    public function deleteIdentityDocument()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->identity_document_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->identity_document_url);
            $user->fill(['identity_document_url' => null]);
            $user->save();
        }
        $this->state['identity_document_url'] = null;
    }

    public function deleteAddressDocument()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->address_document_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->address_document_url);
            $user->fill(['address_document_url' => null]);
            $user->save();
        }
        $this->state['address_document_url'] = null;
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information-form', [
            'countries' => Country::orderBy('name')->get(),
        ]);
    }
}
