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
        'logo_url',
        'icon_url',
        'favicon_url',
        'notification_email',
        'two_factor_auth',
        'account_prefix',
        'account_length',
        'transaction_validation_method',
        'brand_color',
        'brand_primary_hover',
        'brand_primary_light',
        'brand_primary_dark',
        'brand_secondary',
        'brand_accent',
        'brand_success',
        'brand_warning',
        'brand_error',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
