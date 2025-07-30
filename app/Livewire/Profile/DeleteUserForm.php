<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public $confirmingUserDeletion = false;
    public $password = '';

    protected $rules = [
        'password' => 'required|string|current_password:web',
    ];

    protected $messages = [
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.current_password' => 'Le mot de passe fourni ne correspond pas à votre mot de passe actuel.',
    ];

    public function confirmUserDeletion()
    {
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(DeletesUsers $deleter)
    {
        $this->validate();

        $deleter->delete(Auth::user()->fresh());

        return redirect('/');
    }

    public function render()
    {
        return view('profile.delete-user-form');
    }
}