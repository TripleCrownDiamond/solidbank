<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_number',
        'type',
        'currency',
        'status',
        'minimum_deposit',
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
}
