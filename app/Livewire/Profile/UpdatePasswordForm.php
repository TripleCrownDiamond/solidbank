<?php

namespace App\Livewire\Profile;

use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public $successMessage = '';
    public $showSuccess = false;

    protected $rules = [
        'state.current_password' => 'required|string|current_password:web',
        'state.password' => 'required|string|min:8|confirmed',
        'state.password_confirmation' => 'required|string',
    ];

    protected $messages = [
        'state.current_password.required' => 'Le mot de passe actuel est obligatoire.',
        'state.current_password.current_password' => 'Le mot de passe actuel fourni ne correspond pas à votre mot de passe actuel.',
        'state.password.required' => 'Le nouveau mot de passe est obligatoire.',
        'state.password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
        'state.password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        'state.password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
    ];

    public function updatePassword()
    {
        \Log::info('UpdatePasswordForm: Début', [
            'current_password_length' => strlen($this->state['current_password'] ?? ''),
            'password_length' => strlen($this->state['password'] ?? ''),
            'password_confirmation_length' => strlen($this->state['password_confirmation'] ?? ''),
        ]);

        try {
            $this->validate();

            $updateUserPassword = new UpdateUserPassword();
            $updateUserPassword->update(Auth::user(), [
                'current_password' => $this->state['current_password'],
                'password' => $this->state['password'],
                'password_confirmation' => $this->state['password_confirmation'],
            ]);

            // Réinitialiser seulement en cas de succès
            $this->state = [
                'current_password' => '',
                'password' => '',
                'password_confirmation' => '',
            ];

            // Afficher le message de succès
            $this->successMessage = 'Mot de passe mis à jour avec succès !';
            $this->showSuccess = true;
            $this->dispatch('saved');
            
            \Log::info('UpdatePasswordForm: Succès');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('UpdatePasswordForm: Erreur de validation', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            
            // Masquer le message de succès en cas d'erreur
            $this->showSuccess = false;
            
            // Gérer les erreurs de validation de Fortify
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError('state.' . $field, $message);
                }
            }
        }
    }

    public function hideSuccessMessage()
    {
        $this->showSuccess = false;
        $this->successMessage = '';
    }

    public function render()
    {
        return view('profile.update-password-form');
    }
}