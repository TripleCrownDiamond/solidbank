<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CountrySeeder::class);

        $this->call(AdminSeeder::class);

        $this->call(ConfigSeeder::class);

        $this->call(TransferStepGroupSeeder::class);

        $this->call(CryptocurrencySeeder::class);

        // Create 30 users with accounts
        User::factory(250)->create()->each(function ($user) {
            Account::factory()->create(['user_id' => $user->id]);
        });
    }
}
