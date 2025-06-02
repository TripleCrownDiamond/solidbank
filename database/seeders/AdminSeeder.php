<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $france = Country::where('code', 'FR')->first();

        User::create([
            'name' => 'Administrator',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@solidbank.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Azerty%1234'),
            'is_admin' => true,
            'gender' => 'male',
            'birth_date' => '1980-01-01',
            'marital_status' => 'single',
            'profession' => 'Administrator',
            'phone_number' => '+1234567890',
            'country_id' => $france->id,
            'region' => 'Admin Region',
            'city' => 'Admin City',
            'postal_code' => '00000',
            'address' => 'Admin Address',
            'identity_document_url' => null,
            'address_document_url' => null,
            'status' => 'active',
        ]);
    }
}
