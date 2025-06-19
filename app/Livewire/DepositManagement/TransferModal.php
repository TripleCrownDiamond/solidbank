<?php

namespace App\Livewire\DepositManagement;

use App\Mail\TransferOtpMail;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferModal extends Component
{
    public $showTransferModal = false;
    public $transferStep = 1;
    public $maxTransferStep = 4;  // Source, Recipient, Amount, OTP
    // Properties that are still managed by TransferModal
    public $availableBalance = 0;
    public $transferCurrency = '';
    public $userAccounts = [];
    public $userWallets = [];
    // Properties that will be populated by child components
    public $sourceType;
    public $selectedSourceId;
    public $sourceAccount;  // Added to prevent property not found error
    public $recipientName;
    public $recipientCountry;
    public $recipientIban;
    public $recipientBank;
    public $cryptoNetwork;
    public $cryptoAddress;
    public $transferAmount;
    public $transferReason;
    public $amountError = false;
    // Validation states for each step
    public $sourceStepValid = false;
    public $recipientStepValid = false;
    public $amountStepValid = false;
    public $otpVerified = false;
    public $canGoBack = true;

    protected $listeners = [
        'transfer-step-source-validated' => 'handleSourceValidated',
        'open-transfer-modal' => 'openTransferModal',
        'close-transfer-modal' => 'closeTransferModal',
        'otp-verified' => 'submitTransfer',
        'transfer-step-source-validated' => 'handleSourceValidated',
        'transfer-step-recipient-validated' => 'handleRecipientValidated',
        'transfer-step-amount-validated' => 'handleAmountValidated',
        'amount-validation-updated' => 'handleAmountValidation',
        'otp-verified' => 'handleOtpVerified',
        'source-updated' => 'handleSourceUpdated',
        'recipient-step-validity-changed' => 'handleRecipientStepValidityChanged',
        'amount-step-validity-changed' => 'handleAmountStepValidityChanged',
        'amount-validated' => 'handleAmountValidated',
        'transfer-confirmed' => 'handleTransferConfirmed',
        'amount-step-validated' => 'handleAmountStepValidated'
    ];

    public function rules()
    {
        return [];
    }

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->userAccounts = $user?->accounts ?? collect();
        $this->userWallets = $user?->wallets ?? collect();
    }

    #[On('open-transfer-modal')]
    public function openTransferModal()
    {
        $this->resetValidation();
        $this->reset([
            'transferStep',
            'availableBalance',
            'transferCurrency',
            'sourceType',
            'selectedSourceId',
            'recipientName',
            'recipientCountry',
            'recipientIban',
            'recipientBank',
            'cryptoNetwork',
            'cryptoAddress',
            'transferAmount',
            'transferReason',
            'sourceStepValid',
            'recipientStepValid',
            'amountStepValid',
            'otpVerified',
        ]);
        $this->transferStep = 1;
        $this->showTransferModal = true;
    }

    public function handleAmountValidation($data)
    {
        if (is_array($data) && isset($data['isValid'])) {
            $this->amountError = !$data['isValid'];
            $this->amountStepValid = $data['isValid'] && !empty($this->transferAmount);
        } else {
            $this->amountError = !$data;
            $this->amountStepValid = $data && !empty($this->transferAmount);
        }
    }

    public function handleSourceUpdated($data)
    {
        $this->sourceType = $data['sourceType'];
        $this->selectedSourceId = $data['selectedSourceId'];
        $this->availableBalance = $data['availableBalance'];
        $this->transferCurrency = $data['transferCurrency'];
        $this->sourceStepValid = !empty($this->selectedSourceId);
    }

    public function handleRecipientStepValidityChanged($data)
    {
        $this->recipientStepValid = $data['valid'] ?? false;
    }

    public function handleAmountStepValidityChanged($data)
    {
        $this->amountStepValid = $data['valid'] ?? false;
    }

    public function handleOtpVerified()
    {
        $this->otpVerified = true;
        $this->canGoBack = false;  // Désactiver le bouton précédent après vérification OTP
        // Ne pas déclencher automatiquement le transfert, attendre la confirmation
    }

    public function closeTransferModal()
    {
        $this->showTransferModal = false;
    }

    public function nextTransferStepModal()
    {
        if ($this->transferStep === 1) {
            $this->dispatch('validate-source-step');
        } elseif ($this->transferStep === 2) {
            $this->dispatch('validate-recipient-step');
        } elseif ($this->transferStep === 3) {
            $this->dispatch('validate-amount-step');
            // L'étape sera incrémentée dans handleAmountValidated si la validation réussit
            // Ensuite, l'événement amount-step-validated sera émis par le composant TransferStepAmount
        }
    }

    public function previousTransferStepModal()
    {
        if ($this->transferStep > 1 && $this->canGoBack) {
            $this->transferStep--;
        }
    }

    public function handleSourceValidated($data)
    {
        $this->sourceType = $data['sourceType'];
        $this->selectedSourceId = $data['selectedSourceId'];
        $this->availableBalance = $data['availableBalance'];
        $this->transferCurrency = $data['transferCurrency'];
        $this->sourceStepValid = true;
        $this->transferStep++;
    }

    public function handleRecipientValidated($data)
    {
        $this->recipientName = $data['recipientName'];
        $this->recipientCountry = $data['recipientCountry'];
        $this->recipientIban = $data['recipientIban'] ?? null;
        $this->recipientBank = $data['recipientBank'] ?? null;
        $this->cryptoNetwork = $data['cryptoNetwork'] ?? null;
        $this->cryptoAddress = $data['cryptoAddress'] ?? null;
        $this->recipientStepValid = true;
        $this->transferStep++;
    }

    public function handleAmountValidated($data)
    {
        $this->transferAmount = $data['transferAmount'];
        $this->transferReason = $data['transferReason'];
        $this->amountStepValid = true;
        $this->transferStep++;
    }

    public function handleAmountStepValidated()
    {
        // Cette méthode est appelée après que l'étape ait été incrémentée à 4
        // Envoyer l'OTP pour l'étape de vérification
        if ($this->transferStep === 4) {
            $this->sendTransferOtp();
        }
    }

    public function sendTransferOtp()
    {
        // Générer un OTP de 6 chiffres
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Stocker l'OTP pour l'utilisateur authentifié avec expiration
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'two_factor_code' => $otp,
            'two_factor_expires_at' => now()->addMinutes(10)
        ]);

        // Envoyer l'e-mail OTP
        Mail::to($user->email)->send(new TransferOtpMail($user, $otp));

        Log::info(__('transfers.otp_sent_log'), [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email
        ]);
    }

    public function handleTransferConfirmed()
    {
        // Rediriger directement vers la progression sans message de succès
        // et sans enregistrer la transaction (sera fait dans ProgressModal)
        $this->showProgressAndSubmitTransfer();
    }

    public function showProgressAndSubmitTransfer()
    {
        // Prepare transaction data for transfer management
        $transactionData = [
            'sourceType' => $this->sourceType,
            'selectedSourceId' => $this->selectedSourceId,
            'transferAmount' => $this->transferAmount,
            'transferCurrency' => $this->transferCurrency,
            'transferReason' => $this->transferReason,
            'recipientName' => $this->recipientName,
            'recipientCountry' => $this->recipientCountry,
            'recipientIban' => $this->recipientIban,
            'recipientBank' => $this->recipientBank,
            'cryptoNetwork' => $this->cryptoNetwork,
            'cryptoAddress' => $this->cryptoAddress,
        ];

        // Close transfer modal
        $this->showTransferModal = false;

        // dd($transactionData);
        // Show transfer progress with transaction data
        $this->dispatch('show-transfer-progress', $transactionData);

        // Reset the modal state after starting the progress
        $this->reset([
            'transferStep',
            'availableBalance',
            'transferCurrency',
            'sourceType',
            'selectedSourceId',
            'recipientName',
            'recipientCountry',
            'recipientIban',
            'recipientBank',
            'cryptoNetwork',
            'cryptoAddress',
            'transferAmount',
            'transferReason',
            'sourceStepValid',
            'recipientStepValid',
            'amountStepValid',
            'otpVerified',
            'canGoBack',
        ]);
    }

    /*
    public function submitTransfer()
    {
        // This method is kept for backward compatibility
        $this->showProgressAndSubmitTransfer();
    }
    */
    
    public function render()
    {
        return view('livewire.deposit-management.transfer-modal');
    }
}
