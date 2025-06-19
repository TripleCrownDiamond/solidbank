<?php

namespace App\Livewire\DepositManagement;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferStepAmount extends Component
{
    public $selectedSourceCurrency;
    public $availableBalance;
    public $transferAmount;
    public $transferReason;
    public $amountError = false;

    protected $listeners = [
        'amount-updated' => 'handleAmountUpdated',
        'validate-amount-step' => 'validateAmountStep',
    ];

    protected $rules = [
        'transferAmount' => 'required|numeric|min:0.01',
        'transferReason' => 'nullable|string|max:500',
    ];

    public function mount($selectedSourceCurrency, $availableBalance, $transferAmount = null, $transferReason = null)
    {
        $this->selectedSourceCurrency = $selectedSourceCurrency;
        $this->availableBalance = $availableBalance;
        $this->transferAmount = $transferAmount;
        $this->transferReason = $transferReason;

        // Check initial validity
        $this->checkAmountStepValidity();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ($propertyName === 'transferAmount') {
            $this->validateAmount();
        }
        $this->dispatch('update-transfer-amount', $this->transferAmount);
        $this->dispatch('update-transfer-reason', $this->transferReason);

        // Check if all required fields are filled and valid
        $this->checkAmountStepValidity();
    }

    protected function checkAmountStepValidity()
    {
        try {
            $this->validate();
            $this->validateAmount();
            // If validation passes and no amount error, emit event to update parent component
            if (!$this->amountError) {
                $this->dispatch('amount-step-validity-changed', ['valid' => true]);
            } else {
                $this->dispatch('amount-step-validity-changed', ['valid' => false]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, emit event to update parent component
            $this->dispatch('amount-step-validity-changed', ['valid' => false]);
        }
    }

    public function handleAmountUpdated($data)
    {
        if (is_array($data) && isset($data['amount'])) {
            $this->transferAmount = $data['amount'];
            $this->validateAmount();
        }
    }

    protected function validateAmount()
    {
        $amount = (float) $this->transferAmount;
        $this->amountError = $amount > $this->availableBalance;

        // Debugging: Log values to check validation logic

        $this->dispatch('amount-validation-updated', ['isValid' => !$this->amountError]);
    }

    #[On('validate-amount-step')]
    public function validateAmountStep()
    {
        $this->validate();
        $this->validateAmount();

        if (!$this->amountError) {
            $this->dispatch('transfer-step-amount-validated', [
                'transferAmount' => $this->transferAmount,
                'transferReason' => $this->transferReason,
            ]);
            
            // Émettre un événement pour déclencher l'envoi de l'OTP après que l'étape ait été incrémentée
            $this->dispatch('amount-step-validated');
        }
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-step-amount');
    }
}
