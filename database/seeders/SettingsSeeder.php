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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

