<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        \Log::info('UpdateUserPassword: Début de update()', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'input_keys' => array_keys($input),
            'current_password_length' => isset($input['current_password']) ? strlen($input['current_password']) : 0,
            'password_length' => isset($input['password']) ? strlen($input['password']) : 0,
            'password_confirmation_length' => isset($input['password_confirmation']) ? strlen($input['password_confirmation']) : 0,
        ]);

        try {
            \Log::info('UpdateUserPassword: Début de la validation Fortify');
            
            $validator = Validator::make($input, [
                'current_password' => ['required', 'string', 'current_password:web'],
                'password' => $this->passwordRules(),
            ], [
                'current_password.current_password' => 'Le mot de passe actuel fourni ne correspond pas à votre mot de passe actuel.',
                'password.required' => 'Le nouveau mot de passe est obligatoire.',
                'password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            ]);
            
            \Log::info('UpdateUserPassword: Validation créée, début de validateWithBag');
            $validator->validateWithBag('updatePassword');
            \Log::info('UpdateUserPassword: Validation Fortify réussie');
            
        } catch (\Exception $e) {
            \Log::error('UpdateUserPassword: Erreur de validation Fortify', [
                'message' => $e->getMessage(),
                'errors' => method_exists($e, 'errors') ? $e->errors() : null,
                'class' => get_class($e)
            ]);
            throw $e;
        }

        try {
            \Log::info('UpdateUserPassword: Début de la mise à jour du mot de passe');
            
            $hashedPassword = Hash::make($input['password']);
            \Log::info('UpdateUserPassword: Mot de passe hashé créé');
            
            $user->forceFill([
                'password' => $hashedPassword,
            ]);
            \Log::info('UpdateUserPassword: forceFill() effectué');
            
            $result = $user->save();
            \Log::info('UpdateUserPassword: save() effectué', ['result' => $result]);
            
            \Log::info('UpdateUserPassword: Mise à jour du mot de passe terminée avec succès');
            
        } catch (\Exception $e) {
            \Log::error('UpdateUserPassword: Erreur lors de la mise à jour', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
