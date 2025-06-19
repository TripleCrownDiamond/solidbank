<?php

namespace App\Livewire\DepositManagement;

use Livewire\Component;
use Livewire\Attributes\On;

class TransferStepRecipient extends Component
{
    public $sourceType;
    public $recipientName;
    public $recipientCountry;
    public $recipientIban;
    public $recipientBank;
    public $cryptoNetwork;
    public $cryptoAddress;
    public $showCountryField = true;

    protected $listeners = ['validate-recipient-step' => 'validateRecipientStep'];

    public function mount($sourceType)
    {
        $this->sourceType = $sourceType;
        $this->updateCountryFieldVisibility();
    }

    public function updatedSourceType($value)
    {
        $this->updateCountryFieldVisibility();
    }

    protected function updateCountryFieldVisibility()
    {
        $this->showCountryField = $this->sourceType === 'account';
    }

    public function updated($propertyName)
    {
        // Validate in real-time when any field is updated
        $this->validateOnly($propertyName);
        
        // Check if all required fields are filled and valid
        $this->checkRecipientStepValidity();
    }

    protected function checkRecipientStepValidity()
    {
        try {
            $this->validate();
            // If validation passes, emit event to update parent component
            $this->dispatch('recipient-step-validity-changed', ['valid' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, emit event to update parent component
            $this->dispatch('recipient-step-validity-changed', ['valid' => false]);
        }
    }

    #[On('validate-recipient-step')]
    public function validateRecipientStep()
    {
        $this->validate();

        $this->dispatch('transfer-step-recipient-validated', [
            'recipientName' => $this->recipientName,
            'recipientCountry' => $this->recipientCountry,
            'recipientIban' => $this->recipientIban,
            'recipientBank' => $this->recipientBank,
            'cryptoNetwork' => $this->cryptoNetwork,
            'cryptoAddress' => $this->cryptoAddress,
        ]);
    }

    public function rules()
    {
        $rules = [
            'recipientName' => 'required|string|max:255',
        ];

        if ($this->sourceType === 'account') {
            $rules = array_merge($rules, [
                'recipientCountry' => 'required|string|max:100',
                'recipientIban' => 'required|string|max:50',
                'recipientBank' => 'required|string|max:255',
            ]);
        } else {
            $rules = array_merge($rules, [
                'cryptoNetwork' => 'required|string|max:100',
                'cryptoAddress' => 'required|string|max:255',
            ]);
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-step-recipient');
    }
}