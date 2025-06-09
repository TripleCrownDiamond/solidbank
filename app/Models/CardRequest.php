<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CardRequest extends Model
{
    protected $fillable = [
        'account_id',
        'card_type',
        'phone_number',
        'status',
        'message',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime'
    ];

    /**
     * Relation avec le compte
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relation avec l'administrateur qui traite la demande
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope pour les demandes en attente
     */
    public function scopePending($query)
    {
        return $query->where('card_requests.status', 'PENDING');
    }

    /**
     * Scope pour les demandes approuvées
     */
    public function scopeApproved($query)
    {
        return $query->where('card_requests.status', 'APPROVED');
    }

    /**
     * Accesseur pour le statut en minuscules pour l'affichage
     */
    public function getStatusLowerAttribute()
    {
        return strtolower($this->status);
    }
}
