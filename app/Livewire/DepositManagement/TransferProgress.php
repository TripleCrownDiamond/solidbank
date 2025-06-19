<?php

namespace App\Livewire\DepositManagement;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransferStepGroup;
use App\Models\TransferStep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransferNotification;

class TransferProgress extends Component
{
    public $progress = 0;
    public $currentStep = 1;
    public $maxSteps = 4;
    public $isCompleted = false;
    public $transferData = [];
    public $transaction = null;
    public $statusMessage = '';
    public $showCodeModal = false;
    public $verificationCode = '';
    public $isLoaderStarted = true;
    public $dynamicSteps = [];
    public $stepThresholds = [];
    public $showStepDetails = false;

    protected $listeners = ['stepReached' => 'handleStepReached'];

    private function loadDynamicSteps()
    {
        $sourceType = $this->transferData['source_type'] ?? null;
        $sourceId = $this->transferData['selected_source_id'] ?? null;

        $transferStepGroup = null;

        if ($sourceType === 'account' && $sourceId) {
            $transferStepGroup = TransferStepGroup::whereHas('accounts', function ($query) use ($sourceId) {
                $query->where('account_id', $sourceId);
            })->with('transferSteps')->first();
        } elseif ($sourceType === 'wallet' && $sourceId) {
            $transferStepGroup = TransferStepGroup::whereHas('wallets', function ($query) use ($sourceId) {
                $query->where('wallet_id', $sourceId);
            })->with('transferSteps')->first();
        }

        if ($transferStepGroup && $transferStepGroup->transferSteps->isNotEmpty()) {
            $this->dynamicSteps = $transferStepGroup->transferSteps->sortBy('order')->values()->toArray();
        } else {
            // Default steps if no specific group is found
            $this->dynamicSteps = [
                ['id' => 1, 'title' => 'Initialisation du Transfert', 'description' => 'Préparation de votre demande de transfert.', 'type' => 'automatic', 'code' => null, 'order' => 1],
                ['id' => 2, 'title' => 'Vérification de Sécurité', 'description' => 'Veuillez confirmer votre identité pour des raisons de sécurité.', 'type' => 'verification', 'code' => 'OTP', 'order' => 2],
                ['id' => 3, 'title' => 'Traitement du Transfert', 'description' => 'Votre transfert est en cours de traitement.', 'type' => 'automatic', 'code' => null, 'order' => 3],
                ['id' => 4, 'title' => 'Transfert Terminé', 'description' => 'Votre transfert a été effectué avec succès.', 'type' => 'automatic', 'code' => null, 'order' => 4],
            ];
        }

        $this->maxSteps = count($this->dynamicSteps);
        $this->stepThresholds = [];
        foreach ($this->dynamicSteps as $index => $step) {
            $this->stepThresholds[$step['order']] = round((($index + 1) / $this->maxSteps) * 100);
        }
    }

    public function handleStepReached($stepNumber)
    {
        if ($stepNumber > $this->maxSteps) {
            $this->completeTransfer();
            return;
        }

        $this->currentStep = $stepNumber;
        $this->progress = $this->stepThresholds[$stepNumber] ?? 0;
        $currentDynamicStep = collect($this->dynamicSteps)->firstWhere('order', $stepNumber);

        if ($currentDynamicStep) {
            $this->statusMessage = $currentDynamicStep['title'];
            $this->showStepDetails = true;

            switch ($currentDynamicStep['type']) {
                case 'verification':
                    $this->showCodeModal = true;
                    // Optionally, send OTP here if not already sent
                    break;
                case 'automatic':
                    // Automatically proceed to the next step after a short delay
                    $this->dispatch('auto-continue')->self();
                    break;
                case 'manual':
                    // Wait for user action (e.g., button click)
                    break;
            }
        } else {
            Log::warning('Step not found in dynamicSteps: ' . $stepNumber);
            $this->statusMessage = 'Erreur: Étape non trouvée.';
        }

        // If it's an automatic step, or if it's the last step, proceed with the transfer logic
        if ($currentDynamicStep['type'] === 'automatic' || $stepNumber === $this->maxSteps) {
            if ($stepNumber === 2) {
                $this->processTransfer();
            } elseif ($stepNumber === 3) {
                $this->completeTransfer();
            }
        }
    }

    public function mount()
    {
        $this->transferData = session('transfer_data', []);

        if (empty($this->transferData)) {
            return redirect()->route('transactions', ['locale' => app()->getLocale()]);
        }

        $this->loadDynamicSteps();
        $this->statusMessage = $this->dynamicSteps[$this->currentStep]['title'] ?? 'Initialisation du transfert';
        $this->showCodeModal = false; // Ensure it's always initialized
    }

    private function updateProgress($step, $percentage)
    {
        $this->currentStep = $step;
        $this->progress = $percentage;
        $currentDynamicStep = collect($this->dynamicSteps)->firstWhere('order', $step);
        if ($currentDynamicStep) {
            $this->statusMessage = $currentDynamicStep['title'];
        }
    }

    private function processTransfer()
    {
        try {
            // Créer la transaction avec le statut PENDING
            $this->transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'TRANSFER',
                'amount' => $this->transferData['transfer_amount'],
                'currency' => $this->transferData['transfer_currency'],
                'status' => Transaction::STATUS_PENDING,
                'description' => $this->transferData['transfer_reason'] ?? 'Transfert',
                'reference' => 'TRF-' . strtoupper(uniqid()),
                'account_id' => $this->transferData['source_type'] === 'account' ? $this->transferData['selected_source_id'] : null,
                'wallet_id' => $this->transferData['source_type'] === 'wallet' ? $this->transferData['selected_source_id'] : null,
                'external_bank_info' => $this->transferData['source_type'] === 'account' ? [
                    'recipient_name' => $this->transferData['recipient_name'],
                    'recipient_iban' => $this->transferData['recipient_iban'],
                    'recipient_bank' => $this->transferData['recipient_bank'],
                    'recipient_country' => $this->transferData['recipient_country']
                ] : null,
                'external_crypto_info' => $this->transferData['source_type'] === 'wallet' ? [
                    'crypto_address' => $this->transferData['crypto_address'],
                    'crypto_network' => $this->transferData['crypto_network']
                ] : null
            ]);

            Log::info('Transaction créée avec succès', ['transaction_id' => $this->transaction->id]);
            
            // Passer à l'étape suivante après un délai
            $this->dispatch('next-step');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la transaction: ' . $e->getMessage());
            $this->statusMessage = 'Erreur lors du traitement du transfert';
        }
    }

    private function completeTransfer()
    {
        $this->isCompleted = true;
        $this->statusMessage = 'Transfert enregistré avec succès';
        
        // Nettoyer les données de session
        session()->forget('transfer_data');
        
        // Envoyer un email de notification (optionnel)
        try {
            if ($this->transaction && Auth::user()) {
                Mail::to(Auth::user()->email)->send(new TransferNotification(
                    Auth::user(),
                    $this->transaction,
                    'transfer_completed'
                ));
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de notification: ' . $e->getMessage());
        }
    }

    public function redirectToTransactions()
    {
        return redirect()->route('transactions', ['locale' => app()->getLocale()]);
    }

    public function closeCodeModal()
    {
        $this->showCodeModal = false;
    }

    public function verifyCode()
    {
        // Logic to verify the code
        // For now, just close the modal and proceed
        $this->showCodeModal = false;
        $this->dispatch('next-step');
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-progress', [
            'showStepDetails' => $this->showStepDetails,
            'showCodeModal' => $this->showCodeModal,
            'currentStep' => $this->currentStep,
            'isCompleted' => $this->isCompleted,
            'statusMessage' => $this->statusMessage,
            'dynamicSteps' => $this->dynamicSteps,
            'stepThresholds' => $this->stepThresholds,
            'maxSteps' => $this->maxSteps,
        ]);
    }
}