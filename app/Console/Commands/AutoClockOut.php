<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoClockOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-clock-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically clock out users who forgot to clock out';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto clock-out process...');

        // Find attendances from yesterday or earlier that are still clocked in
        $attendances = Attendance::whereNull('clock_out_time')
            ->whereDate('date', '<', today())
            ->get();

        $count = 0;

        foreach ($attendances as $attendance) {
            // Set clock out time to 11:59 PM of that day
            $clockOutTime = Carbon::parse($attendance->date->format('Y-m-d') . ' 23:59:59');

            $attendance->update([
                'clock_out_time' => $clockOutTime,
                'notes' => $attendance->notes . "\n[System] Auto clocked out at midnight.",
                'status' => 'half_day' // Mark as half day or flag for review? Let's keep it simple.
            ]);

            $attendance->updateTotalHours();
            $count++;
        }

        $this->info("Successfully auto clocked out {$count} users.");
    }
}
