<?php

namespace Database\Factories;

use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseholdPhoto>
 */
class HouseholdPhotoFactory extends Factory
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
            'file_path' => 'households/' . fake()->uuid() . '.jpg',
            'original_name' => fake()->word() . '_rumah.jpg',
            'file_size' => fake()->numberBetween(100000, 2000000), // 100KB - 2MB
            'mime_type' => 'image/jpeg',
            'order' => fake()->numberBetween(1, 3),
            'description' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate this is the first photo.
     */
    public function first(): static
    {
        return $this->state(fn (array $attributes) => [
            'order' => 1,
            'description' => 'Foto depan rumah',
        ]);
    }

    /**
     * Indicate this is the second photo.
     */
    public function second(): static
    {
        return $this->state(fn (array $attributes) => [
            'order' => 2,
            'description' => 'Foto samping rumah',
        ]);
    }

    /**
     * Indicate this is the third photo.
     */
    public function third(): static
    {
        return $this->state(fn (array $attributes) => [
            'order' => 3,
            'description' => 'Foto belakang rumah',
        ]);
    }

    /**
     * Indicate this is a PNG file.
     */
    public function png(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_path' => 'households/' . fake()->uuid() . '.png',
            'original_name' => fake()->word() . '_rumah.png',
            'mime_type' => 'image/png',
        ]);
    }
}