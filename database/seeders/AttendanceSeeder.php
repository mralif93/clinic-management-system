<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['staff', 'doctor'])->get();

        if ($users->isEmpty()) {
            $this->command->info('Skipping AttendanceSeeder: No staff or doctors found.');
            return;
        }

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        foreach ($users as $user) {
            $currentDate = $startDate->copy();

            // Clean up existing attendance for this user to avoid duplicates
            Attendance::where('user_id', $user->id)->forceDelete();

            while ($currentDate <= $endDate) {
                // Skip weekends
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // 80% chance of being present, 10% late, 10% absent/leave
                $rand = rand(1, 100);

                if ($rand <= 80) {
                    // Present
                    $clockIn = $currentDate->copy()->setTime(rand(8, 9), rand(0, 59));
                    $clockOut = $currentDate->copy()->setTime(rand(17, 18), rand(0, 59));

                    Attendance::create(
                        [
                            'user_id' => $user->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'clock_in_time' => $clockIn,
                            'clock_out_time' => $clockOut,
                            'status' => 'present',
                            'total_hours' => 8.5, // Simplified
                            'break_duration' => 60,
                            'is_approved' => true,
                        ]
                    );
                } elseif ($rand <= 90) {
                    // Late
                    $clockIn = $currentDate->copy()->setTime(rand(9, 10), rand(15, 59)); // After 9:15
                    $clockOut = $currentDate->copy()->setTime(rand(17, 18), rand(0, 59));

                    Attendance::create(
                        [
                            'user_id' => $user->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'clock_in_time' => $clockIn,
                            'clock_out_time' => $clockOut,
                            'status' => 'late',
                            'total_hours' => 7.5, // Simplified
                            'break_duration' => 60,
                            'is_approved' => true,
                        ]
                    );
                } else {
                    // Absent or Leave (handled by LeaveSeeder, so maybe just mark absent here if no leave)
                    // For simplicity, let's just create an 'absent' record if we want, or skip
                    // Let's skip to avoid conflict with LeaveSeeder
                }

                $currentDate->addDay();
            }
        }

        $this->command->info('Attendance seeded successfully.');
    }
}
