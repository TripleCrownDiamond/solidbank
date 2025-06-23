<?php

namespace App\Livewire\DepositManagement;

use App\Models\Transaction;
use App\Models\TransferStep;
use App\Models\TransferStepCompletion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UnlockProgress extends Component
{
    public $transaction;
    public $currentStep;
    public $enteredCode = '';
    public $codeError = '';
    public $isProcessing = false;
    public $progressPercentage = 0;

    public function mount($transactionId, $stepId = null)
    {
        try {
            // Charger la transaction
            $this->transaction = Transaction::findOrFail($transactionId);

            // Vérifier que l'utilisateur a accès à cette transaction
            if ($this->transaction->user_id !== Auth::id()) {
                abort(403, 'Accès non autorisé à cette transaction');
            }

            // Si un stepId est fourni, charger cette étape spécifique
            if ($stepId) {
                $this->currentStep = TransferStep::findOrFail($stepId);

                // Vérifier que cette étape appartient bien à cette transaction
                if (!$this->isStepBelongsToTransaction($this->currentStep)) {
                    abort(404, 'Étape non trouvée pour cette transaction');
                }
            } else {
                // Sinon, trouver l'étape actuelle bloquée
                $this->findCurrentBlockedStep();
            }

            // Vérifier qu'une étape a été trouvée
            if (!$this->currentStep) {
                abort(404, 'Aucune étape en cours trouvée pour cette transaction');
            }

            // Récupérer le pourcentage de progression actuel
            $this->progressPercentage = $this->transaction->progress_percentage ?? 0;

            Log::info('UnlockProgress mounted', [
                'transaction_id' => $this->transaction->id,
                'current_step_id' => $this->currentStep->id,
                'progress_percentage' => $this->progressPercentage
            ]);
        } catch (\Exception $e) {
            Log::error('Error mounting UnlockProgress', [
                'transaction_id' => $transactionId,
                'step_id' => $stepId,
                'error' => $e->getMessage()
            ]);

            abort(500, 'Erreur lors du chargement de la progression');
        }
    }

    private function isStepBelongsToTransaction($step)
    {
        $sourceType = $this->transaction->source_type;
        $sourceId = $this->transaction->source_id;

        try {
            if ($sourceType === 'account') {
                $source = \App\Models\Account::find($sourceId);
            } else {
                $source = \App\Models\Wallet::find($sourceId);
            }

            if (!$source) {
                return false;
            }

            $transferSteps = $source
                ->transferStepGroups()
                ->with('transferSteps')
                ->first()
                ?->transferSteps ?? collect();

            return $transferSteps->contains('id', $step->id);
        } catch (\Exception $e) {
            Log::error('Error checking step ownership', [
                'transaction_id' => $this->transaction->id,
                'step_id' => $step->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function findCurrentBlockedStep()
    {
        try {
            // Récupérer les étapes associées à cette transaction
            $sourceType = $this->transaction->source_type;
            $sourceId = $this->transaction->source_id;

            if ($sourceType === 'account') {
                $source = \App\Models\Account::find($sourceId);
            } else {
                $source = \App\Models\Wallet::find($sourceId);
            }

            if (!$source) {
                Log::warning('Source not found', [
                    'transaction_id' => $this->transaction->id,
                    'source_type' => $sourceType,
                    'source_id' => $sourceId
                ]);
                return;
            }

            $transferSteps = $source
                ->transferStepGroups()
                ->with('transferSteps')
                ->first()
                ?->transferSteps ?? collect();

            if ($transferSteps->isEmpty()) {
                Log::warning('No transfer steps found', [
                    'transaction_id' => $this->transaction->id,
                    'source_type' => $sourceType,
                    'source_id' => $sourceId
                ]);
                return;
            }

            // Trouver la première étape non complétée
            foreach ($transferSteps->sortBy('order') as $step) {
                $completion = TransferStepCompletion::where('transaction_id', $this->transaction->id)
                    ->where('transfer_step_id', $step->id)
                    ->first();

                if (!$completion) {
                    $this->currentStep = $step;
                    break;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error finding current blocked step', [
                'transaction_id' => $this->transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function submitCode()
    {
        $this->isProcessing = true;
        $this->codeError = '';

        try {
            // Valider le code entré
            if (empty($this->enteredCode)) {
                $this->codeError = 'Veuillez entrer un code';
                return;
            }

            // Nettoyer le code entré
            $this->enteredCode = trim($this->enteredCode);

            // Vérifier si le code est correct
            if (strlen($this->enteredCode) < 4) {
                $this->codeError = 'Le code doit contenir au moins 4 caractères';
                return;
            }

            // Vérifier que l'étape actuelle existe toujours
            if (!$this->currentStep) {
                $this->codeError = 'Aucune étape en cours trouvée';
                return;
            }

            // Vérifier que l'étape n'est pas déjà complétée
            $existingCompletion = TransferStepCompletion::where('transaction_id', $this->transaction->id)
                ->where('transfer_step_id', $this->currentStep->id)
                ->first();

            if ($existingCompletion) {
                $this->codeError = 'Cette étape a déjà été complétée';
                return;
            }

            // Marquer l'étape comme complétée
            TransferStepCompletion::create([
                'transaction_id' => $this->transaction->id,
                'transfer_step_id' => $this->currentStep->id,
                'entered_code' => $this->enteredCode,
                'completed_at' => now(),
            ]);

            Log::info('Step unlocked successfully', [
                'transaction_id' => $this->transaction->id,
                'step_id' => $this->currentStep->id,
                'step_title' => $this->currentStep->title ?? 'N/A'
            ]);

            // Mettre à jour le pourcentage de progression
            $this->updateTransactionProgress();

            // Rediriger vers la page de progression du transfert
            return redirect()->route('transfer.progress', [
                'locale' => app()->getLocale(),
                'transferId' => $this->transaction->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error unlocking step', [
                'transaction_id' => $this->transaction->id,
                'step_id' => $this->currentStep?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->codeError = 'Une erreur est survenue. Veuillez réessayer.';
        } finally {
            $this->isProcessing = false;
        }
    }

    private function updateTransactionProgress()
    {
        try {
            // Récupérer toutes les étapes pour cette transaction
            $sourceType = $this->transaction->source_type;
            $sourceId = $this->transaction->source_id;

            if ($sourceType === 'account') {
                $source = \App\Models\Account::find($sourceId);
            } else {
                $source = \App\Models\Wallet::find($sourceId);
            }

            if (!$source) {
                return;
            }

            $transferSteps = $source
                ->transferStepGroups()
                ->with('transferSteps')
                ->first()
                ?->transferSteps ?? collect();

            if ($transferSteps->isEmpty()) {
                return;
            }

            // Compter les étapes complétées
            $completedSteps = TransferStepCompletion::where('transaction_id', $this->transaction->id)
                ->whereIn('transfer_step_id', $transferSteps->pluck('id'))
                ->count();

            $totalSteps = $transferSteps->count();
            $progressPercentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;

            // Mettre à jour la transaction
            $this->transaction->update([
                'progress_percentage' => $progressPercentage
            ]);

            $this->progressPercentage = $progressPercentage;
        } catch (\Exception $e) {
            Log::error('Error updating transaction progress', [
                'transaction_id' => $this->transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function resetCode()
    {
        $this->enteredCode = '';
        $this->codeError = '';
    }

    public function render()
    {
        return view('livewire.deposit-management.unlock-progress')
            ->layout('layouts.app');
    }
}
