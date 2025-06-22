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
        // Charger la transaction
        $this->transaction = Transaction::findOrFail($transactionId);
        
        // Vérifier que l'utilisateur a accès à cette transaction
        if ($this->transaction->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette transaction');
        }

        // Si un stepId est fourni, charger cette étape spécifique
        if ($stepId) {
            $this->currentStep = TransferStep::findOrFail($stepId);
        } else {
            // Sinon, trouver l'étape actuelle bloquée
            $this->findCurrentBlockedStep();
        }

        // Récupérer le pourcentage de progression actuel
        $this->progressPercentage = $this->transaction->progress_percentage ?? 0;

        Log::info('UnlockProgress mounted', [
            'transaction_id' => $this->transaction->id,
            'current_step_id' => $this->currentStep?->id,
            'progress_percentage' => $this->progressPercentage
        ]);
    }

    private function findCurrentBlockedStep()
    {
        // Récupérer les étapes associées à cette transaction
        $sourceType = $this->transaction->source_type;
        $sourceId = $this->transaction->source_id;

        if ($sourceType === 'account') {
            $source = \App\Models\Account::find($sourceId);
            $transferSteps = $source->transferStepGroups()->with('transferSteps')->first()?->transferSteps ?? collect();
        } else {
            $source = \App\Models\Wallet::find($sourceId);
            $transferSteps = $source->transferStepGroups()->with('transferSteps')->first()?->transferSteps ?? collect();
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

            // Vérifier si le code est correct (ici on accepte n'importe quel code non vide)
            // Dans un vrai système, vous devriez valider contre un code généré ou attendu
            if (strlen($this->enteredCode) < 4) {
                $this->codeError = 'Le code doit contenir au moins 4 caractères';
                return;
            }

            // Marquer l'étape comme complétée
            TransferStepCompletion::updateOrCreate(
                [
                    'transaction_id' => $this->transaction->id,
                    'transfer_step_id' => $this->currentStep->id,
                ],
                [
                    'entered_code' => $this->enteredCode,
                    'completed_at' => now(),
                ]
            );

            Log::info('Step unlocked successfully', [
                'transaction_id' => $this->transaction->id,
                'step_id' => $this->currentStep->id,
                'step_title' => $this->currentStep->title
            ]);

            // Rediriger vers la page de progression du transfert
            return redirect()->route('transfer.progress', [
                'locale' => app()->getLocale(),
                'transferId' => $this->transaction->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error unlocking step', [
                'transaction_id' => $this->transaction->id,
                'step_id' => $this->currentStep->id,
                'error' => $e->getMessage()
            ]);
            
            $this->codeError = 'Une erreur est survenue. Veuillez réessayer.';
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.deposit-management.unlock-progress')
            ->layout('layouts.app');
    }
}