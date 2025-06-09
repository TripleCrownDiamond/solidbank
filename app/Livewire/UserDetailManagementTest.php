<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserDetailManagementTest extends Component
{
    public User $user;

    public function mount(User $user)
    {
        // Vérifier que l'utilisateur connecté est un administrateur
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }

        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user-detail-management-test');
    }
}
