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
        'processed_by_admin_id', 'processed_at'
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

        // Envoyer un email de confirmation à l'utilisateur
        $this->sendConfirmationEmail();
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

        // Envoyer un email d'annulation à l'utilisateur
        $this->sendCancellationEmail();
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

    /**
     * Envoyer un email de confirmation de transaction
     */
    private function sendConfirmationEmail()
    {
        try {
            if ($this->user) {
                // Déterminer le compte ou wallet concerné
                $account = null;
                if ($this->account_id) {
                    $account = $this->account;
                } elseif ($this->wallet_id) {
                    // Pour les wallets, on peut passer null comme account
                    $account = null;
                }

                // Déterminer la devise
                $currency = $this->currency ?: ($this->account ? $this->account->currency : ($this->wallet ? $this->wallet->cryptocurrency->symbol : 'EUR'));
                $amountWithCurrency = number_format($this->amount, 2) . ' ' . $currency;
                
                // Envoyer l'email de confirmation de dépôt
                \Illuminate\Support\Facades\Mail::to($this->user->email)
                    ->send(new \App\Mail\AccountStatusNotification(
                        $this->user,
                        $account,
                        'deposit_confirmed',
                        $amountWithCurrency,
                        $this->processed_by_admin_id
                    ));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(__('messages.failed_to_send_transaction_confirmation_email') . ': ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un email d'annulation de transaction
     */
    private function sendCancellationEmail()
    {
        try {
            if ($this->user) {
                // Déterminer le compte ou wallet concerné
                $account = null;
                if ($this->account_id) {
                    $account = $this->account;
                } elseif ($this->wallet_id) {
                    // Pour les wallets, on peut passer null comme account
                    $account = null;
                }

                // Déterminer la devise
                $currency = $this->currency ?: ($this->account ? $this->account->currency : ($this->wallet ? $this->wallet->cryptocurrency->symbol : 'EUR'));
                $amountWithCurrency = number_format($this->amount, 2) . ' ' . $currency;
                
                // Envoyer l'email d'annulation de transaction
                \Illuminate\Support\Facades\Mail::to($this->user->email)
                    ->send(new \App\Mail\AccountStatusNotification(
                        $this->user,
                        $account,
                        'transaction_cancelled',
                        $amountWithCurrency,
                        $this->processed_by_admin_id
                    ));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(__('messages.failed_to_send_transaction_cancellation_email') . ': ' . $e->getMessage());
        }
    }
}

