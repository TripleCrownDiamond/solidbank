<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'dial_code',
        'flag',
    ];

    /**
     * Get the users associated with this country.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the English name of the country based on its code.
     */
    public function getEnglishNameAttribute(): string
    {
        $englishNames = [
            'AL' => 'Albania',
            'DE' => 'Germany',
            'AD' => 'Andorra',
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'BY' => 'Belarus',
            'BA' => 'Bosnia and Herzegovina',
            'BG' => 'Bulgaria',
            'CY' => 'Cyprus',
            'HR' => 'Croatia',
            'DK' => 'Denmark',
            'ES' => 'Spain',
            'EE' => 'Estonia',
            'FI' => 'Finland',
            'FR' => 'France',
            'GR' => 'Greece',
            'HU' => 'Hungary',
            'IE' => 'Ireland',
            'IS' => 'Iceland',
            'IT' => 'Italy',
            'XK' => 'Kosovo',
            'LV' => 'Latvia',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MK' => 'North Macedonia',
            'MT' => 'Malta',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'ME' => 'Montenegro',
            'NO' => 'Norway',
            'NL' => 'Netherlands',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'CZ' => 'Czech Republic',
            'RO' => 'Romania',
            'GB' => 'United Kingdom',
            'RU' => 'Russia',
            'SM' => 'San Marino',
            'RS' => 'Serbia',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'UA' => 'Ukraine',
            'VA' => 'Vatican City',
        ];

        return $englishNames[$this->code] ?? $this->name;
    }
}
