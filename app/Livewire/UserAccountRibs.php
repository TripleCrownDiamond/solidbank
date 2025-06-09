<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Rib;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserAccountRibs extends Component
{
    public $user;
    public $accounts;
    public $expandedAccounts = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadUserAccounts();
    }

    public function loadUserAccounts()
    {
        if (!$this->user || $this->user->is_admin) {
            $this->accounts = collect();
            return;
        }

        // Get user's accounts with their RIBs
        $this->accounts = $this->user->accounts()->with('rib')->get();
    }

    public function toggleAccount($accountId)
    {
        if (in_array($accountId, $this->expandedAccounts)) {
            $this->expandedAccounts = array_filter($this->expandedAccounts, function ($id) use ($accountId) {
                return $id !== $accountId;
            });
        } else {
            $this->expandedAccounts[] = $accountId;
        }
    }

    public function isAccountExpanded($accountId)
    {
        return in_array($accountId, $this->expandedAccounts);
    }

    public function copyRibDetail($value, $type)
    {
        $message = __('common.copied_to_clipboard', ['type' => $type]);
        $this->dispatch('copy-to-clipboard', ['accountNumber' => $value, 'message' => $message]);
    }

    public function render()
    {
        return view('livewire.user-account-ribs');
    }
}
