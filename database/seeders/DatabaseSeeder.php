<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->kepalaDinas()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
        ]);

        // Create test user for each role
        User::factory()->pengisiData()->create([
            'name' => 'Test Pengisi Data',
            'email' => 'test@example.com',
        ]);

        User::factory()->verifikator()->create([
            'name' => 'Test Verifikator',
            'email' => 'verifikator@example.com',
        ]);

        // Seed households and related data
        $this->call([
            HouseholdSeeder::class,
        ]);
    }
}