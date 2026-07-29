<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'user_role' => 'Super Admin',
            'user_type' => 'super_admin',
            'is_active' => 1,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_name' => 'Antigravity',
            'company_logo' => 'logo.png',
            'profile_photo' => 'photo.jpg',
            'profile_background' => 'bg.jpg',
            'contact_number' => '1234567890',
            'gender' => 'Male',
            'address_1' => '123 Main St',
            'address_2' => '',
            'city' => 'Anytown',
            'state' => 'State',
            'zipcode' => '12345',
            'country' => 1,
            'last_login_date' => now()->toDateTimeString(),
            'last_login_ip' => '127.0.0.1',
            'is_logged_in' => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
