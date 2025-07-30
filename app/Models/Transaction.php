<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Constantes pour les statuts
    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_BLOCKED = 'BLOCKED';
    protected $fillable = [
        'amount', 'type', 'status', 'description', 'reference',
        'from_account_id', 'to_account_id',
        'external_crypto_info', 'external_bank_info',
        'user_id', 'account_id', 'wallet_id', 'currency',
        'blocked_at_transfer_step_id', 'blocked_at_transfer_step_group_id',
        'is_blocked', 'blocked_reason', 'blocked_at',
        'processed_by_admin_id', 'processed_at', 'progress_percentage',
        'receipt_path'
    ];

    protected $casts = [
        'amount' => 'float',
        'external_crypto_info' => 'array',
        'external_bank_info' => 'array',
        'blocked_at' => 'datetime',
        'processed_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function blockedAtTransferStep()
    {
        return $this->belongsTo(TransferStep::class, 'blocked_at_transfer_step_id');
    }

    public function blockedAtTransferStepGroup()
    {
        return $this->belongsTo(TransferStepGroup::class, 'blocked_at_transfer_step_group_id');
    }

    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    public function transferStepCompletions()
    {
        return $this->hasMany(TransferStepCompletion::class);
    }

    public function getCompletedStepsCount()
    {
        return $this->transferStepCompletions()->count();
    }

    public function isStepCompleted($stepId)
    {
        return $this->transferStepCompletions()->where('transfer_step_id', $stepId)->exists();
    }

    /**
     * Confirmer une transaction de dépôt
     */
    public function confirm($adminId = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_by_admin_id' => $adminId,
            'processed_at' => now(),
        ]);

        // Mettre à jour le solde selon le type de transaction
        if ($this->type === 'DEPOSIT') {
            // Dépôt sur un compte
            if ($this->account_id && $this->account) {
                $this->account->increment('balance', $this->amount);
            }
            // Dépôt sur un wallet
            elseif ($this->wallet_id && $this->wallet) {
                $this->wallet->increment('balance', $this->amount);
            }
        } elseif ($this->type === 'WITHDRAWAL') {
            // Retrait d'un compte
            if ($this->account_id && $this->account) {
                $this->account->decrement('balance', $this->amount);
            }
            // Retrait d'un wallet
            elseif ($this->wallet_id && $this->wallet) {
                $this->wallet->decrement('balance', $this->amount);
            }
        }

        // Email de confirmation envoyé depuis TransactionList.php pour éviter la duplication
    }

    /**
     * Annuler une transaction
     */
    public function cancel($adminId = null, $reason = null)
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'processed_by_admin_id' => $adminId,
            'processed_at' => now(),
            'blocked_reason' => $reason,
        ]);

        // Email d'annulation envoyé depuis TransactionList.php pour éviter la duplication
    }

    /**
     * Vérifier si la transaction est en attente
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si la transaction est terminée
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Vérifier si la transaction est annulée
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Vérifier si la transaction a échoué
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Bloquer une transaction à une étape de transfert
     */
    public function blockAtTransferStep($transferStepId, $transferStepGroupId = null, $reason = null)
    {
        $this->update([
            'is_blocked' => true,
            'blocked_at_transfer_step_id' => $transferStepId,
            'blocked_at_transfer_step_group_id' => $transferStepGroupId,
            'blocked_reason' => $reason,
            'blocked_at' => now(),
        ]);
    }

    /**
     * Débloquer une transaction
     */
    public function unblock($adminId = null)
    {
        $this->update([
            'is_blocked' => false,
            'blocked_at_transfer_step_id' => null,
            'blocked_at_transfer_step_group_id' => null,
            'blocked_reason' => null,
            'blocked_at' => null,
            'processed_by_admin_id' => $adminId,
            'processed_at' => now(),
        ]);
    }

    /**
     * Vérifier si la transaction est bloquée
     */
    public function isBlocked()
    {
        return $this->is_blocked === true;
    }

    /**
     * Vérifier si toutes les étapes de transfert sont complétées
     */
    public function areAllTransferStepsCompleted()
    {
        // Déterminer le type de source (compte ou portefeuille)
        $sourceType = $this->account_id ? 'account' : 'wallet';
        $sourceId = $this->account_id ?: $this->wallet_id;
        
        if (!$sourceId) {
            return false;
        }

        // Récupérer les étapes de transfert selon le type de source
        if ($sourceType === 'account') {
            $account = \App\Models\Account::find($sourceId);
            if (!$account) {
                return false;
            }
            $transferSteps = $account->transferStepGroups()
                ->with('transferSteps')
                ->get()
                ->flatMap(function ($group) {
                    return $group->transferSteps;
                })
                ->sortBy('order');
        } else {
            $wallet = \App\Models\Wallet::find($sourceId);
            if (!$wallet) {
                return false;
            }
            $transferSteps = $wallet->transferStepGroups()
                ->with('transferSteps')
                ->get()
                ->flatMap(function ($group) {
                    return $group->transferSteps;
                })
                ->sortBy('order');
        }

        // Vérifier si toutes les étapes sont complétées
        foreach ($transferSteps as $step) {
            if (!$this->isStepCompleted($step->id)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Changer le statut de BLOCKED à PENDING si toutes les étapes sont complétées
     */
    public function updateStatusIfAllStepsCompleted()
    {
        if ($this->status === self::STATUS_BLOCKED && $this->areAllTransferStepsCompleted()) {
            $this->update([
                'status' => self::STATUS_PENDING,
                'is_blocked' => false,
                'blocked_at_transfer_step_id' => null,
                'blocked_at_transfer_step_group_id' => null,
                'blocked_reason' => null,
                'blocked_at' => null,
            ]);
            
            \Illuminate\Support\Facades\Log::info('Transaction status updated from BLOCKED to PENDING', [
                'transaction_id' => $this->id,
                'user_id' => $this->user_id
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Obtenir l'étape de transfert où la transaction est bloquée
     */
    public function getBlockedStepName()
    {
        if ($this->isBlocked() && $this->blockedAtTransferStep) {
            return $this->blockedAtTransferStep->name;
        }
        return null;
    }

    /**
     * Obtenir le groupe d'étapes de transfert où la transaction est bloquée
     */
    public function getBlockedStepGroupName()
    {
        if ($this->isBlocked() && $this->blockedAtTransferStepGroup) {
            return $this->blockedAtTransferStepGroup->name;
        }
        return null;
    }

    // Méthode supprimée pour éviter la duplication d'emails
    // L'envoi d'email est géré dans TransactionList.php

    // Méthodes d'envoi d'email supprimées pour éviter la duplication
    // L'envoi d'email est maintenant géré uniquement dans TransactionList.php
}

