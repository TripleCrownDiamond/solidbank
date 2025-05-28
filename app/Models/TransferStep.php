<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferStep extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'code',
        'order',
        'progress_percentage',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

