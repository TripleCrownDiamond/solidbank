<?php

namespace App\Livewire\DepositManagement;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DepositModal extends Component
{
    public $showDepositModal = false;

    public $depositType = 'account';

    public $transactionType = 'deposit';

    public $selectedAccountId;

    public $selectedWalletId;

    public $accountNumber;

    public $walletAddress;

    public $amount;

    public $reason;

    public $currency;

    public $isLoading = false;

    public $recapData;

    public $detectedUser;

    public $detectedUserName;

    public $availableBalance;

    public $balanceError;

    protected $listeners = [
        'open-deposit-modal' => 'openDepositModal',
        'transfer-confirmed' => 'handleTransferConfirmed'
    ];

    public function mount()
    {
        $this->currency = config('app.default_currency', 'EUR');
        if (!Auth::user()->is_admin) {
            $this->selectedAccountId = Auth::user()->accounts->first()->id ?? null;
        }
    }

    public function render()
    {
        $accounts = Auth::user()->accounts;
        $wallets = Auth::user()->wallets;

        return view('livewire.deposit-management.deposit-modal', compact('accounts', 'wallets'));
    }

    public function openDepositModal()
    {
        $this->resetForm();
        $this->showDepositModal = true;
    }

    public function closeDepositModal()
    {
        $this->showDepositModal = false;
        $this->resetForm();
    }

    public function updatedDepositType()
    {
        $this->reset(['accountNumber', 'walletAddress', 'detectedUser', 'detectedUserName', 'currency', 'availableBalance', 'balanceError']);
        $this->validateOnly('depositType');
    }

    public function updatedSelectedAccountId($value)
    {
        $account = Account::find($value);
        if ($account) {
            $this->currency = $account->currency;
            $this->availableBalance = $account->balance;
        } else {
            $this->currency = null;
            $this->availableBalance = null;
        }
        $this->validateBalance();
    }

    public function updatedSelectedWalletId($value)
    {
        $wallet = Wallet::find($value);
        if ($wallet) {
            $this->currency = $wallet->cryptocurrency->symbol;
            $this->availableBalance = $wallet->balance;
        } else {
            $this->currency = null;
            $this->availableBalance = null;
        }
        $this->validateBalance();
    }

    public function updatedAccountNumber($value)
    {
        if (Auth::user()->is_admin) {
            $this->reset(['detectedUser', 'detectedUserName', 'currency', 'availableBalance', 'balanceError']);
            if (strlen($value) >= 3) {
                $account = Account::where('account_number', $value)->first();
                if ($account) {
                    $this->detectedUser = $account->user;
                    $this->detectedUserName = $account->user->name;
                    $this->currency = $account->currency;
                    $this->availableBalance = $account->balance;
                } else {
                    $this->detectedUserName = __('common.user_not_found');
                }
            }
        }
        $this->validateBalance();
    }

    public function updatedWalletAddress($value)
    {
        if (Auth::user()->is_admin) {
            $this->reset(['detectedUser', 'detectedUserName', 'currency', 'availableBalance', 'balanceError']);
            if (strlen($value) >= 3) {
                $wallet = Wallet::where('address', $value)->first();
                if ($wallet) {
                    $this->detectedUser = $wallet->user;
                    $this->detectedUserName = $wallet->user->name;
                    $this->currency = $wallet->cryptocurrency->symbol;
                    $this->availableBalance = $wallet->balance;
                } else {
                    $this->detectedUserName = __('common.user_not_found');
                }
            }
        }
        $this->validateBalance();
    }

    public function updatedAmount()
    {
        $this->validateBalance();
    }

    public function resetDetection()
    {
        $this->detectedUser = null;
        $this->detectedUserName = null;
        $this->currency = null;
        $this->availableBalance = null;
        $this->balanceError = null;
    }

    public function validateBalance()
    {
        $this->balanceError = null;
        if ($this->amount > 0 && $this->availableBalance !== null && $this->transactionType === 'withdrawal') {
            if ($this->amount > $this->availableBalance) {
                $this->balanceError = __('common.insufficient_funds');
            }
        }
    }

    protected function rules()
    {
        $rules = [
            'depositType' => ['required', Rule::in(['account', 'wallet'])],
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
        ];

        if (Auth::user()->is_admin) {
            if ($this->depositType === 'account') {
                $rules['accountNumber'] = ['required', 'exists:accounts,account_number'];
            } else {
                $rules['walletAddress'] = ['required', 'exists:wallets,address'];
            }
        } else {
            if ($this->depositType === 'account') {
                $rules['selectedAccountId'] = 'required|exists:accounts,id';
            } else {
                $rules['selectedWalletId'] = 'required|exists:wallets,id';
            }
        }

        return array_merge($rules, [
            'accountNumber.exists' => __('validation.account_number_not_found'),
            'walletAddress.exists' => __('validation.wallet_address_not_found'),
            'selectedAccountId.exists' => __('validation.account_not_found'),
            'selectedWalletId.exists' => __('validation.wallet_not_found'),
        ]);
    }

    public function submitTransaction()
    {
        $this->validate();

        if ($this->balanceError) {
            $this->dispatch('toast', ['type' => 'error', 'message' => $this->balanceError]);
            return;
        }

        $this->prepareRecapData();

        $this->showDepositModal = false;
        // dd('submitTransaction called');
        $this->dispatch('open-recap-modal', $this->recapData);
    }

    public function prepareRecapData()
    {
        $target = null;
        $userInfo = null;
        $accountOrWallet = null;

        if (Auth::user()->is_admin) {
            if ($this->depositType === 'account') {
                $target = Account::where('account_number', $this->accountNumber)->first();
            } else {
                $target = Wallet::where('address', $this->walletAddress)->first();
            }
            $userInfo = $this->detectedUserName;
            $accountOrWallet = $target ? ($this->depositType === 'account' ? $target->account_number : $target->address) : 'N/A';
        } else {
            if ($this->depositType === 'account') {
                $target = Account::find($this->selectedAccountId);
            } else {
                $target = Wallet::find($this->selectedWalletId);
            }
            $userInfo = Auth::user()->name;
            $accountOrWallet = $target ? ($this->depositType === 'account' ? $target->account_number : $target->address) : 'N/A';
        }

        $this->recapData = [
            'type' => $this->transactionType,
            'depositType' => $this->depositType,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'userInfo' => $userInfo,
            'accountOrWallet' => $accountOrWallet,
            'reason' => $this->reason,
            'target_id' => $target ? $target->id : null,
            'target_type' => $target ? get_class($target) : null,
        ];
    }

    public function resetForm()
    {
        $this->reset(['depositType', 'selectedAccountId', 'selectedWalletId', 'accountNumber', 'walletAddress', 'amount', 'reason', 'currency', 'isLoading', 'detectedUser', 'detectedUserName', 'availableBalance', 'balanceError']);
        if (!Auth::user()->is_admin) {
            $this->selectedAccountId = Auth::user()->accounts->first()->id ?? null;
        }
        $this->resetValidation();
    }

    public function handleTransferConfirmed()
    {
        try {
            $this->isLoading = true;

            // Suppression de la création de la transaction
            // Suppression de l'affichage du message de succès

            // Réinitialisation du formulaire et fermeture de la modale
            $this->resetForm();
            $this->showDepositModal = false;

        } catch (\Exception $e) {
            // Affichage du message d'erreur
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $e->getMessage() ?: __('common.transaction_failed'),
                'dismissible' => true
            ]);
        } finally {
            $this->isLoading = false;
        }
    }
}
