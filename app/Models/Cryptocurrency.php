<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'network',
        'address_format',
        'address_example',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active cryptocurrencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get cryptocurrencies grouped by symbol
     */
    public static function getGroupedBySymbol()
    {
        return self::active()
            ->orderBy('symbol')
            ->orderBy('network')
            ->get()
            ->groupBy('symbol');
    }

    /**
     * Validate address format
     */
    public function validateAddress($address)
    {
        return preg_match('/' . $this->address_format . '/', $address);
    }
}
