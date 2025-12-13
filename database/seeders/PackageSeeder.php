<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'TWIN PACKAGE',
                'description' => '',
                'original_price' => 500,
                'price' => 450,
                'sessions' => '2X SESSIONS',
                'duration' => '1 HOUR PER SESSION',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'PLATINUM PACKAGE',
                'description' => '',
                'original_price' => 2500,
                'price' => 2000,
                'sessions' => '10X SESSIONS',
                'duration' => '1 HOUR PER SESSION',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'PREMIUM PACKAGE',
                'description' => '',
                'original_price' => 1000,
                'price' => 860,
                'sessions' => '4X SESSIONS',
                'duration' => '1 HOUR PER SESSION',
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }

        $this->command->info('Packages seeded successfully.');
    }
}
