<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        $this->call(UserSeeder::class);
        
        // Seed services
        $this->call(ServiceSeeder::class);
        
        // Seed settings
        $this->call(SettingsSeeder::class);
    }
}

