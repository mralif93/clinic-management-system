<?php

use App\Models\Page;
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
        // Only run if pages table exists and is empty
        if (!Schema::hasTable('pages')) {
            return;
        }

        // Check if pages already exist
        if (Page::count() > 0) {
            return;
        }

        // Create About page (always create, even if settings are empty)
        $aboutHeroTitle = Setting::get('about_hero_title', '');
        $aboutHeroSubtitle = Setting::get('about_hero_subtitle', '');
        Page::updateOrCreate(
            ['type' => 'about', 'slug' => 'about'],
            [
                'title' => $aboutHeroTitle ?: 'About Us',
                'slug' => 'about',
                'type' => 'about',
                'content' => [
                    'hero_title' => $aboutHeroTitle,
                    'hero_subtitle' => $aboutHeroSubtitle,
                ],
                'is_published' => true,
                'order' => 1,
            ]
        );

        // Create Team page (always create, even if settings are empty)
        $teamHeroTitle = Setting::get('team_hero_title', '');
        $teamHeroSubtitle = Setting::get('team_hero_subtitle', '');
        Page::updateOrCreate(
            ['type' => 'team', 'slug' => 'team'],
            [
                'title' => $teamHeroTitle ?: 'Our Team',
                'slug' => 'team',
                'type' => 'team',
                'content' => [
                    'hero_title' => $teamHeroTitle,
                    'hero_subtitle' => $teamHeroSubtitle,
                ],
                'is_published' => true,
                'order' => 2,
            ]
        );

        // Create Packages page (always create, even if settings are empty)
        $packagesHeroTitle = Setting::get('packages_hero_title', '');
        $packagesHeroSubtitle = Setting::get('packages_hero_subtitle', '');
        Page::updateOrCreate(
            ['type' => 'packages', 'slug' => 'packages'],
            [
                'title' => $packagesHeroTitle ?: 'Special Packages',
                'slug' => 'packages',
                'type' => 'packages',
                'content' => [
                    'hero_title' => $packagesHeroTitle,
                    'hero_subtitle' => $packagesHeroSubtitle,
                ],
                'is_published' => true,
                'order' => 3,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove system pages created from settings
        Page::whereIn('type', ['about', 'team', 'packages'])->delete();
    }
};
