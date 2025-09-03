<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['pengisi_data', 'verifikator', 'kepala_dinas']),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->jobTitle(),
            'address' => fake()->address(),
            'remember_token' => Str::random(10),
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

    /**
     * Indicate that the user is a data entry clerk.
     */
    public function pengisiData(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengisi_data',
            'position' => 'Petugas Pendataan',
        ]);
    }

    /**
     * Indicate that the user is a verifier.
     */
    public function verifikator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'verifikator',
            'position' => 'Petugas Verifikasi',
        ]);
    }

    /**
     * Indicate that the user is a head of office.
     */
    public function kepalaDinas(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'kepala_dinas',
            'position' => 'Kepala Dinas Sosial',
        ]);
    }
}