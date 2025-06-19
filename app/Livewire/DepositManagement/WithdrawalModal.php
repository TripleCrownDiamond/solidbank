<?php

namespace App\Livewire\DepositManagement;

use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WithdrawalModal extends Component
{
    public $showWithdrawalModal = false;
    public $withdrawalType = 'account';
    public $accountNumber = '';
    public $walletAddress = '';
    public $selectedAccountId = null;
    public $selectedWalletId = null;
    public $amount = '';
    public $reason = '';
    public \Illuminate\Support\Collection $cryptoCurrencies;
    public $selectedCrypto = '';
    public $cryptoAddress = '';
    public $tag = '';
    public $withdrawalMethod = 'crypto';
    public $balanceError = null;
    public $currency = null;
    public $availableBalance = null;
    public $detectedUserName = null;
    public $recapData = null;

    public $bankTransferDetails = [
        'account_holder' => '',
        'account_number' => '',
        'bank_name' => '',
        'swift_code' => '',
        'iban' => ''
    ];

    public $userAccounts = [];
    public $userWallets = [];
    public $isLoading = false;

    protected $listeners = [
        'open-withdrawal-modal' => 'openWithdrawalModal',
        'transfer-confirmed' => 'handleTransferConfirmed'
    ];

    public function mount()
    {
        $this->loadUserAccounts();
        $this->cryptoCurrencies = CryptoCurrency::active()->get();
    }

    public function loadUserAccounts()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->userAccounts = Account::where('user_id', $user->id)->where('status', 'ACTIVE')->get();
            $this->userWallets = Wallet::where('user_id', $user->id)->with('cryptocurrency')->get();
        }
    }

    public function openWithdrawalModal($data = [])
    {
        $this->resetExcept(['userAccounts', 'userWallets', 'cryptoCurrencies']);
        $this->currency = config('app.default_currency', 'EUR');
        $this->availableBalance = null;
        $this->detectedUserName = null;

        if (!empty($data)) {
            $this->fill($data);
        }

        $this->showWithdrawalModal = true;
    }

    public function closeWithdrawalModal()
    {
        $this->showWithdrawalModal = false;
        $this->resetExcept(['userAccounts', 'userWallets', 'cryptoCurrencies']);
        $this->currency = config('app.default_currency', 'EUR');
        $this->availableBalance = null;
        $this->detectedUserName = null;
    }

    public function updatedWithdrawalType()
    {
        $this->reset([
            'selectedAccountId', 'selectedWalletId',
            'accountNumber', 'walletAddress',
            'availableBalance', 'detectedUserName',
            'balanceError', 'amount'
        ]);
    }

    public function updatedSelectedAccountId()
    {
        $this->updateAccountDetails();
    }

    public function updatedSelectedWalletId()
    {
        $this->updateWalletDetails();
    }

    public function updatedAccountNumber($value)
    {
        if (Auth::user()->is_admin) {
            $this->reset(['detectedUserName', 'availableBalance', 'currency', 'balanceError']);
            if (strlen($value) >= 3) {
                $account = Account::where('account_number', $value)->first();
                if ($account) {
                    $this->detectedUserName = $account->user->name;
                    $this->currency = $account->currency;
                    $this->availableBalance = $account->balance;
                } else {
                    $this->detectedUserName = __('common.user_not_found');
                }
            }
        }
        $this->validateAmount();
    }

    public function updatedWalletAddress($value)
    {
        if (Auth::user()->is_admin) {
            $this->reset(['detectedUserName', 'availableBalance', 'currency', 'balanceError']);
            if (strlen($value) >= 3) {
                $wallet = Wallet::where('address', $value)->first();
                if ($wallet) {
                    $this->detectedUserName = $wallet->user->name;
                    $this->currency = $wallet->cryptocurrency->symbol;
                    $this->availableBalance = $wallet->balance;
                } else {
                    $this->detectedUserName = __('common.user_not_found');
                }
            }
        }
        $this->validateAmount();
    }

    public function updatedAmount()
    {
        $this->validateAmount();
    }

    protected function updateAccountDetails()
    {
        if ($this->selectedAccountId) {
            $account = Account::find($this->selectedAccountId);
            if ($account) {
                $this->availableBalance = $account->balance;
                $this->currency = $account->currency;
                $this->detectedUserName = $account->user->name;
                $this->validateAmount();
            }
        } else {
            $this->reset(['availableBalance', 'currency', 'detectedUserName', 'balanceError']);
        }
    }

    protected function updateWalletDetails()
    {
        if ($this->selectedWalletId) {
            $wallet = Wallet::find($this->selectedWalletId);
            if ($wallet) {
                $this->availableBalance = $wallet->balance;
                $this->currency = $wallet->cryptocurrency->code;
                $this->detectedUserName = $wallet->user->name;
                $this->validateAmount();
            }
        } else {
            $this->reset(['availableBalance', 'currency', 'detectedUserName', 'balanceError']);
        }
    }

    protected function validateAmount()
    {
        $this->balanceError = null;

        if (!$this->amount) {
            return;
        }

        $amount = (float) $this->amount;
        if ($amount <= 0) {
            $this->balanceError = __('validation.min.numeric', ['attribute' => 'amount', 'min' => 0]);
            return;
        }

        if ($this->amount > 0 && $this->availableBalance !== null) {
            if ($this->amount > $this->availableBalance) {
                $this->balanceError = __('common.insufficient_balance');
            } else {
                $this->balanceError = null;
            }
        } else {
            $this->balanceError = null;
        }
    }

    public function submitWithdrawal()
    {
        try {
            $this->validateWithdrawal();

            if ($this->balanceError) {
                $this->dispatch('toast', ['type' => 'error', 'message' => $this->balanceError]);
                return;
            }

            $user = Auth::user();
            $target = null;
            $targetType = null;
            $targetId = null;

            // Déterminer la cible selon le type de retrait
            if ($this->withdrawalType === 'account') {
                if ($user->is_admin && $this->accountNumber) {
                    $target = Account::where('account_number', $this->accountNumber)->first();
                    if (!$target) {
                        $this->addError('accountNumber', __('common.account_not_found'));
                        return;
                    }
                } else {
                    $target = Account::find($this->selectedAccountId);
                }
                $targetType = Account::class;
                $description = __('common.withdrawal_from_account') . ' ' . $target->account_number;
            } else {
                if ($user->is_admin && $this->walletAddress) {
                    $target = Wallet::where('address', $this->walletAddress)->first();
                    if (!$target) {
                        $this->addError('walletAddress', __('common.wallet_not_found'));
                        return;
                    }
                } else {
                    $target = Wallet::find($this->selectedWalletId);
                }
                $targetType = Wallet::class;
                $description = __('common.withdrawal_from_wallet') . ' ' . $target->address;
            }

            if (!$target) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => __('common.target_not_found'),
                    'dismissible' => true
                ]);
                return;
            }

            // Vérifier le solde suffisant
            if ($this->amount > $target->balance) {
                $this->addError('amount', __('common.insufficient_balance'));
                return;
            }

            // Créer la transaction de retrait
            $transactionData = [
                'user_id' => $user->id,
                'type' => 'WITHDRAWAL',
                'amount' => $this->amount,
                'currency' => $this->currency,
                'description' => $this->reason ?: $description,
                'status' => 'PENDING',
                'metadata' => [
                    'method' => $this->withdrawalMethod,
                    'address' => $this->cryptoAddress,
                    'tag' => $this->tag,
                    'bank_details' => $this->withdrawalMethod === 'bank' ? $this->bankTransferDetails : null,
                ]
            ];

            // Associer avec la cible appropriée
            if ($targetType === Account::class) {
                $transactionData['account_id'] = $target->id;
            } else {
                $transactionData['wallet_id'] = $target->id;
            }

            $this->prepareRecapData($transactionData, $target);

            $this->showWithdrawalModal = false;
            $this->dispatch('open-recap-modal', $this->recapData);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'dismissible' => true
            ]);
        }
    }

    public function prepareRecapData($transactionData, $target)
    {
        $userInfo = null;
        $accountOrWallet = null;

        if (Auth::user()->is_admin) {
            $userInfo = $this->detectedUserName;
            $accountOrWallet = $target ? ($this->withdrawalType === 'account' ? $target->account_number : $target->address) : 'N/A';
        } else {
            $userInfo = Auth::user()->name;
            $accountOrWallet = $target ? ($this->withdrawalType === 'account' ? $target->account_number : $target->address) : 'N/A';
        }

        $this->recapData = [
            'type' => 'WITHDRAWAL',
            'withdrawalType' => $this->withdrawalType,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'userInfo' => $userInfo,
            'accountOrWallet' => $accountOrWallet,
            'reason' => $this->reason,
            'target_id' => $target ? $target->id : null,
            'target_type' => $target ? get_class($target) : null,
            'metadata' => $transactionData['metadata']
        ];
    }

    protected function validateWithdrawal()
    {
        $rules = [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:255'],
            'withdrawalMethod' => ['required', 'in:crypto,bank'],
        ];

        $user = Auth::user();

        if ($this->withdrawalType === 'account') {
            if ($user->is_admin) {
                $rules['accountNumber'] = ['required', 'exists:accounts,account_number'];
            } else {
                $rules['selectedAccountId'] = ['required', 'exists:accounts,id,user_id,' . $user->id];
            }
        } else {
            if ($user->is_admin) {
                $rules['walletAddress'] = ['required', 'exists:wallets,address'];
            } else {
                $rules['selectedWalletId'] = ['required', 'exists:wallets,id,user_id,' . $user->id];
            }

            if ($this->withdrawalMethod === 'crypto') {
                $rules['cryptoAddress'] = ['required', 'string'];
                $rules['selectedCrypto'] = ['required', 'exists:crypto_currencies,id'];

                // Vérifier si la crypto sélectionnée nécessite un tag/memo
                $crypto = $this->cryptoCurrencies->where('id', $this->selectedCrypto)->first();
                if ($crypto && $crypto->requires_tag) {
                    $rules['tag'] = ['required', 'string'];
                }
            } else {
                $rules['bankTransferDetails.account_holder'] = ['required', 'string', 'max:255'];
                $rules['bankTransferDetails.account_number'] = ['required', 'string', 'max:50'];
                $rules['bankTransferDetails.bank_name'] = ['required', 'string', 'max:255'];
                $rules['bankTransferDetails.swift_code'] = ['required', 'string', 'max:50'];
                $rules['bankTransferDetails.iban'] = ['required', 'string', 'max:50'];
            }
        }

        $this->validate($rules);
        $this->validateAmount();

        if ($this->balanceError) {
            $this->addError('amount', $this->balanceError);
            throw new \Exception($this->balanceError);
        }
    }

    public function render()
    {
        return view('livewire.deposit-management.withdrawal-modal', [
            'accounts' => $this->userAccounts,
            'wallets' => $this->userWallets,
            'cryptoCurrencies' => $this->cryptoCurrencies,
        ]);
    }

    public function handleTransferConfirmed()
    {
        try {
            $this->isLoading = true;

            // Create the transaction record
            $transactionData = [
                'user_id' => Auth::id(),
                'type' => 'WITHDRAWAL',
                'amount' => $this->amount,
                'currency' => $this->currency,
                'description' => $this->reason,
                'status' => \App\Models\Transaction::STATUS_COMPLETED,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Add source information based on withdrawal type
            if ($this->withdrawalType === 'account') {
                $account = Account::find($this->selectedAccountId);
                if ($account) {
                    $transactionData['from_account_id'] = $account->id;
                    $transactionData['account_id'] = $account->id;
                }
            } else {
                $wallet = Wallet::find($this->selectedWalletId);
                if ($wallet) {
                    $transactionData['wallet_id'] = $wallet->id;
                }
            }

            // Suppression de la création de la transaction
            // Suppression de l'affichage du message de succès

            // Réinitialisation du formulaire et fermeture de la modale
            $this->reset(['withdrawalType', 'selectedAccountId', 'selectedWalletId', 'accountNumber', 'walletAddress', 'amount', 'reason', 'currency', 'isLoading']);
            $this->showWithdrawalModal = false;

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
