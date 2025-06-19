<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class DepositManagement extends Component
{
    /**
     * Generate a unique transaction reference
     */
    protected function generateTransactionReference($type)
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $uniqueId = strtoupper(uniqid());
        return "{$prefix}-{$uniqueId}";
    }

    #[On('confirm-transaction')]
    public function handleTransactionConfirmation($recapData)
    {
        try {
            // Find the target (account or wallet)
            $target = null;
            if ($recapData['target_type'] === 'App\\Models\\Account') {
                $target = Account::find($recapData['target_id']);
            } elseif ($recapData['target_type'] === 'App\\Models\\Wallet') {
                $target = Wallet::find($recapData['target_id']);
            }

            if (!$target) {
                throw new \Exception(__('common.target_not_found'));
            }

            // Generate a unique reference for the transaction
            $reference = $this->generateTransactionReference($recapData['type']);

            // Create a new pending transaction
            $transaction = new Transaction([
                'user_id' => $target->user_id,
                'type' => strtoupper($recapData['type']),
                'amount' => $recapData['amount'],
                'currency' => $recapData['currency'],
                'description' => $recapData['reason'] ?? (strtolower($recapData['type']) === 'deposit' ? __('common.deposit') : __('common.withdrawal')),
                'reference' => $reference,
                'status' => Transaction::STATUS_PENDING,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Set the polymorphic relationship
            if ($recapData['target_type'] === 'App\\Models\\Account') {
                $transaction->account_id = $target->id;
            } else {
                $transaction->wallet_id = $target->id;
            }

            $transaction->save();

            // Emit an event to refresh the transaction list
            $this->dispatch('transaction-created');

            // Show success message
            $this->dispatch('alert', ['type' => 'success', 'message' => __('common.transaction_submitted_successfully')]);

            return true;
        } catch (\Exception $e) {
            logger()->error('Transaction creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        $accounts = Auth::user()->accounts;
        $wallets = Auth::user()->wallets;

        return view('livewire.deposit-management', compact('accounts', 'wallets'));
    }
}
