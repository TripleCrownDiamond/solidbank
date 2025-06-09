<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete($user)
    {
        // Delete profile photo
        $user->deleteProfilePhoto();

        // Delete all tokens
        $user->tokens->each->delete();

        // Delete all user relations (except transfer step groups)
        $user->accounts()->delete();
        $user->wallets()->delete();
        $user->ribs()->delete();
        $user->transactions()->delete();

        // Delete document files
        if ($user->identity_document_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->identity_document_url);
        }
        if ($user->address_document_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->address_document_url);
        }

        // Finally delete the user
        $user->delete();
    }
}
