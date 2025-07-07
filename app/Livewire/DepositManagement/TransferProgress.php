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
    public $transferStatus = 'ready';  // ready, starting, in_progress, blocked, completed
    public $isTransferBlocked = false;
    public $showStartButton = true;
    public $showProgressBar = false;
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
    public $isVerifying = false;

    // Propriétés calculées pour éviter les erreurs
    protected $computed = ['accountTransferSteps', 'overallProgress'];

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

        // Préparer les étapes avec pourcentages pour vérifier l'état
        $this->prepareStepsWithPercentages();

        // Déterminer l'état du transfert basé sur les étapes complétées
        $this->determineTransferState();
    }

    /**
     * Détermine l'état du transfert basé sur les étapes complétées
     */
    private function determineTransferState()
    {
        $hasCompletedSteps = $this->hasCompletedSteps();
        $allStepsCompleted = $this->areAllStepsCompleted();

        if ($allStepsCompleted) {
            // Toutes les étapes sont complétées - cacher le bouton
            $this->transferStatus = 'completed';
            $this->showStartButton = false;
            $this->showProgressBar = true;
            $this->progress = 100;
            $this->statusMessage = __('transfers.transfer_completed_successfully');
            $this->isCompleted = true;
        } elseif ($hasCompletedSteps) {
            // Au moins une étape est complétée - afficher "Continuer"
            $this->transferStatus = 'resumable';
            $this->showStartButton = true;
            $this->showProgressBar = true;  // Garder la barre visible pendant le transfert
            $this->statusMessage = __('transfers.ready_to_continue');
        } else {
            // Aucune étape complétée - afficher "Démarrer"
            $this->transferStatus = 'ready';
            $this->showStartButton = true;
            $this->showProgressBar = false;
            $this->statusMessage = __('transfers.ready_to_start');
        }

        // Ne pas réinitialiser isTransferStarted si le transfert est en cours
        if ($this->transferStatus !== 'in_progress' && $this->transferStatus !== 'starting') {
            $this->isTransferStarted = false;
        }
        $this->isTransferBlocked = false;
        $this->showUnlockButton = false;
        $this->currentStep = 1;
        $this->progress = $this->calculateCurrentProgress();

        // Dispatcher l'événement pour rafraîchir l'interface
        $this->dispatch('transfer-state-updated');
        $this->dispatch('progress-updated', progress: $this->progress);
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

                    if (is_array($bankInfo)) {
                        $this->transferData = array_merge($this->transferData, [
                            'recipient_name' => $bankInfo['recipient_name'] ?? '',
                            'recipient_iban' => $bankInfo['recipient_iban'] ?? '',
                            'recipient_bank' => $bankInfo['recipient_bank'] ?? '',
                            'recipient_country' => $bankInfo['recipient_country'] ?? '',
                        ]);
                    }
                }

                if ($this->transaction->external_crypto_info) {
                    $cryptoInfo = is_string($this->transaction->external_crypto_info)
                        ? json_decode($this->transaction->external_crypto_info, true)
                        : $this->transaction->external_crypto_info;

                    if (is_array($cryptoInfo)) {
                        $this->transferData = array_merge($this->transferData, [
                            'crypto_address' => $cryptoInfo['crypto_address'] ?? '',
                            'crypto_network' => $cryptoInfo['crypto_network'] ?? '',
                        ]);
                    }
                }
            } else {
                Log::warning(__('transfers.transaction_not_found_or_unauthorized'), ['transfer_id' => $this->transferId]);
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
                Log::warning(__('transfers.missing_source_type_or_id'));
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
            Log::error(__('transfers.error_loading_progress_data') . ': ' . $e->getMessage());
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

    public function startTransfer()
    {
        Log::info(__('transfers.begin_transfer_progression'), [
            'transferData_empty' => empty($this->transferData),
            'transaction_exists' => $this->transaction !== null,
            'transferStatus' => $this->transferStatus
        ]);

        // Vérifier que les données de transfert sont disponibles
        if (empty($this->transferData)) {
            Log::error(__('transfers.no_valid_progress_data_received'));
            $this->statusMessage = __('common.error_occurred');
            return;
        }

        // Vérifier qu'une transaction existe
        if (!$this->transaction) {
            Log::error(__('transfers.transaction_not_found'), [
                'transferId' => $this->transferId,
                'transferData' => $this->transferData
            ]);
            $this->statusMessage = __('transfers.transaction_not_found');
            return;
        }

        // Préparer les étapes avec pourcentages
        $this->prepareStepsWithPercentages();

        Log::info(__('transfers.begin_transfer_progression'), [
            'transferStatus' => $this->transferStatus,
            'showStartButton' => $this->showStartButton,
            'showProgressBar' => $this->showProgressBar,
            'stepsWithPercentages_count' => count($this->stepsWithPercentages)
        ]);

        // Changer l'état du transfert
        $this->transferStatus = 'starting';
        $this->showStartButton = false;
        $this->showProgressBar = true;
        $this->isTransferStarted = true;
        // Utiliser la progression actuelle basée sur les étapes complétées
        $this->progress = $this->calculateCurrentProgress();
        $this->currentStepIndex = 0;
        $this->statusMessage = __('transfers.transfer_starting');

        Log::info(__('transfers.transfer_process_started_successfully'), [
            'transferStatus' => $this->transferStatus,
            'showStartButton' => $this->showStartButton,
            'showProgressBar' => $this->showProgressBar,
            'progress' => $this->progress
        ]);

        // Dispatcher immédiatement l'événement de changement d'état
        $this->dispatch('transfer-state-updated');

        // Programmer le démarrage de la progression après un court délai
        $this->dispatch('start-transfer-progression',
            delay: 0  // Pas de délai
        );

        Log::info(__('transfers.delayed_block_transfer_event_dispatched'));
    }

    private function prepareStepsWithPercentages()
    {
        Log::info(__('transfers.begin_transfer_progression'), [
            'transferData' => $this->transferData,
            'transaction_id' => $this->transaction ? $this->transaction->id : null
        ]);

        // Récupérer les étapes de transfert
        $sourceType = $this->transferData['source_type'] ?? null;
        $sourceId = $this->transferData['selected_source_id'] ?? null;

        Log::info(__('transfers.source_data'), [
            'sourceType' => $sourceType,
            'sourceId' => $sourceId
        ]);

        $transferSteps = collect();

        if ($sourceType && $sourceId) {
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
            $transferSteps = $allSteps->sortBy('order');
        }

        Log::info(__('transfers.steps_retrieved'), [
            'transferSteps_count' => $transferSteps->count(),
            'transferSteps' => $transferSteps->map(function ($step) {
                return [
                    'id' => $step->id,
                    'title' => $step->title,
                    'order' => $step->order
                ];
            })->toArray()
        ]);

        // Compter les steps liés au compte/wallet
        $stepsCount = $transferSteps->count();

        // Ajouter +1 au count pour la finalisation
        $totalSteps = $stepsCount + 1;

        // Calculer le pourcentage par step
        $percentagePerStep = $totalSteps > 0 ? 100 / $totalSteps : 0;

        // Attribuer les pourcentages dans l'ordre
        $this->stepsWithPercentages = [];
        foreach ($transferSteps as $index => $step) {
            $stepPercentage = ($index + 1) * $percentagePerStep;
            $this->stepsWithPercentages[] = [
                'step' => $step,
                'order' => $step->order,
                'percentage' => round($stepPercentage, 2),
                'is_completed' => false
            ];
        }

        // Vérifier les étapes déjà complétées
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

        Log::info(__('transfers.end_prepare_steps_with_percentages'), [
            'stepsWithPercentages_count' => count($this->stepsWithPercentages),
            'stepsWithPercentages' => array_map(function ($stepData) {
                return [
                    'step_id' => $stepData['step']->id,
                    'step_title' => $stepData['step']->title,
                    'percentage' => $stepData['percentage'],
                    'is_completed' => $stepData['is_completed']
                ];
            }, $this->stepsWithPercentages)
        ]);
    }

    public function beginTransferProgression()
    {
        Log::info(__('transfers.begin_transfer_progression'), [
            'transferStatus' => $this->transferStatus,
            'stepsWithPercentages_count' => count($this->stepsWithPercentages)
        ]);

        // Cette méthode sera appelée après le délai pour commencer la vraie progression
        $this->transferStatus = 'in_progress';
        $this->statusMessage = __('transfers.transfer_in_progress');

        // Dispatcher l'événement de changement d'état
        $this->dispatch('transfer-state-updated');

        // S'assurer que les étapes sont préparées
        if (empty($this->stepsWithPercentages)) {
            Log::info(__('transfers.empty_steps_preparing'));
            $this->prepareStepsWithPercentages();
        }

        // Vérifier qu'il y a des étapes à traiter
        if (empty($this->stepsWithPercentages)) {
            Log::warning(__('transfers.no_transfer_steps_found'), [
                'transferData' => $this->transferData,
                'transaction_id' => $this->transaction ? $this->transaction->id : null
            ]);
            $this->statusMessage = __('transfers.no_steps_found');
            return;
        }

        // Trouver la prochaine étape non complétée
        $nextStepIndex = $this->findNextIncompleteStep();

        Log::info(__('transfers.next_step_found'), [
            'nextStepIndex' => $nextStepIndex,
            'stepsWithPercentages_count' => count($this->stepsWithPercentages)
        ]);

        $this->currentStepIndex = $nextStepIndex;

        // S'il y a des étapes à traiter
        if ($nextStepIndex !== null && isset($this->stepsWithPercentages[$nextStepIndex])) {
            $nextStep = $this->stepsWithPercentages[$nextStepIndex];

            // Vérifier que la structure de l'étape est correcte
            if (!isset($nextStep['step']) || !$nextStep['step']) {
                Log::error(__('transfers.invalid_step_structure'), [
                    'nextStepIndex' => $nextStepIndex,
                    'nextStep' => $nextStep,
                    'stepsWithPercentages' => $this->stepsWithPercentages
                ]);
                $this->statusMessage = __('transfers.step_structure_error');
                return;
            }

            // Animer la progression vers l'étape suivante
            $this->animateProgressToStep($nextStep['percentage']);

            // Vérifier que la transaction existe avant de continuer
            if (!$this->transaction) {
                Log::error(__('transfers.missing_transaction_to_continue'));
                $this->statusMessage = __('transfers.transaction_not_found');
                return;
            }

            // Log des données avant dispatch
            Log::info(__('transfers.delayed_block_transfer_event_dispatched'), [
                'transactionId' => $this->transaction->id,
                'stepId' => $nextStep['step']->id,
                'stepTitle' => $nextStep['step']->title,
                'nextStep_structure' => $nextStep
            ]);

            // Ne programmer le blocage que si ce n'est pas la dernière étape et si le pourcentage est atteint
            if (!$this->areAllStepsCompleted() && $this->progress >= $nextStep['percentage']) {
                $this->dispatch('delayed-block-transfer',
                    transactionId: $this->transaction->id,
                    stepId: $nextStep['step']->id,
                    stepTitle: $nextStep['step']->title,
                    delay: 5000  // 5 secondes pour laisser voir la progression complètement
                );
            }

            Log::info(__('transfers.delayed_block_transfer_event_dispatched'));
        } else {
            // Aucune étape à traiter, compléter le transfert
            $this->progress = 100;
            $this->transferStatus = 'completed';
            $this->statusMessage = __('transfers.transfer_submitted_successfully');
            $this->isCompleted = true;
            $this->showStartButton = false;
            $this->showProgressBar = true;

            // Mettre à jour la progression dans la transaction
            $this->updateTransactionProgress(100);

            // Déclencher le rafraîchissement de l'interface
            $this->dispatch('transfer-state-updated');
            $this->dispatch('progress-updated', progress: 100);
        }
    }

    public function processNextStep()
    {
        // Trouver la prochaine étape non complétée
        $nextStepIndex = $this->findNextIncompleteStep();

        // Si aucune étape non complétée trouvée, le transfert est terminé
        if ($nextStepIndex === null) {
            $this->progress = 100;
            $this->transferStatus = 'completed';
            $this->statusMessage = __('transfers.transfer_submitted_successfully');
            $this->isCompleted = true;
            $this->showStartButton = false;
            $this->showProgressBar = true;

            // Mettre à jour la progression dans la transaction
            $this->updateTransactionProgress(100);

            // Déclencher le rafraîchissement de l'interface
            $this->dispatch('transfer-state-updated');
            $this->dispatch('progress-updated', progress: 100);

            return;
        }

        // Mettre à jour l'index de l'étape courante
        $this->currentStepIndex = $nextStepIndex;
        $currentStep = $this->stepsWithPercentages[$nextStepIndex];

        // Vérifier que l'étape est valide
        if (!isset($currentStep['step'])) {
            Log::error(__('transfers.invalid_step_structure'), ['stepData' => $currentStep]);
            return;
        }

        // Mettre à jour le message de statut pour montrer la progression
        $this->statusMessage = __('transfers.transfer_in_progress');

        // Animation de progression vers le pourcentage de l'étape
        $targetPercentage = $currentStep['percentage'] ?? 0;
        $this->progress = $targetPercentage;
        $this->updateTransactionProgress($targetPercentage);

        // Journaliser l'étape atteinte
        Log::info(__('transfers.next_step_found'), [
            'step_id' => $currentStep['step']->id ?? 'inconnu',
            'step_title' => $currentStep['step']->title ?? 'inconnu',
            'percentage' => $targetPercentage,
            'step_index' => $this->currentStepIndex
        ]);

        // Mettre à jour l'étape bloquée dans la transaction avant de bloquer
        if ($this->transaction) {
            $this->transaction->blockAtTransferStep(
                $currentStep['step']->id,
                $currentStep['step']->transfer_step_group_id ?? null,
__('transfers.transfer_blocked') . ': ' . $currentStep['step']->title
            );
        }

        // Déclencher immédiatement la mise à jour de la progression
        $this->dispatch('progress-updated', progress: $this->progress);
        
        // Ne programmer l'affichage de la popup que si ce n'est pas la dernière étape
        if (!$this->areAllStepsCompleted() && $this->progress >= $targetPercentage && $this->showProgressBar === true) {
            $this->dispatch('proceed-to-next-step-with-delay',
                transactionId: $this->transaction->id,
                nextStepId: $currentStep['step']->id,
                nextStepTitle: $currentStep['step']->title,
                delay: 7000  // 7 secondes pour laisser voir la progression complètement
            );
        }
    }

    /**
     * Anime la progression vers une étape spécifique
     */
    public function animateProgressToStep($targetPercentage)
    {
        // Envoyer un événement pour l'animation progressive côté client
        $this->dispatch('animate-progress-to', [
            'startValue' => $this->progress,
            'targetValue' => $targetPercentage,
            'duration' => 2000
        ]);
        
        // Mettre à jour la valeur finale côté serveur (sera mise à jour progressivement côté client)
        $this->progress = $targetPercentage;
        $this->updateTransactionProgress($this->progress);
    }

    public function blockTransfer($transactionId, $stepId, $stepTitle)
    {
        try {
            Log::info(__('transfers.begin_transfer_progression'), [
                'transactionId' => $transactionId,
                'stepId' => $stepId,
                'stepTitle' => $stepTitle,
                'currentTransferStatus' => $this->transferStatus
            ]);

            // Trouver les données de l'étape actuelle
            $currentStepData = null;
            foreach ($this->stepsWithPercentages as $stepData) {
                if (isset($stepData['step']) && $stepData['step']->id == $stepId) {
                    $currentStepData = $stepData;
                    break;
                }
            }

            if (!$currentStepData) {
                throw new \Exception(__('transfers.invalid_step_structure') . ': ' . $stepId);
            }

            // Mettre à jour la transaction dans la base de données avec l'étape bloquée
            if ($this->transaction) {
                $this->transaction->blockAtTransferStep(
                    $stepId,
                    $currentStepData['step']->transfer_step_group_id ?? null,
                    __('transfers.transfer_blocked') . ': ' . $stepTitle
                );

                // Recharger la transaction pour avoir les données à jour
                $this->transaction->refresh();
            }

            // Mettre à jour l'état du composant
            $this->transferStatus = 'blocked';
            $this->isTransferBlocked = true;
            $this->showUnlockButton = true;
            $this->isTransferStarted = false;
            $this->showProgressBar = true;
            $this->unlockTransactionId = $transactionId;
            $this->unlockStepId = $stepId;
            $this->statusMessage = __('transfers.transfer_blocked') . ' - ' . ($currentStepData['step']->title ?? __('transfers.unknown_step'));

            // Préparer les données de l'étape pour la modale
            $this->currentStepData = [
                'step' => $currentStepData['step'],
                'percentage' => $currentStepData['percentage'] ?? 0,
                'is_completed' => false
            ];

            // Afficher la modale seulement si le transfert n'est pas en cours de chargement
            // et que la progression est visible (showProgressBar = true)
            if ($this->transferStatus !== 'starting' && $this->showProgressBar === true) {
                $this->showStepModal = true;
            }

            Log::info(__('transfers.delayed_block_transfer_event_dispatched'), [
                'stepTitle' => $currentStepData['step']->title ?? 'Inconnu',
                'showStepModal' => $this->showStepModal,
                'transferStatus' => $this->transferStatus,
                'modalDisplayed' => $this->transferStatus !== 'starting'
            ]);
        } catch (\Exception $e) {
            Log::error(__('transfers.error_starting_transfer') . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            $this->statusMessage = __('common.error_occurred');
        }
    }

    public function verifyStepCode()
    {
        if (!$this->currentStepData || !$this->stepCode) {
            $this->stepCodeError = __('transfers.unlock_code_required');
            return;
        }

        $step = $this->currentStepData['step'];
        $isCodeValid = $this->stepCode === ($step->code ?? '');

        Log::info(__('transfers.verification_code_sent_successfully'), [
            'stepId' => $step->id,
            'stepTitle' => $step->title,
            'codeProvided' => !empty($this->stepCode),
            'codeValid' => $isCodeValid
        ]);

        if ($isCodeValid) {
            // Marquer l'étape comme complétée
            $this->markStepAsCompleted($step->id);

            // Fermer la modale
            $this->showStepModal = false;
            $this->stepCode = '';
            $this->stepCodeError = '';

            // Mettre à jour le statut du transfert
            $this->transferStatus = 'in_progress';
            $this->isTransferBlocked = false;
            $this->showProgressBar = true;  // S'assurer que la barre reste visible
            $this->isTransferStarted = true;  // Maintenir l'état de transfert démarré

            // Continuer immédiatement la progression après déblocage
            $this->dispatch('proceed-to-next-step');
        } else {
            $this->stepCodeError = __('transfers.invalid_unlock_code');
        }
    }

    protected function markStepAsCompleted($stepId)
    {
        // Marquer l'étape comme complétée dans la base de données
        if ($this->transaction) {
            try {
                \App\Models\TransferStepCompletion::updateOrCreate(
                    [
                        'transaction_id' => $this->transaction->id,
                        'transfer_step_id' => $stepId
                    ],
                    [
                        'entered_code' => $this->stepCode,
                        'completed_at' => now()
                    ]
                );

                // Mettre à jour le statut dans stepsWithPercentages
                foreach ($this->stepsWithPercentages as &$stepData) {
                    if ($stepData['step']->id === $stepId) {
                        $stepData['is_completed'] = true;
                        break;
                    }
                }

                // Recalculer et mettre à jour l'état du transfert
                $this->progress = $this->calculateCurrentProgress();
                $this->determineTransferState();

                // Vérifier si toutes les étapes sont complétées et mettre à jour le statut si nécessaire
                $statusUpdated = $this->transaction->updateStatusIfAllStepsCompleted();

                Log::info(__('transfers.step_reached'), [
                    'transactionId' => $this->transaction->id,
                    'stepId' => $stepId,
                    'newProgress' => $this->progress,
                    'statusUpdated' => $statusUpdated
                ]);
            } catch (\Exception $e) {
                Log::error(__('transfers.error_updating_step'), [
                    'error' => $e->getMessage(),
                    'transactionId' => $this->transaction->id ?? null,
                    'stepId' => $stepId ?? null
                ]);
            }
        }
    }

    public function closeStepModal()
    {
        $this->showStepModal = false;
        $this->stepCode = '';
        $this->stepCodeError = '';
        $this->unlockCode = '';
        $this->unlockError = '';
        $this->isVerifying = false;
    }

    public function verifyUnlockCode()
    {
        $this->isVerifying = true;
        $this->unlockError = '';

        try {
            // Validation des données requises
            if (!$this->currentStepData || !isset($this->currentStepData['step'])) {
                $this->unlockError = __('transfers.verification_error');
                $this->isVerifying = false;
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => __('transfers.verification_error'),
                    'dismissible' => true
                ]);
                return;
            }

            if (!$this->unlockCode || trim($this->unlockCode) === '') {
                $this->unlockError = __('transfers.unlock_code_required');
                $this->isVerifying = false;
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => __('transfers.unlock_code_required'),
                    'dismissible' => true
                ]);
                return;
            }

            $step = $this->currentStepData['step'];

            // Vérification que l'étape a un code défini
            if (!isset($step->code) || $step->code === null || $step->code === '') {
                $this->unlockError = __('transfers.verification_error');
                $this->isVerifying = false;
                $this->dispatch('error-updated');
                return;
            }

            // Comparaison des codes (sensible à la casse)
            $isCodeValid = trim($this->unlockCode) === trim($step->code);

            if ($isCodeValid) {
                // Marquer l'étape comme complétée
                $this->markStepAsCompleted($step->id);

                // Fermer la modale
                $this->showStepModal = false;
                $this->unlockCode = '';
                $this->unlockError = '';
                $this->isVerifying = false;

                // Dispatcher l'événement pour que JavaScript gère la continuation
                $this->dispatch('close-step-modal');
            } else {
                // Code incorrect
                $this->unlockError = __('transfers.invalid_unlock_code');
                $this->isVerifying = false;

                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => __('transfers.invalid_unlock_code'),
                    'dismissible' => true
                ]);

                // Log pour debug (optionnel)
                Log::info(__('transfers.invalid_unlock_code_log'), [
                    'expected' => $step->code,
                    'provided' => $this->unlockCode,
                    'step_id' => $step->id
                ]);
            }
        } catch (\Exception $e) {
            $this->unlockError = __('transfers.verification_error');
            $this->isVerifying = false;
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => __('transfers.verification_error'),
                'dismissible' => true
            ]);
            Log::error(__('transfers.error_verifying_code') . ': ' . $e->getMessage(), [
                'step_data' => $this->currentStepData,
                'unlock_code' => $this->unlockCode
            ]);
        }
    }

    public function updateTransactionProgress($percentage)
    {
        if ($this->transaction) {
            try {
                $this->transaction->update([
                    'progress_percentage' => $percentage
                ]);
            } catch (\Exception $e) {
                Log::error(__('transfers.error_updating_progress_percentage') . ': ' . $e->getMessage());
            }
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

    /**
     * Propriété calculée pour les étapes de transfert du compte
     */
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
                'group_name' => $step->transferStepGroup->name ?? __('transfers.default_group'),
                'is_completed' => $isCompleted,
                'is_current' => $isCurrent,
                'percentage' => $percentage,
                'completed_at' => $completedAt,
                'entered_code' => null
            ];
        }

        return $steps;
    }

    /**
     * Propriété calculée pour la progression globale
     */
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

    /**
     * Trouver la prochaine étape non complétée dans l'ordre
     */
    protected function findNextIncompleteStep()
    {
        foreach ($this->stepsWithPercentages as $index => $step) {
            if (!$step['is_completed']) {
                return $index;
            }
        }
        return null;  // Toutes les étapes sont complétées
    }

    /**
     * Vérifie si au moins une étape est complétée
     */
    private function hasCompletedSteps()
    {
        foreach ($this->stepsWithPercentages as $step) {
            if ($step['is_completed']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifie si toutes les étapes sont complétées
     */
    private function areAllStepsCompleted()
    {
        if (empty($this->stepsWithPercentages)) {
            return false;
        }

        foreach ($this->stepsWithPercentages as $step) {
            if (!$step['is_completed']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Calcule le progrès actuel basé sur les étapes complétées
     */
    private function calculateCurrentProgress()
    {
        if (empty($this->stepsWithPercentages)) {
            return 0;
        }

        // Si toutes les étapes sont complétées, retourner 100%
        if ($this->areAllStepsCompleted()) {
            return 100;
        }

        // Vérifier s'il y a des étapes complétées
        $hasCompletedSteps = $this->hasCompletedSteps();

        // Si aucune étape n'est complétée, retourner 0 (nouveau transfert)
        if (!$hasCompletedSteps) {
            return 0;
        }

        // Pour un transfert resumable, retourner le pourcentage de la dernière étape complétée
        $lastCompletedPercentage = 0;
        foreach ($this->stepsWithPercentages as $step) {
            if ($step['is_completed']) {
                $lastCompletedPercentage = $step['percentage'];
            } else {
                break;
            }
        }

        return $lastCompletedPercentage;
    }

    /**
     * Bloquer le transfert à la prochaine étape non complétée
     */
    public function blockAtFirstStep($stepId = null, $stepTitle = null)
    {
        try {
            Log::info(__('transfers.begin_block_at_first_step'), [
                'stepId' => $stepId,
                'stepTitle' => $stepTitle,
                'currentTransferStatus' => $this->transferStatus
            ]);

            // Si aucun stepId fourni, trouver la prochaine étape non complétée
            if (!$stepId) {
                // S'assurer que les étapes sont préparées
                if (empty($this->stepsWithPercentages)) {
                    $this->prepareStepsWithPercentages();
                }

                // Vérifier qu'il y a des étapes à traiter
                if (empty($this->stepsWithPercentages)) {
                    Log::warning(__('transfers.no_transfer_steps_found'), [
                        'transferData' => $this->transferData,
                        'transaction_id' => $this->transaction ? $this->transaction->id : null
                    ]);
                    throw new \Exception(__('transfers.no_transfer_steps_configured'));
                }

                $nextStepIndex = $this->findNextIncompleteStep();
                if ($nextStepIndex !== null && isset($this->stepsWithPercentages[$nextStepIndex])) {
                    $nextStep = $this->stepsWithPercentages[$nextStepIndex];
                    $stepId = $nextStep['step']->id;
                    $stepTitle = $nextStep['step']->title;
                }
            }

            if (!$stepId) {
                Log::warning(__('transfers.no_step_to_block_found'));
                return;
            }

            // Trouver l'étape dans stepsWithPercentages
            $currentStepData = null;
            foreach ($this->stepsWithPercentages as $stepData) {
                if ($stepData['step']->id == $stepId) {
                    $currentStepData = $stepData;
                    break;
                }
            }

            if (!$currentStepData) {
                throw new \Exception(__('transfers.step_not_found_for_blocking') . ': ' . $stepId);
            }

            // Mettre à jour l'état du composant
            $this->transferStatus = 'blocked';
            $this->statusMessage = __('transfers.transfer_blocked_for_verification', [
                'step' => $stepTitle ?? $currentStepData['step']->title
            ]);

            // Préparer les données de l'étape pour la modale
            $this->currentStepData = $currentStepData;
            
            // Afficher la modale seulement si le transfert n'est pas en cours de chargement
            // et que la progression est visible (showProgressBar = true)
            if ($this->transferStatus !== 'starting' && $this->showProgressBar === true) {
                $this->showStepModal = true;
            }

            Log::info(__('transfers.transfer_blocked_successfully'), [
                'stepId' => $stepId,
                'stepTitle' => $stepTitle,
                'modalDisplayed' => $this->transferStatus !== 'starting'
            ]);
        } catch (\Exception $e) {
            Log::error(__('transfers.error_blocking_transfer') . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);
            $this->statusMessage = __('transfers.error_blocking_transfer');
        }
    }

    /**
     * Rouvrir la modale de déblocage pour un transfert bloqué
     */
    public function reopenStepModal()
    {
        try {
            // Vérifier que le transfert est bloqué
            if ($this->transferStatus !== 'blocked') {
                Log::warning('Tentative de réouverture de la modale sur un transfert non bloqué', [
                    'transferStatus' => $this->transferStatus,
                    'transaction_id' => $this->transaction ? $this->transaction->id : null
                ]);
                return;
            }

            // S'assurer que les données de l'étape courante sont disponibles
            if (!$this->currentStepData) {
                // Essayer de récupérer les données de l'étape courante
                if (empty($this->stepsWithPercentages)) {
                    $this->prepareStepsWithPercentages();
                }

                $nextStepIndex = $this->findNextIncompleteStep();
                if ($nextStepIndex !== null && isset($this->stepsWithPercentages[$nextStepIndex])) {
                    $this->currentStepData = $this->stepsWithPercentages[$nextStepIndex];
                }
            }

            // Réinitialiser les erreurs et le code
            $this->unlockError = '';
            $this->unlockCode = '';
            $this->isVerifying = false;

            // Afficher la modale
            $this->showStepModal = true;

            Log::info('Modale de déblocage rouverte avec succès', [
                'transaction_id' => $this->transaction ? $this->transaction->id : null,
                'currentStepData' => $this->currentStepData ? $this->currentStepData['step']->title : 'Non disponible'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la réouverture de la modale de déblocage: ' . $e->getMessage(), [
                'exception' => $e,
                'transaction_id' => $this->transaction ? $this->transaction->id : null
            ]);
        }
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-progress');
    }
}
