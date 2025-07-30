<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        Config::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'iban_country_code' => 'FR',
                'iban_bank_code' => '30759',
                'iban_branch_code' => '00001',
                'iban_account_length' => 11,
                'iban_prefix' => 'FR76',
                'bank_name' => config('app.name'),
                'bank_swift' => 'DBFFRPP',
                'bank_country' => 'FR',
                'bank_address' => '18 QUAI DE LA RAPEE, 75012 PARIS France',
                'bank_phone' => '+33123456789',
                'bank_email' => 'contact@dbqic.com',
                'bank_website' => 'https://www.dbqic.com',
                'logo_url' => 'img/logo_blue.svg',
                'icon_url' => 'img/icon_blue.svg',
                'favicon_url' => 'favicon.ico',
                'notification_email' => 'contact@dbqic.com',
                'two_factor_auth' => true,
                'user_can_self_activate' => false,
                'automatic_rib' => false,
                'account_prefix' => 'DB',
                'account_length' => 10,
                'transaction_validation_method' => 'manual',
                'activate_crypto_features' => true,
            ]
        );
    }
}
