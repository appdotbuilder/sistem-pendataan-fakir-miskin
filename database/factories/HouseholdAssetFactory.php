<?php

namespace Database\Factories;

use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseholdAsset>
 */
class HouseholdAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_id' => Household::factory(),
            'asset_type' => fake()->randomElement([
                'lemari_es', 'ac', 'tv', 'laptop', 'komputer', 'hp_android', 'hp_biasa',
                'sepeda_motor', 'mobil', 'emas', 'tabungan', 'tanah_bangunan', 
                'sawah', 'kebun', 'kolam', 'ternak_sapi', 'ternak_kerbau', 
                'ternak_kuda', 'ternak_babi', 'ternak_kambing', 'ternak_ayam'
            ]),
            'owned' => fake()->boolean(70), // 70% chance of owning the asset
            'quantity' => fake()->numberBetween(1, 5),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the asset is owned.
     */
    public function owned(): static
    {
        return $this->state(fn (array $attributes) => [
            'owned' => true,
            'quantity' => fake()->numberBetween(1, 10),
        ]);
    }

    /**
     * Indicate that the asset is not owned.
     */
    public function notOwned(): static
    {
        return $this->state(fn (array $attributes) => [
            'owned' => false,
            'quantity' => 0,
        ]);
    }

    /**
     * Electronic assets.
     */
    public function electronic(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => fake()->randomElement(['lemari_es', 'ac', 'tv', 'laptop', 'komputer', 'hp_android', 'hp_biasa']),
        ]);
    }

    /**
     * Vehicle assets.
     */
    public function vehicle(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => fake()->randomElement(['sepeda_motor', 'mobil']),
        ]);
    }

    /**
     * Livestock assets.
     */
    public function livestock(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => fake()->randomElement([
                'ternak_sapi', 'ternak_kerbau', 'ternak_kuda', 
                'ternak_babi', 'ternak_kambing', 'ternak_ayam'
            ]),
            'quantity' => fake()->numberBetween(1, 20),
        ]);
    }

    /**
     * Land assets.
     */
    public function land(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => fake()->randomElement(['tanah_bangunan', 'sawah', 'kebun', 'kolam']),
            'notes' => fake()->optional()->sentence() . ' (dalam meter persegi)',
        ]);
    }
}