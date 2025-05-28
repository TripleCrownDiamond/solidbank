<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'type',
        'card_number',
        'expiry_month',
        'expiry_year',
        'cvv',
        'card_holder_name',
        'balance',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

