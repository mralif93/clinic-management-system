<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Todo;
use Carbon\Carbon;

class GenerateRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'todos:generate-recurring';

    /**
     * The console command description.
     */
    protected $description = 'Generate new instances of recurring tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating recurring tasks...');

        $today = Carbon::today();
        $generated = 0;

        // Get all recurring tasks
        $recurringTasks = Todo::where('is_recurring', true)
            ->whereNotNull('recurrence_type')
            ->get();

        foreach ($recurringTasks as $task) {
            $shouldGenerate = false;

            // Check if we should generate based on recurrence type
            switch ($task->recurrence_type) {
                case 'daily':
                    // Generate if last_generated_date is null or not today
                    $shouldGenerate = !$task->last_generated_date ||
                        !$task->last_generated_date->isToday();
                    break;

                case 'weekly':
                    // Generate if last_generated_date is null or more than 7 days ago
                    $shouldGenerate = !$task->last_generated_date ||
                        $task->last_generated_date->diffInDays($today) >= 7;
                    break;

                case 'monthly':
                    // Generate if last_generated_date is null or different month
                    $shouldGenerate = !$task->last_generated_date ||
                        $task->last_generated_date->month != $today->month;
                    break;
            }

            if ($shouldGenerate) {
                // Create new task instance
                $newTask = $task->replicate();
                $newTask->status = 'pending';
                $newTask->is_recurring = false; // New instance is not recurring
                $newTask->recurrence_type = null;
                $newTask->last_generated_date = null;
                $newTask->created_at = now();
                $newTask->updated_at = now();
                $newTask->save();

                // Update last_generated_date on the template task
                $task->last_generated_date = $today;
                $task->save();

                $generated++;
                $this->line("Generated: {$task->title} (ID: {$newTask->id})");
            }
        }

        $this->info("Successfully generated {$generated} recurring task(s).");

        return Command::SUCCESS;
    }
}
