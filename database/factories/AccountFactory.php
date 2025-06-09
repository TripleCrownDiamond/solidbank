<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Config;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        // Get config for account number generation
        $config = Config::first();
        $prefix = $config ? $config->account_prefix : 'ACC';
        $length = $config ? $config->account_length : 10;

        // Generate unique account number with prefix and random digits
        $numberLength = $length - strlen($prefix);
        do {
            $accountNumber = $prefix . str_pad(rand(0, pow(10, $numberLength) - 1), $numberLength, '0', STR_PAD_LEFT);
        } while (Account::where('account_number', $accountNumber)->exists());

        return [
            'account_number' => $accountNumber,
            'balance' => 0,
            'currency' => 'EUR',
            'type' => $this->faker->randomElement(['CHECKING', 'SAVINGS']),
            'status' => 'INACTIVE',
        ];
    }
}
