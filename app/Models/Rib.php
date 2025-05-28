<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rib extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'iban',
        'swift',
        'bank_name',
    ];

    /**
     * Le compte auquel ce RIB appartient
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
