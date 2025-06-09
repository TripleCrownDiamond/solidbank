<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferStepGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Les étapes de transfert appartenant à ce groupe
     */
    public function transferSteps()
    {
        return $this->hasMany(TransferStep::class);
    }

    /**
     * Les comptes utilisant ce groupe d'étapes
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_transfer_step_group');
    }

    /**
     * Les portefeuilles utilisant ce groupe d'étapes
     */
    public function wallets()
    {
        return $this->belongsToMany(Wallet::class, 'wallet_transfer_step_group');
    }
}
