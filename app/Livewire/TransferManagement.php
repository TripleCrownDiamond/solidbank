<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Account;
use App\Models\Wallet;
use App\Models\TransferStepGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransferManagement extends Component
{
    public $transactionData = [];

    protected $listeners = ['show-transfer-progress' => 'handleTransferProgress'];

    public function handleTransferProgress(array $transactionData)
    {
        try {
            $this->transactionData = $transactionData;
            
            // Vérifier le groupe d'étapes de transfert
            $sourceId = $this->transactionData['selectedSourceId'] ?? $this->transactionData['target_id'] ?? null;
            $sourceType = $this->transactionData['sourceType'] ?? $this->getSourceTypeFromTargetType() ?? null;

            if (!$sourceId || !$sourceType) {
                throw new \Exception('Source ID ou type manquant');
            }

            $transferStepGroup = null;
            $transferSteps = [];

            if ($sourceType === 'account' && $sourceId) {
                $account = Account::find($sourceId);
                if ($account) {
                    $transferStepGroup = $account->transferStepGroups()
                        ->where('is_active', true)
                        ->with('transferSteps')
                        ->first();
                }
            } elseif ($sourceType === 'wallet' && $sourceId) {
                $wallet = Wallet::find($sourceId);
                if ($wallet) {
                    $transferStepGroup = $wallet->transferStepGroups()
                        ->where('is_active', true)
                        ->with('transferSteps')
                        ->first();
                }
            }

            if ($transferStepGroup) {
                $transferSteps = $transferStepGroup->transferSteps->sortBy('order')->values()->toArray();
            }

            // Préparer les données pour la session
            $sessionData = [
                'transfer_amount' => $this->transactionData['transferAmount'] ?? $this->transactionData['amount'] ?? 0,
                'transfer_currency' => $this->transactionData['transferCurrency'] ?? $this->transactionData['currency'] ?? 'EUR',
                'transfer_reason' => $this->transactionData['transferReason'] ?? $this->transactionData['reason'] ?? __('transfers.transfer'),
                'source_type' => $sourceType,
                'selected_source_id' => $sourceId,
                'destination_type' => $this->transactionData['destinationType'] ?? null,
                'selected_destination_id' => $this->transactionData['selectedDestinationId'] ?? null,
                'transfer_steps' => $transferSteps,
                'has_custom_steps' => !empty($transferSteps),
                'created_at' => now()->toDateTimeString()
            ];

            // Stocker les données en session
            session(['transfer_data' => $sessionData]);
            
            // S'assurer que la session est sauvegardée
            session()->save();
            
            Log::info('Données de transfert enregistrées en session', [
                'user_id' => Auth::id(),
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'transfer_amount' => $sessionData['transfer_amount'],
                'transfer_currency' => $sessionData['transfer_currency']
            ]);
            
            // Rediriger vers la page de progression
            return redirect()->route('dashboard.transfer-progress', ['locale' => app()->getLocale()]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement des données de transfert', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            // Rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la préparation du transfert. Veuillez réessayer.');
        }
    }

    private function getSourceTypeFromTargetType()
    {
        if (isset($this->transactionData['target_type'])) {
            if ($this->transactionData['target_type'] === 'App\\Models\\Account') {
                return 'account';
            } elseif ($this->transactionData['target_type'] === 'App\\Models\\Wallet') {
                return 'wallet';
            }
        }
        return null;
    }

    public function render()
    {
        return view('livewire.transfer-management');
    }
}