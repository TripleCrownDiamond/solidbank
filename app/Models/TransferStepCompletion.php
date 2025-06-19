<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferStepCompletion extends Model
{
    protected $fillable = [
        'transaction_id',
        'transfer_step_id',
        'entered_code',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transferStep(): BelongsTo
    {
        return $this->belongsTo(TransferStep::class);
    }
}