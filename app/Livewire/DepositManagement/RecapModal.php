<?php

namespace App\Livewire\DepositManagement;

use Livewire\Component;
use Livewire\Attributes\On;

class RecapModal extends Component
{
    public $showRecapModal = false;
    public $recapData = [];

    public function mount() {}

    #[On('open-recap-modal')]
    public function openRecapModal(array $recapData)
    {
        $this->showRecapModal = true;
        $this->recapData = $recapData;
    }

    public function closeRecapModal()
    {
        $this->showRecapModal = false;
        $this->recapData = [];
    }

    public function confirmTransaction()
    {
        try {
            // For deposits and withdrawals, emit the confirm-transaction event
            // Transfers are now handled directly by TransferModal
            $this->dispatch('confirm-transaction', $this->recapData);
            
            // Close the modal
            $this->closeRecapModal();
            
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'dismissible' => true
            ]);
        }
    }

    public function render()
    {
        return view('livewire.deposit-management.recap-modal');
    }
}
