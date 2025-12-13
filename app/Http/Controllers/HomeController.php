<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Safely decode a JSON setting into an array with fallback.
     */
    private function decodeSettingArray(?string $json, array $default): array
    {
        if (!$json) {
            return $default;
        }

        try {
            $decoded = json_decode($json, true);
            return is_array($decoded) && !empty($decoded) ? $decoded : $default;
        } catch (\Throwable $th) {
            return $default;
        }
    }

    /**
     * Show the landing page
     */
    public function index()
    {
        // Get featured services (limit to 3 per type for landing page)
        $psychologyServices = Service::active()
            ->byType('psychology')
            ->orderBy('name')
            ->limit(3)
            ->get();

        $homeopathyServices = Service::active()
            ->byType('homeopathy')
            ->orderBy('name')
            ->limit(3)
            ->get();

        // Get total counts for stats
        $totalPsychologyServices = Service::active()->byType('psychology')->count();
        $totalHomeopathyServices = Service::active()->byType('homeopathy')->count();

        return view('home', compact('psychologyServices', 'homeopathyServices', 'totalPsychologyServices', 'totalHomeopathyServices'));
    }

    /**
     * Show About Us page with clinic story and values
     */
    public function about()
    {
        $values = $this->decodeSettingArray(
            get_setting('about_values'),
            []
        );

        $aboutHeroTitle = get_setting('about_hero_title', '');
        $aboutHeroSubtitle = get_setting('about_hero_subtitle', '');

        $aboutStoryShort = get_setting('about_story_short', '');
        $aboutStoryLong = get_setting('about_story_long', '');
        $aboutVision = get_setting('about_vision', '');
        $aboutMissionItems = $this->decodeSettingArray(
            get_setting('about_mission_items'),
            []
        );

        $aboutValuesDescription = get_setting('about_values_description', '');

        return view(
            'about',
            compact(
                'values',
                'aboutHeroTitle',
                'aboutHeroSubtitle',
                'aboutStoryShort',
                'aboutStoryLong',
                'aboutVision',
                'aboutMissionItems',
                'aboutValuesDescription'
            )
        );
    }

    /**
     * Show Our Team page highlighting clinicians and staff
     */
    public function team()
    {
        $teamMembers = $this->decodeSettingArray(
            get_setting('team_members'),
            []
        );

        $teamHeroTitle = get_setting(
            'team_hero_title',
            'People-first clinicians. Coordinated, informed, and ready to help.'
        );
        $teamHeroSubtitle = get_setting(
            'team_hero_subtitle',
            'Meet the multidisciplinary team that delivers continuous, connected care—combining psychology, homeopathy, and coordinated support.'
        );

        return view('team', compact(
            'teamMembers',
            'teamHeroTitle',
            'teamHeroSubtitle'
        ));
    }
}

