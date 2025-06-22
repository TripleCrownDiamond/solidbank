<?php

namespace App\Livewire\DepositManagement;

use App\Mail\TransferNotification;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransferStepGroup;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class TransferProgress extends Component
{
    public $progress = 0;
    public $currentStep = 1;
    public $maxSteps = 0;
    public $isCompleted = false;
    public $transferData = [];
    public $transaction = null;
    public $statusMessage = '';
    public $steps = [];
    /** @var \Illuminate\Support\Collection */
    public $transferSteps = [];
    public $transferId = null;
    public $isTransferStarted = false;
    public $showUnlockButton = false;
    public $unlockTransactionId = null;
    public $unlockStepId = null;
    public $transferStatus = 'ready'; // ready, starting, in_progress, blocked, completed
    public $isTransferBlocked = false;
    public $showStartButton = true;
    public $showProgressBar = false;

    public function mount($transferId = null)
    {
        $this->transferId = $transferId;

        // Récupérer les données du transfert
        $this->loadTransferData();

        if (empty($this->transferData)) {
            // Rediriger vers la page des transactions si aucune donnée
            return redirect()->route('transactions', ['locale' => app()->getLocale()]);
        }

        // Charger les étapes de transfert dynamiquement
        $this->loadTransferSteps();

        // Préparer le transfert (sera démarré manuellement)
        $this->progress = 0;
        $this->currentStep = 1;
        $this->statusMessage = __('transfers.ready_to_start');
        $this->isTransferStarted = false;
        $this->transferStatus = 'ready';
        $this->showStartButton = true;
        $this->showProgressBar = false;
        $this->isTransferBlocked = false;
        $this->showUnlockButton = false;
    }

    private function loadTransferData()
    {
        if ($this->transferId) {
            // Récupérer les données depuis la base de données via l'ID de transaction
            $this->transaction = Transaction::where('id', $this->transferId)
                ->where('user_id', Auth::id())
                ->first();

            if ($this->transaction) {
                // Reconstituer les données de transfert depuis la transaction
                $this->transferData = [
                    'transfer_amount' => $this->transaction->amount,
                    'transfer_currency' => $this->transaction->currency,
                    'transfer_reason' => $this->transaction->description,
                    'source_type' => $this->transaction->account_id ? 'account' : 'wallet',
                    'selected_source_id' => $this->transaction->account_id ?? $this->transaction->wallet_id,
                ];

                // Ajouter les informations spécifiques selon le type
                if ($this->transaction->external_bank_info) {
                    $bankInfo = is_string($this->transaction->external_bank_info)
                        ? json_decode($this->transaction->external_bank_info, true)
                        : $this->transaction->external_bank_info;

                    $this->transferData = array_merge($this->transferData, [
                        'recipient_name' => $bankInfo['recipient_name'] ?? '',
                        'recipient_iban' => $bankInfo['recipient_iban'] ?? '',
                        'recipient_bank' => $bankInfo['recipient_bank'] ?? '',
                        'recipient_country' => $bankInfo['recipient_country'] ?? '',
                    ]);
                }

                if ($this->transaction->external_crypto_info) {
                    $cryptoInfo = is_string($this->transaction->external_crypto_info)
                        ? json_decode($this->transaction->external_crypto_info, true)
                        : $this->transaction->external_crypto_info;

                    $this->transferData = array_merge($this->transferData, [
                        'crypto_address' => $cryptoInfo['crypto_address'] ?? '',
                        'crypto_network' => $cryptoInfo['crypto_network'] ?? '',
                    ]);
                }

            } else {
                Log::warning('Transaction non trouvée ou non autorisée', ['transfer_id' => $this->transferId]);
            }
        } else {
            // Récupérer les données du transfert depuis la session (mode création)
            $this->transferData = session('transfer_data', []);
        }
    }

    private function loadTransferSteps()
    {
        try {
            $sourceType = $this->transferData['source_type'] ?? null;
            $sourceId = $this->transferData['selected_source_id'] ?? null;

            if (!$sourceType || !$sourceId) {
                Log::warning('Type de source ou ID manquant pour charger les étapes de transfert');
                return;
            }

            $transferStepGroups = collect();

            if ($sourceType === 'account') {
                $account = Account::find($sourceId);
                if ($account) {
                    $transferStepGroups = $account->transferStepGroups()->where('is_active', true)->get();
                }
            } elseif ($sourceType === 'wallet') {
                $wallet = Wallet::find($sourceId);
                if ($wallet) {
                    $transferStepGroups = $wallet->transferStepGroups()->where('is_active', true)->get();
                }
            }

            // Récupérer toutes les étapes des groupes actifs
            $allSteps = collect();
            foreach ($transferStepGroups as $group) {
                $steps = $group->transferSteps()->orderBy('order')->get();
                $allSteps = $allSteps->merge($steps);
            }

            // Trier par ordre global
            $this->transferSteps = $allSteps->sortBy('order');

            // Construire le tableau des étapes pour l'affichage
            $this->steps = [];
            $stepNumber = 1;
            foreach ($this->transferSteps as $step) {
                $this->steps[$stepNumber] = $step->title;
                $stepNumber++;
            }

            $this->maxSteps = count($this->steps);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des étapes de transfert: ' . $e->getMessage());
            // Fallback vers des étapes par défaut si nécessaire
            $this->setDefaultSteps();
        }
    }

    private function setDefaultSteps()
    {
        // Pas d'étapes par défaut hardcodées
        $this->steps = [];
        $this->maxSteps = 0;
    }

    // Propriétés pour la modal de déblocage
    public $showStepModal = false;
    public $currentStepData = null;
    public $stepCode = '';
    public $stepCodeError = '';
    public $stepsWithPercentages = [];
    public $currentStepIndex = 0;
    
    // Propriétés pour le code de déblocage
    public $unlockCode = '';
    public $unlockError = '';

    public function startTransfer()
    {
        // 1. Récupérer les steps liés au compte ou wallet via les groupes
        $sourceType = $this->transferData['source_type'] ?? null;
        $sourceId = $this->transferData['selected_source_id'] ?? null;

        $transferSteps = collect();

        if ($sourceType && $sourceId) {
            $transferStepGroups = collect();

            if ($sourceType === 'account') {
                $account = \App\Models\Account::find($sourceId);
                if ($account) {
                    $transferStepGroups = $account->transferStepGroups()->where('is_active', true)->get();
                }
            } elseif ($sourceType === 'wallet') {
                $wallet = \App\Models\Wallet::find($sourceId);
                if ($wallet) {
                    $transferStepGroups = $wallet->transferStepGroups()->where('is_active', true)->get();
                }
            }

            // Récupérer toutes les étapes des groupes actifs
            $allSteps = collect();
            foreach ($transferStepGroups as $group) {
                $steps = $group->transferSteps()->orderBy('order')->get();
                $allSteps = $allSteps->merge($steps);
            }

            // Trier par ordre global
            $transferSteps = $allSteps->sortBy('order');
        }

        // 2. Compter les steps liés au compte/wallet
        $stepsCount = $transferSteps->count();

        // 3. Ajouter +1 au count
        $totalSteps = $stepsCount + 1;

        // 4. Calculer le pourcentage par step (100 / totalSteps)
        $percentagePerStep = $totalSteps > 0 ? 100 / $totalSteps : 0;

        // 5. Attribuer les pourcentages dans l'ordre
        $this->stepsWithPercentages = [];
        foreach ($transferSteps as $index => $step) {
            $stepPercentage = ($index + 1) * $percentagePerStep;
            $this->stepsWithPercentages[] = [
                'step' => $step,
                'order' => $step->order,
                'percentage' => round($stepPercentage, 2),
                'is_completed' => false  // sera mis à jour ci-dessous
            ];
        }

        // 6. Vérifier dans TransferStepCompletion quels steps sont complétés pour cette transaction
        if ($this->transaction) {
            $completedSteps = \App\Models\TransferStepCompletion::where('transaction_id', $this->transaction->id)
                ->with('transferStep')
                ->get();

            // Marquer les steps complétés
            foreach ($this->stepsWithPercentages as &$stepData) {
                $isCompleted = $completedSteps->contains(function ($completion) use ($stepData) {
                    return $completion->transfer_step_id === $stepData['step']->id;
                });
                $stepData['is_completed'] = $isCompleted;
            }
        }

        // Changer l'état du transfert
        $this->transferStatus = 'starting';
        $this->showStartButton = false;
        $this->showProgressBar = true;
        $this->isTransferStarted = true;
        $this->progress = 0;
        $this->currentStepIndex = 0;
        $this->statusMessage = __('transfers.transfer_starting');
        
        // Programmer le démarrage de la progression après un court délai
        $this->dispatch('start-transfer-progression', [
            'delay' => 1000 // 1 seconde
        ]);
    }
    
    public function beginTransferProgression()
    {
        // Cette méthode sera appelée après le délai pour commencer la vraie progression
        $this->transferStatus = 'in_progress';
        $this->statusMessage = __('transfers.transfer_in_progress');
        
        // Charger immédiatement la progression vers la première étape
        if (count($this->stepsWithPercentages) > 0) {
            $firstStep = $this->stepsWithPercentages[0];
            $this->progress = $firstStep['percentage'];
            
            // Démarrer le processus étape par étape
            $this->processNextStep();
        }
    }

    // Méthode de progression automatique supprimée - progression manuelle uniquement

    public function processNextStep()
    {
        // Si toutes les étapes sont terminées
        if ($this->currentStepIndex >= count($this->stepsWithPercentages)) {
            $this->progress = 100;
            $this->statusMessage = __('transfers.transfer_completed_successfully');
            $this->isCompleted = true;
            return;
        }

        // Vérifier que l'index est valide
        if (!isset($this->stepsWithPercentages[$this->currentStepIndex])) {
            return;
        }

        $currentStep = $this->stepsWithPercentages[$this->currentStepIndex];

        // Si l'étape est déjà complétée, passer à la suivante
        if ($currentStep['is_completed']) {
            $this->progress = $currentStep['percentage'];
            $this->statusMessage = __('transfers.step_already_completed', ['title' => $currentStep['step']->title]);
            $this->currentStepIndex++;

            // Passer à l'étape suivante après un délai
            $this->dispatch('step-completed');
            return;
        }

        // Charger la progression vers le pourcentage exact de l'étape
        $this->progress = $currentStep['percentage'];
        
        // Log pour tracer l'étape atteinte
        Log::info('Étape atteinte: ' . $currentStep['step']->title . ' (' . $currentStep['percentage'] . '%)', [
            'step_id' => $currentStep['step']->id,
            'step_title' => $currentStep['step']->title,
            'percentage' => $currentStep['percentage'],
            'step_index' => $this->currentStepIndex
        ]);
        
        // Programmer le changement de message et l'affichage de la modal après un délai
        $this->dispatch('show-modal-after-delay', [
            'stepData' => $currentStep,
            'delay' => 3000 // 3 secondes
        ]);
        
        // Programmer le changement de message après 1 seconde (quand la progression se bloque)
        $stepTitle = isset($currentStep['step']) && isset($currentStep['step']->title) ? $currentStep['step']->title : 'Étape inconnue';
        $statusMessage = __('transfers.step_in_progress') . ': ' . $stepTitle;
        
        $this->dispatch('change-status-message-after-delay', [
            'message' => $statusMessage,
            'delay' => 1000 // 1 seconde
        ]);
        
        // Programmer le blocage du transfert après un délai
        $this->dispatch('block-transfer-after-delay', [
            'transactionId' => $this->transaction->id,
            'stepId' => $currentStep['step']->id,
            'stepTitle' => $currentStep['step']->title,
            'delay' => 3000 // 3 secondes
        ]);
    }

    public function updateProgressToPercentage($targetPercentage, $stepData)
    {
        // Cette méthode sera appelée depuis JavaScript pour mettre à jour la progression
        $this->progress = $targetPercentage;
        
        // Mettre à jour le pourcentage de progression dans la transaction
        $this->updateTransactionProgress($targetPercentage);
    }
    
    public function blockTransfer($transactionId, $stepId, $stepTitle)
    {
        // Trouver les données de l'étape actuelle
        $currentStepData = null;
        foreach ($this->stepsWithPercentages as $stepData) {
            if ($stepData['step']->id == $stepId) {
                $currentStepData = $stepData;
                break;
            }
        }
        
        // Stocker les données de l'étape actuelle pour l'affichage
        $this->currentStepData = $currentStepData;
        
        // Changer l'état du transfert
        $this->transferStatus = 'blocked';
        $this->isTransferBlocked = true;
        $this->showUnlockButton = true;
        $this->isTransferStarted = false; // Arrêter l'animation de progression
        $this->showProgressBar = false; // Cacher la barre de progression
        $this->unlockTransactionId = $transactionId;
        $this->unlockStepId = $stepId;
        $this->statusMessage = ''; // Effacer le message de progression
        
        // Réinitialiser le code de déblocage et les erreurs
        $this->unlockCode = '';
        $this->unlockError = '';
    }
    
    public function verifyUnlockCode()
    {
        $this->resetErrorBag();
        $this->unlockError = '';
        
        // Valider le code de déblocage
        if (empty($this->unlockCode)) {
            $this->unlockError = __('transfers.unlock_code_required');
            return;
        }
        
        // Vérifier si l'étape actuelle nécessite un code de déblocage
        if (!$this->currentStepData) {
            $this->unlockError = __('transfers.invalid_step');
            return;
        }
        
        $step = $this->currentStepData['step'];
        
        // Vérifier le code de déblocage (remplacer par votre logique de validation)
        if ($this->unlockCode !== $step->unlock_code) {
            $this->unlockError = __('transfers.invalid_unlock_code');
            return;
        }
        
        // Si le code est valide, marquer l'étape comme complétée
        $this->markStepAsCompleted($step->id);
        
        // Réinitialiser l'état de blocage
        $this->isTransferBlocked = false;
        $this->showUnlockButton = false;
        $this->unlockCode = '';
        
        // Passer à l'étape suivante
        $this->currentStepIndex++;
        $this->processNextStep();
    }
    
    protected function markStepAsCompleted($stepId)
    {
        // Marquer l'étape comme complétée dans la base de données
        if ($this->transaction) {
            \App\Models\TransferStepCompletion::updateOrCreate(
                [
                    'transaction_id' => $this->transaction->id,
                    'transfer_step_id' => $stepId
                ],
                [
                    'completed_at' => now(),
                    'status' => 'completed'
                ]
            );
            
            // Mettre à jour le statut dans stepsWithPercentages
            foreach ($this->stepsWithPercentages as &$stepData) {
                if ($stepData['step']->id === $stepId) {
                    $stepData['is_completed'] = true;
                    break;
                }
            }
        }
    }
    
    public function redirectToUnlockPage($transactionId = null, $stepId = null)
    {
        // Utiliser les paramètres fournis ou ceux stockés
        $transactionId = $transactionId ?? $this->unlockTransactionId;
        $stepId = $stepId ?? $this->unlockStepId;
        
        \Log::info('Executing Livewire redirect to unlock page', [
            'transactionId' => $transactionId,
            'stepId' => $stepId
        ]);
        
        $locale = app()->getLocale();
        return $this->redirect(route('transfers.unlock', [
            'locale' => $locale,
            'transactionId' => $transactionId,
            'stepId' => $stepId
        ]));
    }
    
    public function unlockTransfer()
    {
        // Réinitialiser l'état de blocage
        $this->isTransferBlocked = false;
        $this->showUnlockButton = false;
        $this->transferStatus = 'in_progress';
        
        // Continuer avec l'étape suivante
        $this->currentStepIndex++;
        
        // Programmer la prochaine étape après un délai
        $this->dispatch('process-next-step-after-delay', [
            'delay' => 1000 // 1 seconde
        ]);
    }
    
    public function updateTransactionProgress($percentage)
    {
        if ($this->transaction) {
            $this->transaction->update([
                'progress_percentage' => $percentage
            ]);
        }
    }

    public function showModalAtPercentage($stepData)
    {
        // Vérifier que $stepData est valide
        if (!$stepData || !isset($stepData['step'])) {
            \Log::error('Invalid stepData provided to showModalAtPercentage', [
                'stepData' => $stepData
            ]);
            return;
        }

        \Log::info('Showing modal at target percentage', [
            'currentProgress' => $this->progress,
            'stepTitle' => $stepData['step']->title
        ]);

        // Afficher la modal de déblocage
        $this->currentStepData = $stepData;
        $this->stepCode = '';
        $this->stepCodeError = '';
        $this->showStepModal = true;
        $this->statusMessage = __('transfers.transfer_blocked');
    }

    public function changeStatusMessage($message)
    {
        \Log::info('Changing status message', [
            'old_message' => $this->statusMessage,
            'new_message' => $message
        ]);
        $this->statusMessage = $message;
    }

    public function showCardAfterLoading()
    {
        \Log::info('Showing modal after loading delay (deprecated - use showModalAtPercentage)');

        if ($this->currentStepIndex < count($this->stepsWithPercentages)) {
            $currentStep = $this->stepsWithPercentages[$this->currentStepIndex];

            // Afficher la modal de déblocage après le chargement
            $this->currentStepData = $currentStep;
            $this->stepCode = '';
            $this->stepCodeError = '';
            $this->showStepModal = true;
            $this->statusMessage = __('transfers.transfer_blocked');

            \Log::info('Modal data set', [
                'stepTitle' => $currentStep['step']->title,
                'currentStepData' => $this->currentStepData ? 'set' : 'null'
            ]);
        }
    }

    public function showStepModalAfterDelay($currentStep)
    {
        \Log::info('Programming modal display after delay (deprecated method)');

        // Utiliser JavaScript pour afficher la modal après 2 secondes
        $this->dispatch('show-modal-after-delay', [
            'stepData' => $currentStep,
            'delay' => 2000
        ]);
    }

    public function showStepModalAfterProgress()
    {
        \Log::info('showStepModalAfterProgress called', [
            'currentStepIndex' => $this->currentStepIndex,
            'stepsCount' => count($this->stepsWithPercentages),
            'showStepModal' => $this->showStepModal
        ]);

        if ($this->currentStepIndex < count($this->stepsWithPercentages)) {
            $currentStep = $this->stepsWithPercentages[$this->currentStepIndex];

            \Log::info('Setting modal data', [
                'stepTitle' => $currentStep['step']->title,
                'stepPercentage' => $currentStep['percentage']
            ]);

            // Afficher la modal de déblocage après la progression
            $this->currentStepData = $currentStep;
            $this->showStepModal = true;
            $this->stepCode = '';
            $this->stepCodeError = '';
            $this->statusMessage = 'Transfert Bloqué';

            \Log::info('Modal should be visible now', [
                'showStepModal' => $this->showStepModal,
                'currentStepData' => $this->currentStepData ? 'set' : 'null'
            ]);
        } else {
            \Log::warning('Cannot show modal - currentStepIndex out of bounds', [
                'currentStepIndex' => $this->currentStepIndex,
                'stepsCount' => count($this->stepsWithPercentages)
            ]);
        }
    }

    public function verifyStepCode()
    {
        if (!$this->currentStepData || !$this->stepCode) {
            $this->stepCodeError = __('transfers.unlock_code_required');
            return;
        }

        $step = $this->currentStepData['step'];

        // Vérifier si le code correspond
        if ($this->stepCode === $step->code) {
            // Marquer l'étape comme complétée
            if ($this->transaction) {
                \App\Models\TransferStepCompletion::create([
                    'transaction_id' => $this->transaction->id,
                    'transfer_step_id' => $step->id,
                    'entered_code' => $this->stepCode,
                    'completed_at' => now()
                ]);
            }

            // Mettre à jour le statut local
            $this->stepsWithPercentages[$this->currentStepIndex]['is_completed'] = true;

            // Fermer la modal
            $this->showStepModal = false;
            $this->currentStepData = null;

            // Passer à l'étape suivante
            $this->currentStepIndex++;
            $this->statusMessage = __('transfers.step_completed_successfully', ['title' => $step->title]);

            // Continuer avec l'étape suivante
            $this->processNextStep();
        } else {
            $this->stepCodeError = __('transfers.incorrect_unlock_code');
        }
    }

    public function closeStepModal()
    {
        $this->showStepModal = false;
        $this->currentStepData = null;
        $this->stepCode = '';
        $this->stepCodeError = '';

        // Remettre le message de statut à l'état précédent
        if ($this->currentStepIndex < count($this->stepsWithPercentages)) {
            $currentStep = $this->stepsWithPercentages[$this->currentStepIndex];
            $stepTitle = isset($currentStep['step']) && isset($currentStep['step']->title) ? $currentStep['step']->title : 'Étape inconnue';
            $this->statusMessage = __('transfers.step_in_progress') . ': ' . $stepTitle;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep < $this->maxSteps) {
            $this->currentStep++;
            $this->statusMessage = $this->steps[$this->currentStep] ?? 'Étape en cours';

            $progressPercentage = ($this->currentStep / $this->maxSteps) * 100;
            $this->updateProgress($this->currentStep, $progressPercentage);

            // Si c'est l'avant-dernière étape, traiter le transfert
            if ($this->currentStep === $this->maxSteps - 1) {
                $this->processTransfer();
            }
            // Si c'est la dernière étape, terminer le transfert
            elseif ($this->currentStep === $this->maxSteps) {
                $this->completeTransfer();
            }
            // Sinon, passer à l'étape suivante
            else {
                $this->dispatch('next-step');
            }
        }
    }

    private function updateProgress($step, $percentage)
    {
        $this->currentStep = $step;
        $this->progress = $percentage;
        $this->statusMessage = $this->steps[$step];
    }

    private function determineCurrentStep()
    {
        // Déterminer l'étape actuelle basée sur le statut de la transaction
        if ($this->transaction->status === Transaction::STATUS_COMPLETED) {
            $this->currentStep = $this->maxSteps;
            $this->progress = 100;
            $this->isCompleted = true;
            $this->statusMessage = 'Transfert terminé avec succès';
        } elseif ($this->transaction->status === Transaction::STATUS_PENDING) {
            // Transaction en cours - reprendre à l'avant-dernière étape
            $this->currentStep = max(1, $this->maxSteps - 1);
            $this->progress = ($this->currentStep / $this->maxSteps) * 100;
            $this->statusMessage = $this->steps[$this->currentStep] ?? 'Traitement en cours';
        } else {
            // Transaction créée mais pas encore traitée
            $this->currentStep = 1;
            $this->progress = $this->maxSteps > 0 ? (100 / $this->maxSteps) : 25;
            $this->statusMessage = $this->steps[$this->currentStep] ?? 'Initialisation';
        }

        Log::info('Étape actuelle déterminée', [
            'transaction_status' => $this->transaction->status,
            'current_step' => $this->currentStep,
            'progress' => $this->progress
        ]);
    }

    private function processTransfer()
    {
        try {
            // Si la transaction n'existe pas encore, la créer
            if (!$this->transaction) {
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
            } else {
                // Mettre à jour le statut de la transaction existante
                $this->transaction->update(['status' => Transaction::STATUS_PENDING]);
                Log::info('Transaction mise à jour', ['transaction_id' => $this->transaction->id]);
            }

            // Passer à l'étape suivante après un délai
            $this->dispatch('next-step');
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement de la transaction: ' . $e->getMessage());
            $this->statusMessage = 'Erreur lors du traitement du transfert';
        }
    }

    private function completeTransfer()
    {
        $this->isCompleted = true;
        $this->statusMessage = 'Transfert enregistré avec succès';

        // Mettre à jour le statut de la transaction à COMPLETED
        if ($this->transaction) {
            $this->transaction->update(['status' => Transaction::STATUS_COMPLETED]);
            Log::info('Transaction marquée comme terminée', ['transaction_id' => $this->transaction->id]);
        }

        // Nettoyer les données de session seulement si c'est un nouveau transfert
        if (!$this->transferId) {
            session()->forget('transfer_data');
        }

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
            Log::error("Erreur lors de l'envoi de l'email de notification: " . $e->getMessage());
        }
    }

    public function redirectToTransactions()
    {
        return redirect()->route('transactions', ['locale' => app()->getLocale()]);
    }

    /**
     * Générer l'URL de reprise du transfert
     */
    public function getResumeUrl()
    {
        if ($this->transaction) {
            return route('transfers.progress.resume', [
                'locale' => app()->getLocale(),
                'transferId' => $this->transaction->id
            ]);
        }
        return null;
    }

    /**
     * Vérifier si le transfert peut être repris
     */
    public function canResume()
    {
        return $this->transaction &&
            in_array($this->transaction->status, [Transaction::STATUS_PENDING]) &&
            !$this->isCompleted;
    }

    public function getAccountTransferStepsProperty()
    {
        if (empty($this->transferSteps)) {
            return [];
        }

        $steps = [];
        $currentStep = $this->currentStep ?? 1;

        foreach ($this->transferSteps as $index => $step) {
            $isCompleted = $step->order < $currentStep;
            $isCurrent = $step->order == $currentStep;

            $percentage = 0;
            $completedAt = null;

            if ($isCompleted) {
                $percentage = 100;
                $completedAt = now();
            } elseif ($isCurrent) {
                // Pour l'étape courante, calculer le pourcentage basé sur la progression
                $percentage = min(75, ($currentStep / count($this->transferSteps)) * 100);
            }

            $steps[] = [
                'id' => $step->id,
                'title' => $step->title ?? __('transfers.step_title_unavailable'),
                'description' => $step->description ?? __('transfers.step_description_unavailable'),
                'order' => $step->order,
                'type' => $step->type ?? __('transfers.step_type_unavailable'),
                'code' => $step->code ?? __('transfers.step_code_unavailable'),
                'group_name' => $step->transferStepGroup->name ?? 'Groupe par défaut',
                'is_completed' => $isCompleted,
                'is_current' => $isCurrent,
                'percentage' => $percentage,
                'completed_at' => $completedAt,
                'entered_code' => null
            ];
        }

        return $steps;
    }

    public function getOverallProgressProperty()
    {
        $accountSteps = $this->getAccountTransferStepsProperty();

        if (empty($accountSteps)) {
            return 0;
        }

        $completedSteps = collect($accountSteps)->where('is_completed', true)->count();
        $totalSteps = count($accountSteps);

        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-progress');
    }
}
