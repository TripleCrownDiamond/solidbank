<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $france = Country::where('code', 'FR')->first();

        User::create([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@solidbank.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
            'gender' => 'male',
            'birth_date' => '1990-05-15',
            'marital_status' => 'single',
            'profession' => 'Tester',
            'phone_number' => '+33612345678',
            'country_id' => $france->id,
            'region' => 'Ile-de-France',
            'city' => 'Paris',
            'postal_code' => '75001',
            'address' => '123 Test Street',
            'identity_document_url' => null,
            'address_document_url' => null,
            'status' => 'active',
        ]);
    }
} 