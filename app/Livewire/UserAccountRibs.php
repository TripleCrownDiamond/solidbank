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
    public $copiedItems = [];

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
        
        // Auto-expand accounts that have RIBs
        foreach ($this->accounts as $account) {
            if ($account->rib) {
                $this->expandedAccounts[] = $account->id;
            }
        }
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
        \Log::info('copyRibDetail called', ['value' => $value, 'type' => $type]);
        
        $message = __('common.copied_to_clipboard', ['type' => $type]);
        $this->dispatch('copy-to-clipboard', ['accountNumber' => $value, 'message' => $message]);
        
        // Add to copied items for visual feedback
        $this->copiedItems[$value] = true;
        \Log::info('copiedItems after adding', ['copiedItems' => $this->copiedItems]);
        
        // Auto-remove after 2 seconds using JavaScript
        $this->dispatch('start-copy-timer', ['value' => $value]);
    }
    
    public function removeCopiedState($value)
    {
        \Log::info('removeCopiedState called', ['value' => $value]);
        unset($this->copiedItems[$value]);
        \Log::info('copiedItems after removing', ['copiedItems' => $this->copiedItems]);
    }
    
    public function isCopied($value)
    {
        $result = isset($this->copiedItems[$value]);
        \Log::info('isCopied called', ['value' => $value, 'result' => $result, 'copiedItems' => $this->copiedItems]);
        return $result;
    }

    public function render()
    {
        return view('livewire.user-account-ribs');
    }
}
