<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'iban_country_code',
        'iban_bank_code',
        'iban_branch_code',
        'iban_account_length',
        'iban_prefix',
        'bank_name',
        'bank_swift',
        'bank_country',
        'bank_address',
        'bank_phone',
        'bank_email',
        'bank_website',
        'notification_email',
        'two_factor_auth',
        'account_prefix',
        'account_length',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
