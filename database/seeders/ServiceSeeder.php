<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Psychology Services
            [
                'name' => 'Individual Therapy Session',
                'slug' => 'individual-therapy',
                'description' => 'One-on-one therapy session with a licensed psychologist to address personal mental health concerns, anxiety, depression, and emotional well-being.',
                'type' => 'psychology',
                'price' => 150.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Couples Counseling',
                'slug' => 'couples-counseling',
                'description' => 'Therapy session for couples to improve communication, resolve conflicts, and strengthen relationships.',
                'type' => 'psychology',
                'price' => 180.00,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Family Therapy',
                'slug' => 'family-therapy',
                'description' => 'Family therapy session to address family dynamics, improve relationships, and resolve conflicts.',
                'type' => 'psychology',
                'price' => 200.00,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Cognitive Behavioral Therapy (CBT)',
                'slug' => 'cognitive-behavioral-therapy',
                'description' => 'Evidence-based therapy focusing on changing negative thought patterns and behaviors.',
                'type' => 'psychology',
                'price' => 160.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Stress Management Session',
                'slug' => 'stress-management',
                'description' => 'Specialized session to learn coping strategies and techniques for managing stress and anxiety.',
                'type' => 'psychology',
                'price' => 140.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],

            // Homeopathy Services
            [
                'name' => 'Homeopathy Consultation',
                'slug' => 'homeopathy-consultation',
                'description' => 'Comprehensive homeopathic consultation to understand your health condition and create a personalized treatment plan.',
                'type' => 'homeopathy',
                'price' => 120.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Acute Homeopathy Treatment',
                'slug' => 'acute-homeopathy',
                'description' => 'Treatment for acute conditions like cold, flu, injuries, and sudden illnesses using homeopathic remedies.',
                'type' => 'homeopathy',
                'price' => 100.00,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Chronic Disease Homeopathy',
                'slug' => 'chronic-homeopathy',
                'description' => 'Long-term homeopathic treatment for chronic conditions like allergies, arthritis, and autoimmune disorders.',
                'type' => 'homeopathy',
                'price' => 150.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Pediatric Homeopathy',
                'slug' => 'pediatric-homeopathy',
                'description' => 'Specialized homeopathic treatment for children\'s health issues, behavioral problems, and developmental concerns.',
                'type' => 'homeopathy',
                'price' => 110.00,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Health Homeopathy',
                'slug' => 'womens-health-homeopathy',
                'description' => 'Homeopathic treatment for women\'s health issues including hormonal imbalances, menstrual problems, and menopause.',
                'type' => 'homeopathy',
                'price' => 130.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}

