<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Account extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($account) {
            try {
                Log::info('Starting account deletion for account ID: ' . $account->id);

                // Delete related records that don't have cascade delete
                // RIB and Cards have cascade delete in database, so we don't need to delete them manually
                // Transactions have nullOnDelete, so we just need to update them
                $sentCount = $account->sentTransactions()->update(['from_account_id' => null]);
                $receivedCount = $account->receivedTransactions()->update(['to_account_id' => null]);

                Log::info("Updated {$sentCount} sent transactions and {$receivedCount} received transactions for account {$account->id}");
            } catch (\Exception $e) {
                Log::error('Error during account deletion cleanup: ' . $e->getMessage());
                throw $e;  // Re-throw to prevent deletion if cleanup fails
            }
        });
    }

    protected $fillable = [
        'user_id',
        'account_number',
        'type',
        'currency',
        'status',
        'balance',
        'minimum_deposit',
        'suspension_reason',
        'suspension_instructions',
    ];

    /**
     * L'utilisateur propriétaire du compte
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RIB lié à ce compte
     */
    public function rib()
    {
        return $this->hasOne(Rib::class);
    }

    /**
     * Cartes associées à ce compte
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Demandes de cartes associées à ce compte
     */
    public function cardRequests()
    {
        return $this->hasMany(CardRequest::class);
    }

    /**
     * Transactions envoyées
     */
    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    /**
     * Transactions reçues
     */
    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    /**
     * Groupes d'étapes de transfert associés à ce compte
     */
    public function transferStepGroups()
    {
        return $this->belongsToMany(TransferStepGroup::class, 'account_transfer_step_group');
    }
}
