<?php

use App\Models\Package;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if packages table exists
        if (!Schema::hasTable('packages')) {
            return;
        }

        // Check if packages already exist in database
        if (Package::count() > 0) {
            return;
        }

        // Get packages from Settings
        $packagesJson = Setting::get('packages');
        
        if (!$packagesJson) {
            return;
        }

        // Decode JSON
        $packages = json_decode($packagesJson, true);
        
        if (!is_array($packages) || empty($packages)) {
            return;
        }

        // Migrate each package
        foreach ($packages as $index => $packageData) {
            try {
                Package::create([
                    'name' => $packageData['name'] ?? 'Package ' . ($index + 1),
                    'description' => $packageData['description'] ?? null,
                    'original_price' => isset($packageData['original_price']) ? (float) $packageData['original_price'] : null,
                    'price' => isset($packageData['price']) ? (float) $packageData['price'] : 0,
                    'sessions' => $packageData['sessions'] ?? null,
                    'duration' => $packageData['duration'] ?? null,
                    'image' => $packageData['image'] ?? null,
                    'is_active' => true,
                ]);
            } catch (\Exception $e) {
                // Log error but continue with other packages
                \Log::warning("Failed to migrate package: " . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally remove migrated packages
        // Package::truncate(); // Uncomment if you want to remove all packages on rollback
    }
};
