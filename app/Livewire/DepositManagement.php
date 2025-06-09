<?php

namespace App\Livewire;

use App\Mail\TransactionNotification;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DepositManagement extends Component
{
    // Modal states
    public $showDepositModal = false;
    public $showWithdrawalModal = false;
    public $showRecapModal = false;
    public $showTransferModal = false;
    public $showTransferRecapModal = false;
    public $showTransferStepsModal = false;
    public $showProgressModal = false;
    // Transaction type
    public $transactionType = 'deposit';  // 'deposit' or 'withdrawal'
    public $depositType = 'account';  // 'account' or 'wallet'
    // Form fields
    public $selectedAccountId = null;
    public $selectedWalletId = null;
    public $accountNumber = '';
    public $walletAddress = '';
    public $amount = '';
    public $reason = '';
    public $transferAmount = '';
    public $transferReason = '';
    public $currency = '';
    public $isLoading = false;
    // User detection
    public $detectedUser = null;
    public $detectedUserName = '';
    public $balanceError = '';
    // Recap data
    public $recapData = [];
    // Transfer properties
    public $transferType = 'internal';
    public $sourceType = 'account';
    public $selectedSourceId = null;
    public $recipientAccountNumber = '';
    public $recipientWalletAddress = '';
    public $recipientBankName = '';
    public $recipientName = '';
    public $recipientIdentifier = '';
    public $transferCurrency = '';
    public $senderAccountId = null;
    public $senderWalletId = null;
    public $detectedRecipient = null;
    public $detectedRecipientName = '';
    public $detectedRecipientCurrency = '';
    public $recipientError = '';
    public $currentStep = 1;
    public $totalSteps = 3;
    public $progressPercentage = 0;
    public $transferReference = '';
    public $userAccounts = [];
    public $userWallets = [];
    public $availableBalance = 0;
    // Transfer step management
    public $transferStep = 1;
    public $maxTransferStep = 3;
    public $selectedSourceCurrency = '';
    // External transfer fields
    public $recipientCountry = '';
    public $recipientIban = '';
    public $recipientBank = '';
    public $cryptoNetwork = '';
    public $cryptoAddress = '';

    protected $rules = [
        'depositType' => 'required|in:account,wallet',
        'amount' => 'required|numeric|min:0.01',
        'reason' => 'nullable|string|max:255',
    ];

    protected function messages()
    {
        return [
            'amount.required' => __('common.amount_required'),
            'amount.numeric' => __('common.amount_numeric'),
            'amount.min' => __('common.amount_min'),
            'reason.max' => __('common.reason_max_length'),
        ];
    }

    public function mount()
    {
        // Initialize with first account if user is not admin
        if (!Auth::user()->is_admin) {
            /** @var Account|null $firstAccount */
            $firstAccount = Account::where('user_id', Auth::id())->first();
            if ($firstAccount) {
                $this->selectedAccountId = $firstAccount->id;
                $this->currency = $firstAccount->currency ?? 'EUR';
            }
        }

        // Initialize user accounts and wallets for transfers
        $this->userAccounts = Auth::user()->accounts;
        $this->userWallets = Auth::user()->wallets;
    }

    public function render()
    {
        $accounts = Auth::user()->accounts;
        $wallets = Auth::user()->wallets;

        return view('livewire.deposit-management', compact('accounts', 'wallets'));
    }

    public function openDepositModal()
    {
        $this->transactionType = 'deposit';
        $this->showDepositModal = true;
        $this->resetForm();
    }

    public function closeDepositModal()
    {
        $this->showDepositModal = false;
        $this->resetForm();
    }

    public function openWithdrawalModal()
    {
        $this->transactionType = 'withdrawal';
        $this->showWithdrawalModal = true;
        $this->resetForm();
    }

    public function closeWithdrawalModal()
    {
        $this->showWithdrawalModal = false;
        $this->resetForm();
    }

    public function closeRecapModal()
    {
        $this->showRecapModal = false;
        $this->resetForm();
    }

    public function updatedDepositType($value)
    {
        $this->currency = '';
        $this->selectedAccountId = null;
        $this->selectedWalletId = null;
        $this->accountNumber = '';
        $this->walletAddress = '';

        // Auto-select first item for non-admin users
        if (!Auth::user()->is_admin) {
            if ($value === 'account') {
                /** @var Account|null $firstAccount */
                $firstAccount = Account::where('user_id', Auth::id())->first();
                if ($firstAccount) {
                    $this->selectedAccountId = $firstAccount->id;
                    $this->currency = $firstAccount->currency ?? 'EUR';
                }
            } elseif ($value === 'wallet') {
                /** @var Wallet|null $firstWallet */
                $firstWallet = Wallet::where('user_id', Auth::id())->first();
                if ($firstWallet) {
                    $this->selectedWalletId = $firstWallet->id;
                    $this->currency = strtoupper($firstWallet->coin);
                }
            }
        }
    }

    public function updatedRecipientWalletAddress($value)
    {
        if ($value && $this->transferType === 'internal') {
            $wallet = Wallet::with('user')->where('address', $value)->first();
            if ($wallet && $wallet->user_id !== Auth::id()) {
                $this->detectedRecipient = $wallet->user;
                $this->detectedRecipientName = $wallet->user->first_name . ' ' . $wallet->user->last_name;
                $this->transferCurrency = strtoupper($wallet->coin);
                $this->recipientError = '';
            } else {
                $this->detectedRecipient = null;
                $this->detectedRecipientName = '';
                $this->transferCurrency = '';
                $this->recipientError = __('transfers.recipient_not_found');
            }
        } else {
            $this->resetDetection();
        }
    }

    public function updatedSelectedAccountId($value)
    {
        if ($value) {
            $account = Account::find($value);
            if ($account) {
                $this->currency = $account->currency ?? 'EUR';
            }
        }
    }

    public function updatedSelectedWalletId($value)
    {
        if ($value) {
            $wallet = Wallet::find($value);
            if ($wallet) {
                $this->currency = strtoupper($wallet->coin);
            }
        }
    }

    public function updatedAccountNumber($value)
    {
        if (Auth::user()->is_admin && $value) {
            $account = Account::with('user')->where('account_number', $value)->first();
            if ($account) {
                $this->currency = $account->currency ?? 'EUR';
                $this->detectedUser = $account->user;
                $this->detectedUserName = $account->user->first_name . ' ' . $account->user->last_name;
                $this->availableBalance = $account->balance;
                $this->validateBalance();
            } else {
                $this->resetDetection();
            }
        }
    }

    public function updatedWalletAddress($value)
    {
        if (Auth::user()->is_admin && $value) {
            $wallet = Wallet::with('user')->where('address', $value)->first();
            if ($wallet) {
                $this->currency = strtoupper($wallet->cryptocurrency->symbol ?? $wallet->coin);
                $this->detectedUser = $wallet->user;
                $this->detectedUserName = $wallet->user->first_name . ' ' . $wallet->user->last_name;
                $this->availableBalance = $wallet->balance;
                $this->validateBalance();
            } else {
                $this->resetDetection();
            }
        }
    }

    public function updatedAmount($value)
    {
        $this->validateOnly('amount');
        $this->validateBalance();
    }

    public function updatedRecipientAccountNumber($value)
    {
        if ($value && $this->transferType === 'internal') {
            $account = Account::with('user')->where('account_number', $value)->first();
            if ($account && $account->user_id !== Auth::id()) {
                $this->detectedRecipient = $account->user;
                $this->detectedRecipientName = $account->user->first_name . ' ' . $account->user->last_name;
                $this->transferCurrency = $account->currency ?? 'EUR';
                $this->recipientError = '';

                // Check for currency mismatch
                if ($this->selectedSourceId) {
                    $source = $this->sourceType === 'account' ? Account::find($this->selectedSourceId) : Wallet::find($this->selectedSourceId);
                    if ($source && $source->currency !== $this->transferCurrency) {
                        $this->recipientError = __('transfers.currency_mismatch');
                    }
                }
            } else {
                $this->detectedRecipient = null;
                $this->detectedRecipientName = '';
                $this->transferCurrency = '';
                $this->recipientError = __('transfers.recipient_not_found');
            }
        }
    }

    private function resetDetection()
    {
        $this->currency = '';
        $this->detectedUser = null;
        $this->detectedUserName = '';
        $this->availableBalance = 0;
        $this->balanceError = '';
    }

    private function validateBalance()
    {
        if ($this->transactionType === 'withdrawal' && $this->amount && $this->availableBalance >= 0) {
            if ((float) $this->amount > $this->availableBalance) {
                $this->balanceError = __('common.balance_exceeded_error') . ' (' . number_format($this->availableBalance, 2) . ' ' . $this->currency . ')';
            } else {
                $this->balanceError = '';
            }
        } elseif ($this->transactionType === 'deposit') {
            // Pas de validation de solde pour les dépôts
            $this->balanceError = '';
        }
    }

    public function submitTransaction()
    {
        $this->validate();

        // Validation spécifique pour les retraits
        if ($this->transactionType === 'withdrawal' && $this->balanceError) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $this->balanceError
            ]);
            return;
        }

        // Additional validation based on user type and deposit type
        if (Auth::user()->is_admin) {
            if ($this->depositType === 'account') {
                $this->validate([
                    'accountNumber' => 'required|exists:accounts,account_number'
                ], [
                    'accountNumber.required' => __('common.account_number_required'),
                    'accountNumber.exists' => __('common.account_number_not_exists')
                ]);
            } else {
                $this->validate([
                    'walletAddress' => 'required|exists:wallets,address'
                ], [
                    'walletAddress.required' => __('common.wallet_address_required'),
                    'walletAddress.exists' => __('common.wallet_address_not_exists')
                ]);
            }
        } else {
            if ($this->depositType === 'account') {
                $this->validate([
                    'selectedAccountId' => 'required|exists:accounts,id'
                ], [
                    'selectedAccountId.required' => __('common.please_select_account'),
                    'selectedAccountId.exists' => __('common.selected_account_not_exists')
                ]);
            } else {
                $this->validate([
                    'selectedWalletId' => 'required|exists:wallets,id'
                ], [
                    'selectedWalletId.required' => __('common.please_select_wallet'),
                    'selectedWalletId.exists' => __('common.selected_wallet_not_exists')
                ]);
            }
        }

        // Préparer les données de récapitulatif
        $this->prepareRecapData();

        // Fermer le modal de dépôt/retrait et ouvrir le modal de récapitulatif
        $this->showDepositModal = false;
        $this->showWithdrawalModal = false;
        $this->showRecapModal = true;
    }

    private function prepareRecapData()
    {
        $this->recapData = [
            'type' => $this->transactionType,
            'depositType' => $this->depositType,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'reason' => $this->reason ?: __('common.no_reason_specified'),
            'userInfo' => $this->detectedUserName ?: (Auth::user()->first_name . ' ' . Auth::user()->last_name),
            'accountOrWallet' => $this->depositType === 'account'
                ? ($this->accountNumber ?: __('common.selected_account'))
                : ($this->walletAddress ?: __('common.selected_wallet'))
        ];

        // S'assurer qu'aucune clé 'transfer_type' n'est définie pour les dépôts/retraits
        // afin que la bonne modale de récapitulation soit affichée
        unset($this->recapData['transfer_type']);
    }

    public function confirmTransaction()
    {
        $this->isLoading = true;

        try {
            DB::transaction(function () {
                $transactionData = [
                    'type' => strtoupper($this->transactionType),
                    'amount' => $this->amount,
                    'currency' => $this->currency,
                    'status' => 'PENDING',
                    'description' => $this->reason,
                    'reference' => strtoupper(substr($this->transactionType, 0, 3)) . '-' . strtoupper(uniqid()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($this->depositType === 'account') {
                    if (Auth::user()->is_admin) {
                        $account = Account::where('account_number', $this->accountNumber)->first();
                    } else {
                        $account = Account::where('user_id', Auth::id())->find($this->selectedAccountId);
                    }

                    if (!$account) {
                        throw new \Exception(__('messages.account_not_found'));
                    }

                    if ($account->status !== 'ACTIVE') {
                        throw new \Exception(__('messages.transaction_not_allowed_inactive_account'));
                    }

                    $transactionData['account_id'] = $account->id;
                    $transactionData['user_id'] = $account->user_id;
                } else {
                    if (Auth::user()->is_admin) {
                        $wallet = Wallet::where('address', $this->walletAddress)->first();
                    } else {
                        $wallet = Wallet::where('user_id', Auth::id())->find($this->selectedWalletId);
                    }

                    if (!$wallet) {
                        throw new \Exception(__('messages.wallet_not_found'));
                    }

                    $transactionData['wallet_id'] = $wallet->id;
                    $transactionData['user_id'] = $wallet->user_id;
                }

                Transaction::create($transactionData);
            });

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => __('messages.transaction_created_successfully')
            ]);

            $this->closeRecapModal();
        } catch (\Exception $e) {
            Log::error('Transaction creation failed: ' . $e->getMessage());
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        $this->isLoading = false;
    }

    private function resetForm()
    {
        $this->depositType = 'account';
        $this->selectedAccountId = null;
        $this->selectedWalletId = null;
        $this->accountNumber = '';
        $this->walletAddress = '';
        $this->amount = '';
        $this->reason = '';
        $this->currency = '';
        // Ne pas réinitialiser transactionType pour préserver le type (deposit/withdrawal)
        $this->detectedUser = null;
        $this->detectedUserName = '';
        $this->availableBalance = 0;
        $this->balanceError = '';
        $this->recapData = [];

        $this->isLoading = false;

        // Reset validation errors
        $this->resetErrorBag();

        // Auto-select first item for non-admin users
        if (!Auth::user()->is_admin) {
            $firstAccount = Account::where('user_id', Auth::id())->first();
            if ($firstAccount) {
                $this->selectedAccountId = $firstAccount->id;
                $this->currency = $firstAccount->currency ?? 'EUR';
            }
        }
    }

    // Transfer methods
    public function openTransferModal()
    {
        $this->showTransferModal = true;
        $this->resetTransferForm();
    }

    public function closeTransferModal()
    {
        $this->showTransferModal = false;
        $this->resetTransferForm();
    }

    public function closeTransferRecapModal()
    {
        $this->showTransferRecapModal = false;
        $this->resetTransferForm();
    }

    public function closeTransferStepsModal()
    {
        $this->showTransferStepsModal = false;
        $this->resetTransferForm();
    }

    public function closeProgressModal()
    {
        $this->showProgressModal = false;
        $this->resetTransferForm();
    }

    public function submitTransfer()
    {
        // If we're not on the final step, move to the next step
        if ($this->transferStep < $this->maxTransferStep) {
            $this->nextTransferStepModal();
            return;
        }

        // Validate the transfer form
        $this->validate([
            'transferType' => 'required|in:internal,external',
            'sourceType' => 'required|in:account,wallet',
            'selectedSourceId' => 'required',
            'transferAmount' => 'required|numeric|min:0.01',
        ]);

        // Additional validation based on transfer type
        if ($this->transferType === 'internal') {
            $this->validate([
                'recipientIdentifier' => 'required',
            ]);

            if (!$this->detectedRecipientName) {
                $this->recipientError = __('transfers.recipient_not_found');
                return;
            }
        } else {  // external transfer
            $this->validate([
                'recipientName' => 'required',
            ]);

            if ($this->sourceType === 'account') {
                $this->validate([
                    'recipientCountry' => 'required',
                    'recipientIban' => 'required',
                    'recipientBank' => 'required',
                ]);
            } else {  // wallet
                $this->validate([
                    'cryptoNetwork' => 'required',
                    'cryptoAddress' => 'required',
                ]);
            }
        }

        // Check available balance
        if ($this->transferAmount > $this->availableBalance) {
            $this->balanceError = __('transfers.insufficient_balance');
            return;
        }

        // Set amount and reason from transfer fields
        $this->amount = $this->transferAmount;
        $this->reason = $this->transferReason;

        // Prepare recap data
        $this->prepareTransferRecapData();

        // Close transfer modal and open recap modal
        $this->showTransferModal = false;
        $this->showTransferRecapModal = true;
    }

    private function prepareTransferRecapData()
    {
        $this->recapData = [
            'transfer_type' => $this->transferType,
            'type' => 'transfer',
            'amount' => $this->transferAmount,
            'currency' => $this->transferCurrency,
            'reason' => $this->transferReason ?: __('common.no_reason_specified'),
            'recipient_account' => $this->recipientAccountNumber,
            'recipient_name' => $this->detectedRecipientName ?: $this->recipientName,
            'recipient_bank' => $this->recipientBankName,
            'sender_info' => Auth::user()->first_name . ' ' . Auth::user()->last_name
        ];
    }

    public function confirmTransfer()
    {
        try {
            // Generate transfer reference
            $this->transferReference = 'TRF-' . strtoupper(uniqid());

            // Start progress
            $this->currentStep = 1;
            $this->progressPercentage = 33;

            // Close recap modal and show progress
            $this->showTransferRecapModal = false;
            $this->showProgressModal = true;

            // Simulate transfer processing
            $this->dispatch('transferProcessing');
        } catch (\Exception $e) {
            Log::error('Transfer confirmation failed: ' . $e->getMessage());
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function nextTransferStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->progressPercentage = ($this->currentStep / $this->totalSteps) * 100;
        }

        if ($this->currentStep >= $this->totalSteps) {
            // Transfer completed
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => __('transfers.transfer_completed_successfully')
            ]);

            $this->closeProgressModal();
        }
    }

    private function resetTransferForm()
    {
        $this->transferType = 'internal';
        $this->sourceType = 'account';
        $this->selectedSourceId = null;
        $this->availableBalance = 0;
        $this->recipientAccountNumber = '';
        $this->recipientWalletAddress = '';
        $this->recipientBankName = '';
        $this->recipientName = '';
        $this->recipientIdentifier = '';
        $this->transferAmount = '';
        $this->transferReason = '';
        $this->transferCurrency = '';
        $this->senderAccountId = null;
        $this->senderWalletId = null;
        $this->detectedRecipient = null;
        $this->detectedRecipientName = '';
        $this->detectedRecipientCurrency = '';
        $this->recipientError = '';
        $this->currentStep = 1;
        $this->totalSteps = 3;
        $this->progressPercentage = 0;
        $this->transferReference = '';
        // Reset transfer step properties
        $this->transferStep = 1;
        $this->selectedSourceCurrency = '';
        $this->recipientCountry = '';
        $this->recipientIban = '';
        $this->recipientBank = '';
        $this->cryptoNetwork = '';
        $this->cryptoAddress = '';
        $this->transferAmount = '';
        $this->transferReason = '';
        $this->balanceError = '';

        // Reset validation errors
        $this->resetErrorBag();
    }

    public function updatedTransferType($value)
    {
        $this->recipientAccountNumber = '';
        $this->recipientBankName = '';
        $this->recipientName = '';
        $this->detectedRecipient = null;
        $this->detectedRecipientName = '';
        $this->transferCurrency = '';
    }

    public function updatedSourceType($value)
    {
        $this->selectedSourceId = null;
        $this->transferCurrency = '';
        $this->availableBalance = 0;
    }

    public function updatedSelectedSourceId($value)
    {
        if ($value) {
            if ($this->sourceType === 'account') {
                $account = Account::find($value);
                if ($account) {
                    $this->transferCurrency = $account->currency ?? 'EUR';
                    $this->selectedSourceCurrency = $account->currency ?? 'EUR';
                    $this->availableBalance = $account->balance;
                }
            } else {
                $wallet = Wallet::find($value);
                if ($wallet) {
                    $this->transferCurrency = strtoupper($wallet->coin);
                    $this->selectedSourceCurrency = strtoupper($wallet->coin);
                    $this->availableBalance = $wallet->balance;
                }
            }
        } else {
            $this->transferCurrency = '';
            $this->selectedSourceCurrency = '';
            $this->availableBalance = 0;
        }
    }

    // Transfer step navigation methods
    public function nextTransferStepModal()
    {
        // Validate current step before proceeding
        if ($this->transferStep === 1) {
            $this->validate([
                'sourceType' => 'required|in:account,wallet',
                'selectedSourceId' => 'required',
                'transferType' => 'required|in:internal,external'
            ]);
        } elseif ($this->transferStep === 2) {
            if ($this->transferType === 'internal') {
                $this->validate([
                    'recipientIdentifier' => 'required'
                ]);
            } else {
                $this->validate([
                    'recipientName' => 'required|string|max:255',
                    'recipientCountry' => 'required|string|max:255'
                ]);

                if ($this->sourceType === 'account') {
                    $this->validate([
                        'recipientIban' => 'required|string|max:255',
                        'recipientBank' => 'required|string|max:255'
                    ]);
                } else {
                    $this->validate([
                        'cryptoNetwork' => 'required|string|max:255',
                        'cryptoAddress' => 'required|string|max:255'
                    ]);
                }
            }
        }

        if ($this->transferStep < $this->maxTransferStep) {
            $this->transferStep++;
        }
    }

    public function previousTransferStepModal()
    {
        if ($this->transferStep > 1) {
            $this->transferStep--;
        }
    }

    public function submitTransferStep()
    {
        // Final validation for step 3
        $this->validate([
            'transferAmount' => 'required|numeric|min:0.01',
            'transferReason' => 'nullable|string|max:255'
        ]);

        // Check balance
        if ((float) $this->transferAmount > $this->availableBalance) {
            $this->addError('transferAmount', __('common.balance_exceeded_error') . ' (' . number_format($this->availableBalance, 2) . ' ' . $this->selectedSourceCurrency . ')');
            return;
        }

        // Prepare transfer recap data
        $this->prepareTransferRecapData();

        // Close transfer modal and open recap modal
        $this->showTransferModal = false;
        $this->showTransferRecapModal = true;
    }
}
