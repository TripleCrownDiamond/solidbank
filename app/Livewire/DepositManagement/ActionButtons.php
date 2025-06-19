<?php

namespace App\Livewire\DepositManagement;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActionButtons extends Component
{


    public function render()
    {
        return view('livewire.deposit-management.action-buttons');
    }

    public function openDepositModal()
    {
        $this->dispatch('open-deposit-modal');
    }

    public function openWithdrawalModal()
    {
        $this->dispatch('open-withdrawal-modal');
    }

    public function openTransferModal()
    {
        $this->dispatch('open-transfer-modal');
    }
}
