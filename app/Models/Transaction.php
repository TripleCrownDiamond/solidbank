<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount', 'type', 'status', 'description', 'reference',
        'from_account_id', 'to_account_id',
        'external_crypto_info', 'external_bank_info'
    ];

    protected $casts = [
        'external_crypto_info' => 'array',
        'external_bank_info' => 'array',
    ];

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}

