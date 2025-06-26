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
        Log::info('TransferProgress mount() appelée', ['transferId' => $transferId]);
        $this->transferId = $transferId;

        // Charger la transaction depuis la base de données
        $this->transaction = Transaction::find($transferId);
        Log::info('Transaction chargée', ['transaction' => $this->transaction]);

        // Charger les données de transfert
        $this->loadTransferData();
        Log::info('Après loadTransferData', ['transferData_empty' => empty($this->transferData)]);

        // Charger les étapes de transfert
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

                Log::info('Données de transfert chargées depuis la base de données', [
                    'transaction_id' => $this->transaction->id,
                    'source_type' => $this->transferData['source_type']
                ]);

                Log::info('Données de transfert chargées depuis la base de données', [
                    'transferData' => $this->transferData,
                ]);
            } else {
                Log::warning('Transaction non trouvée ou non autorisée', ['transfer_id' => $this->transferId]);
            }
        } else {
            // Récupérer les données du transfert depuis la session (mode création)
            $this->transferData = session('transfer_data', []);

            if (!empty($this->transferData)) {
                Log::info('Données de transfert chargées depuis la session');

                // Debug: Afficher les données récupérées depuis la session

                /*
                 * dd('Données depuis session:', [
                 *     'transferData' => $this->transferData,
                 *     'session_data' => session()->all()
                 * ]);
                 */
            } else {
                dd('Aucune donnée trouvée:', [
                    'transferId' => $this->transferId,
                    'session_has_transfer_data' => session()->has('transfer_data'),
                    'all_session' => session()->all()
                ]);
            }
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

            $allSteps = collect();
            foreach ($transferStepGroups as $group) {
                $steps = $group->transferSteps()->orderBy('order')->get();
                $allSteps = $allSteps->merge($steps);
            }

            $this->transferSteps = $allSteps->sortBy('order');
            $this->maxSteps = $this->transferSteps->count();  // Initialiser maxSteps ici

            Log::info('Étapes finales chargées', ['steps' => $this->transferSteps->toArray()]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des étapes de transfert: ' . $e->getMessage());
        }
    }

    private function setDefaultSteps()
    {
        // Pas d'étapes par défaut hardcodées
        $this->steps = [];
        $this->maxSteps = 0;
    }

    public function startTransferProcess()
    {
        // Vérifier que les données de transfert sont disponibles
        if (empty($this->transferData)) {
            $this->statusMessage = __('common.error_occurred');
            return;
        }

        // Créer la transaction si elle n'existe pas
        if (!$this->transaction) {
            try {
                $this->transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'type' => 'TRANSFER_BANK',
                    'amount' => $this->transferData['transfer_amount'],
                    'currency' => $this->transferData['transfer_currency'],
                    'status' => Transaction::STATUS_PENDING,
                    'description' => $this->transferData['transfer_reason'] ?? __('transfers.transfer'),
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
                    ] : null,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la création de la transaction: ' . $e->getMessage());
                $this->statusMessage = __('common.error_occurred');
                return;
            }
        }

        // Préparer les étapes avec pourcentages
        $this->prepareStepsWithPercentages();

        // Changer l'état du transfert
        $this->transferStatus = 'starting';
        $this->showStartButton = false;
        $this->showProgressBar = true;
        $this->isTransferStarted = true;
        $this->progress = 0;
        $this->currentStepIndex = 0;
        $this->statusMessage = __('transfers.transfer_starting');

        // Programmer le démarrage de la progression après un court délai
        $this->dispatch('start-transfer-progression',
            delay: 1500  // 1.5 secondes
        );
    }

    private function prepareStepsWithPercentages()
    {
        Log::info('Début de prepareStepsWithPercentages', [
            'transferData' => $this->transferData,
            'transaction_id' => $this->transaction ? $this->transaction->id : null
        ]);

        // Récupérer les étapes de transfert
        $sourceType = $this->transferData['source_type'] ?? null;
        $sourceId = $this->transferData['selected_source_id'] ?? null;

        Log::info('Données source', [
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

        Log::info('Étapes récupérées', [
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

        Log::info('Fin de prepareStepsWithPercentages', [
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
        // Cette méthode sera appelée après le délai pour commencer la vraie progression
        $this->transferStatus = 'in_progress';
        $this->statusMessage = __('transfers.transfer_in_progress');

        // S'assurer que les étapes sont préparées
        if (empty($this->stepsWithPercentages)) {
            $this->prepareStepsWithPercentages();
        }

        // Vérifier qu'il y a des étapes à traiter
        if (empty($this->stepsWithPercentages)) {
            Log::warning('Aucune étape de transfert trouvée', [
                'transferData' => $this->transferData,
                'transaction_id' => $this->transaction ? $this->transaction->id : null
            ]);
            $this->statusMessage = __('transfers.no_steps_found');
            return;
        }

        // Trouver la prochaine étape non complétée
        $nextStepIndex = $this->findNextIncompleteStep();

        $this->currentStepIndex = $nextStepIndex;

        // S'il y a des étapes à traiter
        if ($nextStepIndex !== null && isset($this->stepsWithPercentages[$nextStepIndex])) {
            $nextStep = $this->stepsWithPercentages[$nextStepIndex];

            // Vérifier que la structure de l'étape est correcte
            if (!isset($nextStep['step']) || !$nextStep['step']) {
                Log::error("Structure d'étape invalide", [
                    'nextStepIndex' => $nextStepIndex,
                    'nextStep' => $nextStep,
                    'stepsWithPercentages' => $this->stepsWithPercentages
                ]);
                $this->statusMessage = __('transfers.step_structure_error');
                return;
            }

            // Animer la progression vers l'étape suivante
            $this->animateProgressToStep($nextStep['percentage']);

            // Log des données avant dispatch
            Log::info('Dispatch delayed-block-transfer', [
                'transactionId' => $this->transaction->id,
                'stepId' => $nextStep['step']->id,
                'stepTitle' => $nextStep['step']->title,
                'nextStep_structure' => $nextStep
            ]);

            // Programmer le blocage après l'animation
            $this->dispatch('delayed-block-transfer',
                transactionId: $this->transaction->id,
                stepId: $nextStep['step']->id,
                stepTitle: $nextStep['step']->title,
                delay: 2000  // 2 secondes après l'animation
            );
        } else {
            // Aucune étape à traiter, compléter le transfert
            $this->progress = 100;
            $this->statusMessage = __('transfers.transfer_completed_successfully');
            $this->isCompleted = true;
        }
    }

    public function processNextStep()
    {
        // Trouver la prochaine étape non complétée
        $nextStepIndex = $this->findNextIncompleteStep();

        // Si aucune étape non complétée trouvée, le transfert est terminé
        if ($nextStepIndex === null) {
            $this->progress = 100;
            $this->statusMessage = __('transfers.transfer_completed_successfully');
            $this->isCompleted = true;
            return;
        }

        // Mettre à jour l'index de l'étape courante
        $this->currentStepIndex = $nextStepIndex;
        $currentStep = $this->stepsWithPercentages[$nextStepIndex];

        // Vérifier que l'étape est valide
        if (!isset($currentStep['step'])) {
            Log::error('Étape invalide', ['stepData' => $currentStep]);
            return;
        }

        // Mettre à jour le message de statut pour montrer la progression
        $this->statusMessage = __('transfers.processing_step', [
            'title' => $currentStep['step']->title ?? 'Étape inconnue'
        ]);

        // Animation de progression vers le pourcentage de l'étape
        $targetPercentage = $currentStep['percentage'] ?? 0;
        $this->progress = $targetPercentage;
        $this->updateTransactionProgress($targetPercentage);

        // Journaliser l'étape atteinte
        Log::info('Étape atteinte', [
            'step_id' => $currentStep['step']->id ?? 'inconnu',
            'step_title' => $currentStep['step']->title ?? 'inconnu',
            'percentage' => $targetPercentage,
            'step_index' => $this->currentStepIndex
        ]);

        // Bloquer le transfert et afficher la modale après un court délai
        $this->dispatch('delayed-block-transfer',
            transactionId: $this->transaction->id,
            stepId: $currentStep['step']->id,
            stepTitle: $currentStep['step']->title,
            delay: 2000  // 2 secondes avant de bloquer
        );
    }

    /**
     * Anime la progression vers une étape spécifique
     */
    public function animateProgressToStep($targetPercentage)
    {
        $this->progress = $targetPercentage;
        $this->updateTransactionProgress($this->progress);
        $this->dispatch('progress-updated', progress: $this->progress);
    }

    public function blockTransfer($transactionId, $stepId, $stepTitle)
    {
        try {
            Log::info('Début de blockTransfer', [
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
                throw new \Exception('Étape non trouvée pour le blocage: ' . $stepId);
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

            // Afficher la modale
            $this->showStepModal = true;

            Log::info('Transfert bloqué avec succès', [
                'stepTitle' => $currentStepData['step']->title ?? 'Inconnu',
                'showStepModal' => $this->showStepModal,
                'transferStatus' => $this->transferStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du blocage du transfert: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            $this->statusMessage = __('common.error_occurred');
        }
    }

    public function verifyStepCode()
    {
        Log::info('Vérification du code de déblocage', [
            'hasStepData' => !empty($this->currentStepData),
            'hasStepCode' => !empty($this->stepCode),
            'currentStepIndex' => $this->currentStepIndex,
            'transferStatus' => $this->transferStatus
        ]);

        if (!$this->currentStepData || !$this->stepCode) {
            $this->stepCodeError = __('transfers.unlock_code_required');
            return;
        }

        $step = $this->currentStepData['step'];
        $isCodeValid = $this->stepCode === ($step->code ?? '');

        Log::info('Validation du code', [
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

            // Passer à l'étape suivante
            $this->processNextStep();
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

                Log::info('Étape marquée comme complétée', [
                    'transactionId' => $this->transaction->id,
                    'stepId' => $stepId
                ]);
            } catch (\Exception $e) {
                Log::error("Erreur lors de la mise à jour de l'étape dans la base de données", [
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
            if (!$this->currentStepData || !$this->unlockCode) {
                $this->unlockError = __('transfers.unlock_code_required');
                return;
            }

            $step = $this->currentStepData['step'];
            $isCodeValid = $this->unlockCode === ($step->code ?? '');

            if ($isCodeValid) {
                // Marquer l'étape comme complétée
                $this->markStepAsCompleted($step->id);

                // Fermer la modale
                $this->showStepModal = false;
                $this->unlockCode = '';
                $this->unlockError = '';

                // Passer à l'étape suivante
                $this->processNextStep();
            } else {
                $this->unlockError = __('transfers.invalid_unlock_code');
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

    public function getAccountTransferStepsProperty()
    {
        Log::info('getAccountTransferStepsProperty appelée', [
            'transferSteps_empty' => empty($this->transferSteps),
            'transferSteps_count' => $this->transferSteps ? $this->transferSteps->count() : 0
        ]);

        if (empty($this->transferSteps)) {
            Log::warning('transferSteps est vide dans getAccountTransferStepsProperty');
            return [];
        }

        $steps = [];
        foreach ($this->transferSteps as $index => $step) {
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

        Log::info('getAccountTransferStepsProperty retourne', [
            'steps_count' => count($steps),
            'steps_data' => $steps
        ]);

        return $steps;
    }

    public function getOverallProgressProperty()
    {
        if (empty($this->accountTransferSteps)) {
            return 0;
        }

        $completedSteps = collect($this->accountTransferSteps)->where('is_completed', true)->count();
        $totalSteps = count($this->accountTransferSteps);

        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    }

    /**
     * Déterminer si le bouton de démarrage doit être affiché
     */
    public function getShowStartButtonProperty()
    {
        foreach ($this->stepsWithPercentages as $index => $step) {
            if (!$step['is_completed']) {
                return $index;
            }
        }
        return null;  // Toutes les étapes sont complétées
    }

    /**
     * Bloquer le transfert à la prochaine étape non complétée
     */
    public function blockAtFirstStep($stepId = null, $stepTitle = null)
    {
        try {
            Log::info('Début de blockAtFirstStep', [
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
                    Log::warning('Aucune étape de transfert trouvée dans blockAtFirstStep', [
                        'transferData' => $this->transferData,
                        'transaction_id' => $this->transaction ? $this->transaction->id : null
                    ]);
                    throw new \Exception('Aucune étape de transfert configurée');
                }

                $nextStepIndex = $this->findNextIncompleteStep();
                if ($nextStepIndex !== null && isset($this->stepsWithPercentages[$nextStepIndex])) {
                    $nextStep = $this->stepsWithPercentages[$nextStepIndex];
                    $stepId = $nextStep['step']->id;
                    $stepTitle = $nextStep['step']->title;
                }
            }

            if (!$stepId) {
                Log::warning('Aucune étape à bloquer trouvée');
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
                throw new \Exception('Étape non trouvée pour le blocage: ' . $stepId);
            }

            // Mettre à jour l'état du composant
            $this->transferStatus = 'blocked';
            $this->statusMessage = __('transfers.transfer_blocked_for_verification', [
                'step' => $stepTitle ?? $currentStepData['step']->title
            ]);

            // Préparer les données de l'étape pour la modale
            $this->currentStepData = $currentStepData;
            $this->showStepModal = true;

            Log::info('Transfert bloqué avec succès', [
                'stepId' => $stepId,
                'stepTitle' => $stepTitle
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du blocage du transfert: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            $this->statusMessage = __('transfers.error_blocking_transfer');
        }
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-progress');
    }
}
