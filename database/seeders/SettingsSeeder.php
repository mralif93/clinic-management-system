<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'clinic_logo',
                'value' => '',
                'type' => 'file',
                'group' => 'general',
                'description' => 'Clinic logo image',
            ],
            [
                'key' => 'clinic_name',
                'value' => 'Clinic Management System',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Name of the clinic',
            ],
            [
                'key' => 'clinic_email',
                'value' => 'info@clinic.com',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Main contact email',
            ],
            [
                'key' => 'clinic_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Contact phone number',
            ],
            [
                'key' => 'clinic_address',
                'value' => '123 Medical Street, Health City, HC 12345',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Clinic physical address',
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'type' => 'text',
                'group' => 'general',
                'description' => 'System timezone',
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Date format (e.g., Y-m-d, d/m/Y)',
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Time format (e.g., H:i, h:i A)',
            ],

            // Payment Settings
            [
                'key' => 'currency',
                'value' => 'USD',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'Default currency code (USD, EUR, GBP, etc.)',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'Currency symbol',
            ],
            [
                'key' => 'currency_position',
                'value' => 'before',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'Currency position (before or after)',
            ],
            [
                'key' => 'tax_rate',
                'value' => '0',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'Tax rate percentage',
            ],
            [
                'key' => 'payment_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Enable online payments',
            ],
            [
                'key' => 'payment_method',
                'value' => 'cash',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'Default payment method',
            ],

            // Payroll Settings
            [
                'key' => 'payroll_part_time_hourly_rate',
                'value' => '8.00',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Default hourly rate for part-time employees (RM/hour)',
            ],
            [
                'key' => 'payroll_locum_commission_rate',
                'value' => '60.00',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Default commission rate for locum doctors (%)',
            ],
            [
                'key' => 'payroll_epf_employee_rate',
                'value' => '11',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'EPF employee contribution rate (%)',
            ],
            [
                'key' => 'payroll_epf_employer_rate',
                'value' => '13',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'EPF employer contribution rate (%)',
            ],
            [
                'key' => 'payroll_socso_employee_rate',
                'value' => '0.5',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'SOCSO employee contribution rate (%)',
            ],
            [
                'key' => 'payroll_socso_employer_rate',
                'value' => '1.75',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'SOCSO employer contribution rate (%)',
            ],
            [
                'key' => 'payroll_eis_employee_rate',
                'value' => '0.2',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'EIS employee contribution rate (%)',
            ],
            [
                'key' => 'payroll_eis_employer_rate',
                'value' => '0.2',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'EIS employer contribution rate (%)',
            ],
            [
                'key' => 'payroll_tax_rate',
                'value' => '0',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Income tax rate (%)',
            ],

            // Email Settings
            [
                'key' => 'email_from_name',
                'value' => 'Clinic Management System',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Email sender name',
            ],
            [
                'key' => 'email_from_address',
                'value' => 'noreply@clinic.com',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Email sender address',
            ],
            [
                'key' => 'appointment_reminder',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Send appointment reminders',
            ],

            // Landing Page Settings
            [
                'key' => 'hero_title_line1',
                'value' => 'Modern Clinic',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Hero section title line 1',
            ],
            [
                'key' => 'hero_title_line2',
                'value' => 'Management',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Hero section title line 2 (highlighted)',
            ],
            [
                'key' => 'hero_title_line3',
                'value' => 'System',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Hero section title line 3',
            ],
            [
                'key' => 'hero_description',
                'value' => 'Streamline your clinic operations with our comprehensive management solution. Manage patients, appointments, and staff all in one place.',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Hero section description',
            ],
            [
                'key' => 'cta_title',
                'value' => 'Ready to Get Started?',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Call to action title',
            ],
            [
                'key' => 'cta_description',
                'value' => 'Join thousands of clinics already using our management system',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Call to action description',
            ],
            [
                'key' => 'footer_description',
                'value' => 'Modern clinic management solution for healthcare professionals.',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Footer description',
            ],
            [
                'key' => 'stat_secure',
                'value' => '100%',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Secure value',
            ],
            [
                'key' => 'stat_secure_label',
                'value' => 'Secure',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Secure label',
            ],
            [
                'key' => 'stat_support',
                'value' => '24/7',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Support value',
            ],
            [
                'key' => 'stat_support_label',
                'value' => 'Support',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Support label',
            ],
            [
                'key' => 'stat_users',
                'value' => '1000+',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Users value',
            ],
            [
                'key' => 'stat_users_label',
                'value' => 'Users',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Users label',
            ],
            [
                'key' => 'stat_uptime',
                'value' => '99.9%',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Uptime value',
            ],
            [
                'key' => 'stat_uptime_label',
                'value' => 'Uptime',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Statistics - Uptime label',
            ],

            // About Us Page Settings
            [
                'key' => 'about_hero_title',
                'value' => 'Sakeenah Medicare (SKMED)',
                'type' => 'text',
                'group' => 'pages',
                'description' => 'About Us - Hero title',
            ],
            [
                'key' => 'about_hero_subtitle',
                'value' => 'We provide high-quality mental health and wellness services while advocating for mental health awareness in Malaysia.',
                'type' => 'textarea',
                'group' => 'pages',
                'description' => 'About Us - Hero subtitle',
            ],
            [
                'key' => 'about_story_short',
                'value' => 'At SKMED, quality over quantity is our priority. We ensure that every client receives personalized, high-quality care at an affordable rate. Our team supports individuals facing mental health challenges, life transitions, or emotional struggles with hope, guidance, and expert care.',
                'type' => 'textarea',
                'group' => 'pages',
                'description' => 'About Us - Short story',
            ],
            [
                'key' => 'about_story_long',
                'value' => 'At Sakeenah Medicare (SKMED), we are committed to providing high-quality mental health and wellness services while advocating for mental health awareness in Malaysia. We strive to create a safe, supportive environment for emotional well-being, healing, and growth. Our certified clinical psychologists, counsellors, occupational therapists, and homeopathy practitioners deliver care through psychotherapy, assessments, screenings, counseling, holistic wellness, and trainee supervision for future professionals.',
                'type' => 'textarea',
                'group' => 'pages',
                'description' => 'About Us - Long story',
            ],
            [
                'key' => 'about_vision',
                'value' => 'To be a leading mental health and wellness center in Malaysia, breaking the stigma and providing compassionate, high-quality, and accessible care.',
                'type' => 'textarea',
                'group' => 'pages',
                'description' => 'About Us - Vision statement',
            ],
            [
                'key' => 'about_mission_items',
                'value' => json_encode([
                    'Advocating for mental health awareness and reducing stigma.',
                    'Providing expert yet affordable care for all.',
                    'Empowering individuals towards healing and growth.',
                    'Training future mental health professionals with excellence.',
                ]),
                'type' => 'json',
                'group' => 'pages',
                'description' => 'About Us - Mission items',
            ],
            [
                'key' => 'about_values',
                'value' => json_encode([
                    [
                        'icon' => 'bx-heart',
                        'title' => 'Care & Compassion',
                        'description' => 'We prioritize kindness, empathy, and understanding in everything we do.',
                        'accent' => 'text-rose-600 bg-rose-50',
                    ],
                    [
                        'icon' => 'bx-shield',
                        'title' => 'Excellence',
                        'description' => 'We provide the highest quality care through expertise and innovation.',
                        'accent' => 'text-indigo-600 bg-indigo-50',
                    ],
                    [
                        'icon' => 'bx-megaphone',
                        'title' => 'Advocacy',
                        'description' => 'We actively fight against mental health stigma through education and support.',
                        'accent' => 'text-amber-600 bg-amber-50',
                    ],
                ]),
                'type' => 'json',
                'group' => 'pages',
                'description' => 'About Us - Values',
            ],
            [
                'key' => 'about_values_description',
                'value' => 'Guided by compassion, excellence, and advocacy to serve our community.',
                'type' => 'textarea',
                'group' => 'pages',
                'description' => 'About Us - Values section description',
            ],

            // Team Page Settings
            [
                'key' => 'team_members',
                'value' => json_encode([
                    [
                        'name' => 'Sarah',
                        'title' => 'Senior Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Experienced in trauma-informed therapy and evidence-based interventions. Specializes in anxiety, depression, and relationship counseling.',
                    ],
                    [
                        'name' => 'Qistina',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Dedicated to providing compassionate mental health care. Focuses on cognitive-behavioral therapy and mindfulness-based approaches.',
                    ],
                    [
                        'name' => 'Andrea',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Expert in child and adolescent psychology. Passionate about supporting young people through life transitions and challenges.',
                    ],
                    [
                        'name' => 'Adrianna',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Specializes in family therapy and couples counseling. Committed to helping individuals build stronger, healthier relationships.',
                    ],
                    [
                        'name' => 'Farhah',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Focuses on stress management and work-life balance. Helps clients develop effective coping strategies for daily challenges.',
                    ],
                    [
                        'name' => 'Marjan',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Expert in mood disorders and emotional regulation. Uses integrative approaches to support clients\' mental wellness journey.',
                    ],
                    [
                        'name' => 'Aina',
                        'title' => 'Clinical Psychologist/Counsellor',
                        'photo' => null,
                        'bio' => 'Combines clinical psychology expertise with counseling skills. Specializes in grief counseling and life transition support.',
                    ],
                    [
                        'name' => 'Alya',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Dedicated to mental health advocacy and stigma reduction. Provides culturally sensitive therapy for diverse populations.',
                    ],
                    [
                        'name' => 'Kelly',
                        'title' => 'Clinical Psychologist',
                        'photo' => null,
                        'bio' => 'Specializes in assessment and diagnosis. Experienced in working with adults facing various mental health challenges.',
                    ],
                    [
                        'name' => 'Adeera',
                        'title' => 'Counselor',
                        'photo' => null,
                        'bio' => 'Provides supportive counseling for individuals navigating personal and professional challenges. Warm and empathetic approach.',
                    ],
                    [
                        'name' => 'Izzam',
                        'title' => 'Counselor',
                        'photo' => null,
                        'bio' => 'Experienced counselor specializing in men\'s mental health and career counseling. Helps clients achieve personal and professional goals.',
                    ],
                    [
                        'name' => 'Hannan',
                        'title' => 'Counselor',
                        'photo' => null,
                        'bio' => 'Compassionate counselor focused on supporting individuals through difficult times. Specializes in self-esteem and personal growth.',
                    ],
                ]),
                'type' => 'json',
                'group' => 'pages',
                'description' => 'Team - Team members list',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

