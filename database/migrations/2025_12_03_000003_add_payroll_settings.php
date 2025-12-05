<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn('key', [
            'payroll_part_time_hourly_rate',
            'payroll_locum_commission_rate',
        ])->delete();
    }
};

