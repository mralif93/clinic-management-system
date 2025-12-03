<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['staff', 'doctor'])->get();

        if ($users->isEmpty()) {
            $this->command->info('Skipping LeaveSeeder: No staff or doctors found.');
            return;
        }

        foreach ($users as $user) {
            // Create 1-3 leave requests per user
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $startDate = Carbon::now()->addDays(rand(-30, 30));
                $days = rand(1, 3);
                $endDate = $startDate->copy()->addDays($days - 1);

                $status = ['pending', 'approved', 'rejected'][rand(0, 2)];
                $type = array_rand(Leave::getLeaveTypes());

                Leave::create([
                    'user_id' => $user->id,
                    'leave_type' => $type,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'total_days' => $days,
                    'reason' => 'Personal matters',
                    'status' => $status,
                    'reviewed_by' => $status !== 'pending' ? 1 : null, // Admin ID 1
                    'reviewed_at' => $status !== 'pending' ? Carbon::now() : null,
                ]);
            }
        }

        $this->command->info('Leaves seeded successfully.');
    }
}
