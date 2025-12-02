<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Check if doctor already has schedules
            if ($doctor->schedules()->count() > 0) {
                continue;
            }

            // Create default schedules for all days
            for ($day = 0; $day <= 6; $day++) {
                // Monday to Friday are active by default
                $isActive = ($day >= 1 && $day <= 5);

                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'is_active' => $isActive,
                    'start_time' => '09:00',
                    'break_start' => '12:00',
                    'break_end' => '13:00',
                    'end_time' => '17:00',
                    'slot_duration' => 30,
                ]);
            }
        }

        $this->command->info('Doctor schedules seeded successfully!');
    }
}
