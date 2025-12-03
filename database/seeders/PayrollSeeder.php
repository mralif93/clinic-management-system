<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['staff', 'doctor'])->get();

        if ($users->isEmpty()) {
            $this->command->info('Skipping PayrollSeeder: No staff or doctors found.');
            return;
        }

        $lastMonth = Carbon::now()->subMonth();
        $start = $lastMonth->copy()->startOfMonth();
        $end = $lastMonth->copy()->endOfMonth();

        foreach ($users as $user) {
            // Basic salary based on role
            $basicSalary = $user->role === 'doctor' ? 5000 : 2500;

            // Random allowances
            $allowances = [
                'Transport' => rand(100, 300),
                'Medical' => rand(50, 200),
            ];

            // Random deductions
            $deductions = [
                'Tax' => $basicSalary * 0.1,
                'Insurance' => 50,
            ];

            $totalAllowances = array_sum($allowances);
            $totalDeductions = array_sum($deductions);
            $grossSalary = $basicSalary + $totalAllowances;
            $netSalary = $grossSalary - $totalDeductions;

            // Clean up existing payroll for this user
            Payroll::where('user_id', $user->id)->forceDelete();

            Payroll::create([
                'user_id' => $user->id,
                'pay_period_start' => $start->format('Y-m-d'),
                'pay_period_end' => $end->format('Y-m-d'),
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'status' => 'paid',
                'payment_date' => $end->copy()->addDays(5)->format('Y-m-d'),
                'payment_method' => 'bank_transfer',
                'generated_by' => 1, // Admin ID 1
                'approved_by' => 1,
                'approved_at' => $end->copy()->addDays(1),
            ]);
        }

        $this->command->info('Payrolls seeded successfully.');
    }
}
