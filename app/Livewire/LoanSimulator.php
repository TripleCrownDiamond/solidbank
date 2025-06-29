<?php

namespace App\Livewire;

use Livewire\Component;

class LoanSimulator extends Component
{
    public $amount = 5000;
    public $duration = 24;
    public $currency = 'EUR';

    public function render()
    {
        $rate = 1.9; // Taux fixe exemple
        $monthly = $this->amount * ($rate/100/12) / (1 - pow(1 + $rate/100/12, -$this->duration));
        $monthly = round($monthly, 2);

        // Symboles de devises
        $currencySymbols = [
            'EUR' => '€',
            'GBP' => '£',
            'CHF' => 'CHF',
            'SEK' => 'kr',
            'NOK' => 'kr',
            'DKK' => 'kr',
            'PLN' => 'zł',
            'CZK' => 'Kč',
            'HUF' => 'Ft',
            'RON' => 'lei',
            'BGN' => 'лв',
            'HRK' => 'kn'
        ];

        $currencySymbol = $currencySymbols[$this->currency] ?? '€';

        return view('livewire.loan-simulator', [
            'monthly' => $monthly,
            'rate' => $rate,
            'currencySymbol' => $currencySymbol,
        ]);
    }
}