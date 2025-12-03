<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['staff', 'doctor'])->get();

        if ($users->isEmpty()) {
            $this->command->info('Skipping TodoSeeder: No staff or doctors found.');
            return;
        }

        foreach ($users as $user) {
            // Create 3-5 tasks per user
            $count = rand(3, 5);
            for ($i = 0; $i < $count; $i++) {
                $dueDate = Carbon::now()->addDays(rand(-5, 10));
                $status = array_rand(Todo::getStatuses());
                $priority = array_rand(Todo::getPriorities());

                Todo::create([
                    'title' => 'Task ' . ($i + 1) . ' for ' . $user->name,
                    'description' => 'This is a generated task description.',
                    'status' => $status,
                    'priority' => $priority,
                    'due_date' => $dueDate->format('Y-m-d'),
                    'assigned_to' => $user->id,
                    'created_by' => 1, // Admin ID 1
                ]);
            }
        }

        $this->command->info('Todos seeded successfully.');
    }
}
