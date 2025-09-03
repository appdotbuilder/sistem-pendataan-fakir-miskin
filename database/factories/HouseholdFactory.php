<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Household>
 */
class HouseholdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'respondent_status' => fake()->randomElement(['ditemukan', 'pindah', 'tidak_ditemukan', 'meninggal']),
            'respondent_status_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'head_of_household_name' => fake()->name(),
            'family_card_number' => fake()->unique()->numerify('################'),
            'nik' => fake()->unique()->numerify('################'),
            'address' => fake()->address(),
            'rt' => fake()->numberBetween(1, 20),
            'rw' => fake()->numberBetween(1, 15),
            'village' => fake()->word() . ' Village',
            'district' => fake()->word() . ' District',
            'house_ownership_status' => fake()->randomElement(['milik_sendiri', 'kontrak', 'sewa', 'bebas_sewa', 'dinas', 'lainnya']),
            'floor_type' => fake()->randomElement(['marmer', 'keramik', 'parket', 'kayu', 'semen', 'bambu', 'tanah']),
            'wall_type' => fake()->randomElement(['tembok', 'kayu', 'bambu', 'seng', 'lainnya']),
            'roof_type' => fake()->randomElement(['genteng', 'seng', 'asbes', 'kayu', 'bambu', 'ijuk', 'lainnya']),
            'water_source' => fake()->randomElement(['pdam', 'sumur_bor', 'sumur_gali', 'mata_air', 'air_hujan', 'sungai', 'lainnya']),
            'lighting_source' => fake()->randomElement(['listrik_pln', 'listrik_non_pln', 'minyak_tanah', 'petromaks', 'lilin', 'lainnya']),
            'pln_id' => fake()->optional()->numerify('############'),
            'electricity_power' => fake()->optional()->randomElement(['450VA', '900VA', '1300VA', '2200VA']),
            'toilet_facility' => fake()->randomElement(['sendiri', 'bersama', 'umum', 'tidak_ada']),
            'toilet_type' => fake()->randomElement(['leher_angsa', 'plengsengan', 'cemplung', 'tidak_ada']),
            'waste_disposal' => fake()->randomElement(['tangki_septik', 'spal', 'kolam', 'sungai', 'pantai', 'tanah_lapang', 'lainnya']),
            'cooking_fuel' => fake()->randomElement(['listrik', 'gas_lpg', 'gas_kota', 'minyak_tanah', 'kayu_bakar', 'arang', 'lainnya']),
            'latitude' => fake()->latitude(-11, 6), // Indonesia latitude range
            'longitude' => fake()->longitude(95, 141), // Indonesia longitude range
            'recommendation_status' => fake()->randomElement(['miskin', 'tidak_miskin']),
            'verification_status' => fake()->randomElement(['belum_diverifikasi', 'disetujui', 'perlu_revisi']),
            'verification_notes' => fake()->optional()->sentence(),
            'verified_by' => fake()->optional(0.5)->numberBetween(1, 10),
            'verified_at' => fake()->optional()->dateTimeBetween('-6 months', 'now'),
            'created_by' => User::factory(),
            'updated_by' => fake()->optional(0.3)->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the household is poor.
     */
    public function miskin(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommendation_status' => 'miskin',
            'floor_type' => fake()->randomElement(['tanah', 'bambu', 'semen']),
            'wall_type' => fake()->randomElement(['bambu', 'seng', 'kayu']),
            'water_source' => fake()->randomElement(['sumur_gali', 'mata_air', 'sungai']),
            'lighting_source' => fake()->randomElement(['listrik_non_pln', 'minyak_tanah', 'lilin']),
            'toilet_facility' => fake()->randomElement(['bersama', 'umum', 'tidak_ada']),
        ]);
    }

    /**
     * Indicate that the household is not poor.
     */
    public function tidakMiskin(): static
    {
        return $this->state(fn (array $attributes) => [
            'recommendation_status' => 'tidak_miskin',
            'floor_type' => fake()->randomElement(['marmer', 'keramik', 'parket']),
            'wall_type' => 'tembok',
            'water_source' => fake()->randomElement(['pdam', 'sumur_bor']),
            'lighting_source' => 'listrik_pln',
            'toilet_facility' => 'sendiri',
        ]);
    }

    /**
     * Indicate that the household is not yet verified.
     */
    public function belumDiverifikasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'belum_diverifikasi',
            'verification_notes' => null,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate that the household is approved.
     */
    public function disetujui(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'disetujui',
            'verification_notes' => fake()->optional()->sentence(),
            'verified_by' => User::factory(),
            'verified_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the household needs revision.
     */
    public function perluRevisi(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'perlu_revisi',
            'verification_notes' => fake()->sentence(),
            'verified_by' => User::factory(),
            'verified_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}