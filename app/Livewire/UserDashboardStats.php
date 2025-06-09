<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class UserDashboardStats extends Component
{
    public $user;
    public $account;
    public $accountBalance;
    public $latestTransaction;
    public $cards;
    public $wallets;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadUserStats();
    }

    public function loadUserStats()
    {
        if (!$this->user || $this->user->is_admin) {
            return;
        }

        // Get user's account
        $this->account = $this->user->account;

        if ($this->account && $this->account->status === 'ACTIVE') {
            // Get account balance
            $this->accountBalance = $this->account->balance ?? 0;

            // Get latest transaction
            $this->latestTransaction = Transaction::where(function ($query) {
                $query
                    ->where('from_account_id', $this->account->id)
                    ->orWhere('to_account_id', $this->account->id);
            })
                ->orderBy('created_at', 'desc')
                ->first();
        } else {
            $this->accountBalance = 0;
            $this->latestTransaction = null;
        }

        // Get user's cards
        $this->cards = Card::where('user_id', $this->user->id)->get();

        // Get user's crypto wallets
        $this->wallets = Wallet::where('user_id', $this->user->id)->get();
    }



    public function render()
    {
        return view('livewire.user-dashboard-stats');
    }
}
