<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if (!$admin) {
            $admin = User::factory()->create([
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        Config::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'iban_country_code' => 'FR',
                'iban_bank_code' => '30759',
                'iban_branch_code' => '00001',
                'iban_account_length' => 11,
                'iban_prefix' => 'FR76',
                'bank_name' => config('app.name'),
                'bank_swift' => 'SOGEFRPP',
                'bank_country' => 'FR',
                'bank_address' => '29 Rue du Faubourg, Paris, France',
                'bank_phone' => '+33123456789',
                'bank_email' => 'support@solidbank.com',
                'bank_website' => 'https://www.solidbank.fr',
                'notification_email' => true,
                'two_factor_auth' => true,
            ]
        );
    }
}
