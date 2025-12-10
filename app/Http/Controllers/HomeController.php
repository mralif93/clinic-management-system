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
        $timeline = $this->decodeSettingArray(
            get_setting('about_timeline'),
            [
                [
                    'year' => '2018',
                    'title' => 'Clinic founded',
                    'description' => 'Started as a small practice focused on patient-centered care.',
                ],
                [
                    'year' => '2020',
                    'title' => 'Digital transformation',
                    'description' => 'Rolled out online appointments, telehealth, and integrated records.',
                ],
                [
                    'year' => '2022',
                    'title' => 'Specialty expansion',
                    'description' => 'Added homeopathy, psychotherapy, and preventive wellness programs.',
                ],
                [
                    'year' => '2024',
                    'title' => 'Community impact',
                    'description' => 'Launched outreach clinics and mental health awareness initiatives.',
                ],
            ]
        );

        $values = $this->decodeSettingArray(
            get_setting('about_values'),
            [
                [
                    'icon' => 'bx-heart',
                    'title' => 'Compassion first',
                    'description' => 'We listen deeply, respect every story, and treat people—never just symptoms.',
                    'accent' => 'text-rose-600 bg-rose-50',
                ],
                [
                    'icon' => 'bx-shield',
                    'title' => 'Safety and trust',
                    'description' => 'Privacy, clinical standards, and secure systems keep care safe and reliable.',
                    'accent' => 'text-blue-600 bg-blue-50',
                ],
                [
                    'icon' => 'bx-bar-chart',
                    'title' => 'Results with data',
                    'description' => 'Evidence-based protocols paired with outcomes tracking to guide every decision.',
                    'accent' => 'text-emerald-600 bg-emerald-50',
                ],
                [
                    'icon' => 'bx-group',
                    'title' => 'Teamwork',
                    'description' => 'Doctors, therapists, and support staff collaborate to give seamless care.',
                    'accent' => 'text-indigo-600 bg-indigo-50',
                ],
            ]
        );

        $aboutHeroTitle = get_setting('about_hero_title', 'Compassionate care powered by smart systems.');
        $aboutHeroSubtitle = get_setting(
            'about_hero_subtitle',
            'We built this clinic platform to make healthcare simpler—for patients booking visits, clinicians coordinating care, and teams running the day-to-day. Every feature is designed with safety, clarity, and trust in mind.'
        );
        $aboutCtaTitle = get_setting('about_cta_title', 'See how our team can help');
        $aboutCtaSubtitle = get_setting(
            'about_cta_subtitle',
            'Explore services tailored to your needs, or meet the clinicians behind your care.'
        );
        $aboutCtaPrimaryLabel = get_setting('about_cta_primary_label', 'Meet our team');
        $aboutCtaPrimaryLink = get_setting('about_cta_primary_link', route('team'));
        $aboutCtaSecondaryLabel = get_setting('about_cta_secondary_label', 'View services');
        $aboutCtaSecondaryLink = get_setting('about_cta_secondary_link', route('services.index'));

        return view(
            'about',
            compact(
                'timeline',
                'values',
                'aboutHeroTitle',
                'aboutHeroSubtitle',
                'aboutCtaTitle',
                'aboutCtaSubtitle',
                'aboutCtaPrimaryLabel',
                'aboutCtaPrimaryLink',
                'aboutCtaSecondaryLabel',
                'aboutCtaSecondaryLink'
            )
        );
    }

    /**
     * Show Our Team page highlighting clinicians and staff
     */
    public function team()
    {
        $leadership = $this->decodeSettingArray(
            get_setting('team_leadership'),
            [
                [
                    'name' => 'Dr. Aisha Rahman',
                    'role' => 'Medical Director',
                    'focus' => 'Integrative care & patient safety',
                    'bio' => 'Guides clinical standards and quality programs across all specialties.',
                    'photo' => null,
                ],
                [
                    'name' => 'Dr. Marcus Lee',
                    'role' => 'Head of Psychology',
                    'focus' => 'Trauma-informed therapy',
                    'bio' => 'Leads multidisciplinary therapy programs with measurable outcomes.',
                    'photo' => null,
                ],
                [
                    'name' => 'Dr. Priya Menon',
                    'role' => 'Lead Homeopath',
                    'focus' => 'Holistic wellness',
                    'bio' => 'Designs personalized care plans blending traditional and modern approaches.',
                    'photo' => null,
                ],
                [
                    'name' => 'Sofia Gomez, RN',
                    'role' => 'Patient Experience',
                    'focus' => 'Care coordination',
                    'bio' => 'Ensures every visit is smooth—from scheduling to follow-up.',
                    'photo' => null,
                ],
            ]
        );

        $careTeams = $this->decodeSettingArray(
            get_setting('team_care_teams'),
            [
                [
                    'title' => 'Psychologists & Therapists',
                    'members' => [
                        'Clinical psychologists',
                        'Family & couples therapists',
                        'Child and adolescent specialists',
                        'Trauma and EMDR practitioners',
                    ],
                    'color' => 'border-blue-100 bg-blue-50',
                ],
                [
                    'title' => 'Homeopathy & Wellness',
                    'members' => [
                        'Certified homeopaths',
                        'Lifestyle and nutrition coaches',
                        'Sleep and stress specialists',
                        'Preventive wellness educators',
                    ],
                    'color' => 'border-emerald-100 bg-emerald-50',
                ],
                [
                    'title' => 'Clinical Support',
                    'members' => [
                        'Registered nurses',
                        'Care coordinators',
                        'Laboratory & diagnostics',
                        'Patient outreach and education',
                    ],
                    'color' => 'border-indigo-100 bg-indigo-50',
                ],
            ]
        );

        $teamHeroTitle = get_setting(
            'team_hero_title',
            'People-first clinicians. Coordinated, informed, and ready to help.'
        );
        $teamHeroSubtitle = get_setting(
            'team_hero_subtitle',
            'Meet the multidisciplinary team that delivers continuous, connected care—combining psychology, homeopathy, and coordinated support.'
        );
        $teamCtaTitle = get_setting('team_cta_title', 'Book time with the right specialist');
        $teamCtaSubtitle = get_setting(
            'team_cta_subtitle',
            'Choose from psychology, homeopathy, and care coordination services—all in one place.'
        );
        $teamCtaPrimaryLabel = get_setting('team_cta_primary_label', 'View services');
        $teamCtaPrimaryLink = get_setting('team_cta_primary_link', route('services.index'));
        $teamCtaSecondaryLabel = get_setting('team_cta_secondary_label', 'Meet our team');
        $teamCtaSecondaryLink = get_setting('team_cta_secondary_link', route('team'));

        return view('team', compact(
            'leadership',
            'careTeams',
            'teamHeroTitle',
            'teamHeroSubtitle',
            'teamCtaTitle',
            'teamCtaSubtitle',
            'teamCtaPrimaryLabel',
            'teamCtaPrimaryLink',
            'teamCtaSecondaryLabel',
            'teamCtaSecondaryLink'
        ));
    }
}

