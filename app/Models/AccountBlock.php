<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'description',
        'instructions',
        'amount_to_pay',
        'currency',
        'show_rib',
        'request_id_document',
    ];

    protected $casts = [
        'amount_to_pay' => 'decimal:2',
        'show_rib' => 'boolean',
        'request_id_document' => 'boolean',
    ];

    /**
     * Les comptes associés à ce blocage.
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_account_block')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Obtenir les comptes avec un statut actif pour ce blocage.
     */
    public function activeAccounts()
    {
        return $this->accounts()->wherePivot('status', 'active');
    }

    /**
     * Obtenir les comptes avec un statut inactif pour ce blocage.
     */
    public function inactiveAccounts()
    {
        return $this->accounts()->wherePivot('status', 'inactive');
    }
}