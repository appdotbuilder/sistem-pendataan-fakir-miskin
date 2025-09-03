<?php

namespace Database\Seeders;

use App\Models\Household;
use App\Models\HouseholdAsset;
use App\Models\User;
use Illuminate\Database\Seeder;

class HouseholdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users with specific roles
        $pengisiData = User::factory()->pengisiData()->create([
            'name' => 'Ahmad Wijaya',
            'email' => 'pengisi@example.com',
        ]);

        $verifikator = User::factory()->verifikator()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'verifikator@example.com',
        ]);

        $kepalaDinas = User::factory()->kepalaDinas()->create([
            'name' => 'Budi Santoso',
            'email' => 'kepala@example.com',
        ]);

        // Create additional users
        User::factory()->count(5)->pengisiData()->create();
        User::factory()->count(3)->verifikator()->create();

        // Create households with different statuses
        
        // Poor households (not yet verified)
        $poorHouseholds = Household::factory()
            ->count(15)
            ->miskin()
            ->belumDiverifikasi()
            ->create([
                'created_by' => $pengisiData->id,
            ]);

        // Non-poor households (approved)
        $nonPoorHouseholds = Household::factory()
            ->count(10)
            ->tidakMiskin()
            ->disetujui()
            ->create([
                'created_by' => $pengisiData->id,
                'verified_by' => $verifikator->id,
            ]);

        // Households needing revision
        $revisionHouseholds = Household::factory()
            ->count(5)
            ->perluRevisi()
            ->create([
                'created_by' => $pengisiData->id,
                'verified_by' => $verifikator->id,
            ]);

        // Random households with mixed statuses
        Household::factory()
            ->count(20)
            ->create();

        // Create assets for some households
        $allHouseholds = Household::all();
        
        foreach ($allHouseholds->take(30) as $household) {
            // Electronic assets
            HouseholdAsset::factory()
                ->count(random_int(2, 5))
                ->electronic()
                ->create(['household_id' => $household->id]);

            // Vehicle assets (some households)
            if (random_int(1, 10) > 6) {
                HouseholdAsset::factory()
                    ->vehicle()
                    ->owned()
                    ->create(['household_id' => $household->id]);
            }

            // Livestock assets (rural households)
            if (random_int(1, 10) > 7) {
                HouseholdAsset::factory()
                    ->count(random_int(1, 3))
                    ->livestock()
                    ->owned()
                    ->create(['household_id' => $household->id]);
            }

            // Land assets (some households)
            if (random_int(1, 10) > 5) {
                HouseholdAsset::factory()
                    ->land()
                    ->owned()
                    ->create(['household_id' => $household->id]);
            }
        }
    }
}