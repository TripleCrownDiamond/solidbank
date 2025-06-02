<?php

namespace App\Livewire;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WelcomeDashboard extends Component
{
    public $user;
    public $account;

    public function mount()
    {
        $this->user = Auth::user();
        $this->account = Account::where('user_id', $this->user->id)->first();
    }

    public function render()
    {
        return view('livewire.welcome-dashboard', [
            'user' => $this->user,
            'account' => $this->account,
        ]);
    }
}
