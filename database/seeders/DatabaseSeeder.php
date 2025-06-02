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

        // Create 30 users with accounts
        User::factory(30)->create()->each(function ($user) {
            Account::factory()->create(['user_id' => $user->id]);
        });
    }
}
