<?php

namespace App\Livewire\DepositManagement;

use App\Models\Account;
use App\Models\Wallet;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferStepSource extends Component
{
    public $sourceType;
    public $userAccounts;
    public $userWallets;
    public $selectedSourceId;
    public $availableBalance = null;
    public $transferCurrency = null;

    public function updatedSelectedSourceId($value)
    {
        $this->validate();

        $availableBalance = 0;
        $transferCurrency = '';

        if ($this->sourceType === 'account') {
            $source = Account::find($value);
            if ($source) {
                $availableBalance = $source->balance;
                $transferCurrency = $source->currency;
            }
        } elseif ($this->sourceType === 'wallet') {
            $source = Wallet::with('cryptocurrency')->find($value);
            if ($source) {
                $availableBalance = $source->balance;
                $transferCurrency = strtoupper($source->cryptocurrency->symbol);
            }
        }

        $this->availableBalance = $availableBalance;
        $this->transferCurrency = $transferCurrency;
        
        // Dispatch an event to update the parent component
        $this->dispatch('source-updated', [
            'sourceType' => $this->sourceType,
            'selectedSourceId' => $this->selectedSourceId,
            'availableBalance' => $availableBalance,
            'transferCurrency' => $transferCurrency
        ]);
    }

    protected $rules = [
        'selectedSourceId' => 'required',
        'sourceType' => 'required|in:account,wallet',
    ];

    public function mount($sourceType, $userAccounts, $userWallets, $selectedSourceId = null)
    {
        $this->sourceType = $sourceType;
        $this->userAccounts = $userAccounts;
        $this->userWallets = $userWallets;
        $this->selectedSourceId = $selectedSourceId;
    }

    public function updatedSourceType($value)
    {
        // Réinitialiser les valeurs quand le type de source change
        $this->selectedSourceId = null;
        $this->availableBalance = null;
        $this->transferCurrency = null;
        
        // Réinitialiser la validation
        $this->resetValidation();
        
        // Informer le composant parent du changement
        $this->dispatch('source-type-changed', [
            'sourceType' => $value
        ]);
    }

    #[On('validate-source-step')]
    public function validateSourceStep()
    {
        $this->validate();

        $availableBalance = 0;
        $transferCurrency = '';

        if ($this->sourceType === 'account') {
            $source = Account::find($this->selectedSourceId);
            if ($source) {
                $availableBalance = $source->balance;
                $transferCurrency = $source->currency;
            }
        } elseif ($this->sourceType === 'wallet') {
            $source = Wallet::find($this->selectedSourceId);
            if ($source) {
                $availableBalance = $source->balance;
                $transferCurrency = $source->cryptocurrency->symbol;
            }
        }

        $this->dispatch('transfer-step-source-validated', [
            'sourceType' => $this->sourceType,
            'selectedSourceId' => $this->selectedSourceId,
            'availableBalance' => $availableBalance,
            'transferCurrency' => $transferCurrency,
        ]);
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-step-source');
    }
}