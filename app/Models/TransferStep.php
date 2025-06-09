<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferStep extends Model
{
    protected $fillable = [
        'transfer_step_group_id',
        'title',
        'description',
        'code',
        'order',
        'type',
    ];

    public function transferStepGroup()
    {
        return $this->belongsTo(TransferStepGroup::class);
    }
}
