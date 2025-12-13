<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if pages table exists
        if (!Schema::hasTable('pages')) {
            return;
        }

        // Create Services page if it doesn't exist
        Page::updateOrCreate(
            ['type' => 'services', 'slug' => 'services'],
            [
                'title' => 'Our Services',
                'slug' => 'services',
                'type' => 'services',
                'content' => [
                    'hero_title' => 'Our Services',
                    'hero_subtitle' => 'We offer comprehensive psychology and homeopathy treatments to support your health and well-being.',
                ],
                'is_published' => true,
                'order' => 0,
            ]
        );

        // Ensure packages page exists and is published (if it was created earlier)
        $packagesPage = Page::where('type', 'packages')->first();
        if ($packagesPage) {
            $packagesPage->update(['is_published' => true]);
        } else {
            Page::updateOrCreate(
                ['type' => 'packages', 'slug' => 'packages'],
                [
                    'title' => 'Special Packages',
                    'slug' => 'packages',
                    'type' => 'packages',
                    'content' => [
                        'hero_title' => 'Special Packages',
                        'hero_subtitle' => 'Choose from our specially curated packages designed to meet your wellness needs.',
                    ],
                    'is_published' => true,
                    'order' => 3,
                ]
            );
        }

        // Ensure team page exists and is published (if it was created earlier)
        $teamPage = Page::where('type', 'team')->first();
        if ($teamPage) {
            $teamPage->update(['is_published' => true]);
        } else {
            Page::updateOrCreate(
                ['type' => 'team', 'slug' => 'team'],
                [
                    'title' => 'Our Team',
                    'slug' => 'team',
                    'type' => 'team',
                    'content' => [
                        'hero_title' => 'Our Team',
                        'hero_subtitle' => 'Meet the multidisciplinary team that delivers continuous, connected care—combining psychology, homeopathy, and coordinated support.',
                    ],
                    'is_published' => true,
                    'order' => 2,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove services page
        Page::where('type', 'services')->delete();
    }
};
