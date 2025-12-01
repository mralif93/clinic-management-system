<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class PayrollSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // EPF (KWSP)
            [
                'key' => 'payroll_epf_employee_rate',
                'value' => '11',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Employee EPF contribution rate (%)',
            ],
            [
                'key' => 'payroll_epf_employer_rate',
                'value' => '13',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Employer EPF contribution rate (%)',
            ],

            // SOCSO (PERKESO)
            [
                'key' => 'payroll_socso_employee_rate',
                'value' => '0.5',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Employee SOCSO contribution rate (%)',
            ],
            [
                'key' => 'payroll_socso_employer_rate',
                'value' => '1.75',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Employer SOCSO contribution rate (%)',
            ],

            // EIS (SIP)
            [
                'key' => 'payroll_eis_employee_rate',
                'value' => '0.2',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Employee EIS contribution rate (%)',
            ],
            [
                'key' => 'payroll_eis_employer_rate',
                'value' => '0.2',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Employer EIS contribution rate (%)',
            ],

            // Tax (PCB) - Simplified flat rate for now
            [
                'key' => 'payroll_tax_rate',
                'value' => '0',
                'type' => 'number',
                'group' => 'payroll',
                'description' => 'Default Tax/PCB rate (%)',
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
