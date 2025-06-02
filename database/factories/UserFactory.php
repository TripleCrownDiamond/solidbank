<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Generate a safe email that doesn't contain 'admin' before @
     *
     * @return string
     */
    private function generateSafeEmail(): string
    {
        do {
            $email = fake()->unique()->safeEmail();
            $localPart = explode('@', $email)[0];
        } while (stripos($localPart, 'admin') !== false);

        return $email;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => $this->generateSafeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('Azerty%1234'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'birth_date' => fake()->date('Y-m-d', '-18 years'),
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced', 'widowed']),
            'profession' => fake()->jobTitle(),
            'phone_number' => fake()->phoneNumber(),
            'country_id' => Country::inRandomOrder()->first()->id,
            'region' => fake()->state(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'address' => fake()->address(),
            'identity_document_url' => null,
            'address_document_url' => null,
            'is_admin' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
            ];
        });
    }
}
