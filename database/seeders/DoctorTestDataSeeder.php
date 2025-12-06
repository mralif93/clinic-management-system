<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Todo;
use Carbon\Carbon;

class DoctorTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds to create test data for doctor portal.
     */
    public function run(): void
    {
        // Get doctor1 user
        $doctorUser = User::where('email', 'doctor1@clinic.com')->first();
        
        if (!$doctorUser) {
            $this->command->error('Doctor user not found. Please run UserSeeder first.');
            return;
        }

        $doctor = Doctor::where('user_id', $doctorUser->id)->first();
        
        if (!$doctor) {
            $this->command->error('Doctor profile not found.');
            return;
        }

        $this->command->info("Creating test data for Dr. {$doctor->full_name}...");

        // Get existing patients and services
        $patients = Patient::all();
        $services = Service::all();

        if ($patients->isEmpty() || $services->isEmpty()) {
            $this->command->error('Please run UserSeeder and ServiceSeeder first.');
            return;
        }

        // 1. Create TODAY's appointments for the doctor
        $this->command->info('Creating today\'s appointments...');
        $times = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00'];
        $statuses = ['scheduled', 'confirmed', 'in_progress', 'completed', 'scheduled', 'scheduled'];
        
        foreach ($times as $index => $time) {
            $patient = $patients->random();
            $service = $services->random();
            $status = $statuses[$index % count($statuses)];
            
            Appointment::updateOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'appointment_date' => today()->format('Y-m-d'),
                    'appointment_time' => $time,
                ],
                [
                    'patient_id' => $patient->id,
                    'service_id' => $service->id,
                    'status' => $status,
                    'notes' => $status === 'completed' ? 'Consultation completed successfully.' : 'Regular appointment.',
                    'diagnosis' => $status === 'completed' ? 'Patient in good health.' : null,
                    'prescription' => $status === 'completed' ? 'Multivitamins for 30 days' : null,
                    'fee' => $service->price,
                    'discount_type' => rand(0, 1) ? 'percentage' : null,
                    'discount_value' => rand(0, 1) ? rand(5, 15) : null,
                    'payment_status' => $status === 'completed' ? 'paid' : 'unpaid',
                    'payment_method' => $status === 'completed' ? ['cash', 'card', 'online'][rand(0, 2)] : null,
                ]
            );
        }

        // 2. Create upcoming appointments (next 7 days)
        $this->command->info('Creating upcoming appointments...');
        for ($day = 1; $day <= 7; $day++) {
            $appointmentCount = rand(3, 6);
            for ($i = 0; $i < $appointmentCount; $i++) {
                $hour = rand(9, 16);
                $minute = rand(0, 1) ? '00' : '30';
                
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patients->random()->id,
                    'service_id' => $services->random()->id,
                    'appointment_date' => today()->addDays($day)->format('Y-m-d'),
                    'appointment_time' => "{$hour}:{$minute}",
                    'status' => rand(0, 1) ? 'scheduled' : 'confirmed',
                    'notes' => 'Follow-up appointment',
                    'fee' => $services->random()->price,
                ]);
            }
        }

        // 3. Create past appointments (last 30 days)
        $this->command->info('Creating past appointments...');
        for ($day = 1; $day <= 30; $day++) {
            $appointmentCount = rand(4, 8);
            for ($i = 0; $i < $appointmentCount; $i++) {
                $hour = rand(9, 16);
                $minute = rand(0, 1) ? '00' : '30';
                $service = $services->random();
                
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patients->random()->id,
                    'service_id' => $service->id,
                    'appointment_date' => today()->subDays($day)->format('Y-m-d'),
                    'appointment_time' => "{$hour}:{$minute}",
                    'status' => 'completed',
                    'notes' => 'Consultation completed.',
                    'diagnosis' => 'Patient consultation notes here.',
                    'prescription' => 'Prescribed medications as needed.',
                    'fee' => $service->price,
                    'payment_status' => 'paid',
                    'payment_method' => ['cash', 'card', 'online'][rand(0, 2)],
                ]);
            }
        }

        // 4. Create attendance records
        $this->createAttendance($doctorUser);

        // 5. Create todos
        $this->createTodos($doctorUser);

        // 6. Create leaves
        $this->createLeaves($doctorUser);

        // 7. Create payrolls
        $this->createPayrolls($doctorUser, $doctor);

        $this->command->info('✅ Doctor test data created successfully!');
    }

    private function createAttendance(User $user): void
    {
        $this->command->info('Creating attendance records...');

        // Restore any soft-deleted records for this user (unique constraint issue)
        Attendance::withTrashed()
            ->where('user_id', $user->id)
            ->whereNotNull('deleted_at')
            ->restore();

        // Create attendance for last 30 days
        $created = 0;
        $skipped = 0;

        for ($day = 1; $day <= 30; $day++) {
            $date = today()->subDays($day);
            $dateStr = $date->format('Y-m-d');

            // Skip weekends
            if ($date->isWeekend()) continue;

            // Check if record exists (including soft-deleted)
            $existing = Attendance::withTrashed()
                ->where('user_id', $user->id)
                ->whereDate('date', $dateStr)
                ->exists();

            if ($existing) {
                $skipped++;
                continue;
            }

            $rand = rand(1, 100);

            if ($rand <= 85) {
                // Present (85%)
                $clockIn = $date->copy()->setTime(rand(8, 9), rand(0, 14));
                $clockOut = $date->copy()->setTime(rand(17, 18), rand(0, 59));
                $status = 'present';
            } elseif ($rand <= 95) {
                // Late (10%)
                $clockIn = $date->copy()->setTime(9, rand(16, 45));
                $clockOut = $date->copy()->setTime(rand(17, 18), rand(0, 59));
                $status = 'late';
            } else {
                // Absent (5%)
                continue;
            }

            Attendance::create([
                'user_id' => $user->id,
                'date' => $dateStr,
                'clock_in_time' => $clockIn,
                'clock_out_time' => $clockOut,
                'status' => $status,
                'total_hours' => 8,
                'break_duration' => 60,
                'is_approved' => true,
            ]);
            $created++;
        }

        $this->command->info("  - Created {$created} attendance records, skipped {$skipped} existing");
    }

    private function createTodos(User $user): void
    {
        $this->command->info('Creating todos...');

        // Clear existing todos for this user
        Todo::where('assigned_to', $user->id)->delete();

        $tasks = [
            ['title' => 'Review patient reports', 'priority' => 'high', 'status' => 'pending'],
            ['title' => 'Update medical certificates', 'priority' => 'medium', 'status' => 'in_progress'],
            ['title' => 'Complete training module', 'priority' => 'low', 'status' => 'pending'],
            ['title' => 'Submit monthly report', 'priority' => 'high', 'status' => 'completed'],
            ['title' => 'Attend staff meeting', 'priority' => 'medium', 'status' => 'pending'],
            ['title' => 'Review prescription requests', 'priority' => 'high', 'status' => 'pending'],
            ['title' => 'Update availability schedule', 'priority' => 'medium', 'status' => 'completed'],
            ['title' => 'Follow up with referred patients', 'priority' => 'high', 'status' => 'in_progress'],
        ];

        foreach ($tasks as $index => $task) {
            Todo::create([
                'title' => $task['title'],
                'description' => 'Task description for: ' . $task['title'],
                'status' => $task['status'],
                'priority' => $task['priority'],
                'due_date' => today()->addDays(rand(-3, 10))->format('Y-m-d'),
                'assigned_to' => $user->id,
                'created_by' => 1,
            ]);
        }
    }

    private function createLeaves(User $user): void
    {
        $this->command->info('Creating leave records...');

        // Clear existing leaves for this user
        Leave::where('user_id', $user->id)->delete();

        $leaveTypes = ['annual', 'sick', 'emergency', 'unpaid'];
        $statuses = ['pending', 'approved', 'rejected', 'approved'];

        // Past approved leave
        Leave::create([
            'user_id' => $user->id,
            'leave_type' => 'annual',
            'start_date' => today()->subDays(45)->format('Y-m-d'),
            'end_date' => today()->subDays(43)->format('Y-m-d'),
            'total_days' => 3,
            'reason' => 'Family vacation',
            'status' => 'approved',
            'reviewed_by' => 1,
            'reviewed_at' => today()->subDays(50),
        ]);

        // Recent sick leave
        Leave::create([
            'user_id' => $user->id,
            'leave_type' => 'sick',
            'start_date' => today()->subDays(10)->format('Y-m-d'),
            'end_date' => today()->subDays(9)->format('Y-m-d'),
            'total_days' => 2,
            'reason' => 'Medical appointment',
            'status' => 'approved',
            'reviewed_by' => 1,
            'reviewed_at' => today()->subDays(12),
        ]);

        // Pending leave request
        Leave::create([
            'user_id' => $user->id,
            'leave_type' => 'annual',
            'start_date' => today()->addDays(14)->format('Y-m-d'),
            'end_date' => today()->addDays(16)->format('Y-m-d'),
            'total_days' => 3,
            'reason' => 'Personal leave for family event',
            'status' => 'pending',
        ]);

        // Rejected leave
        Leave::create([
            'user_id' => $user->id,
            'leave_type' => 'emergency',
            'start_date' => today()->subDays(20)->format('Y-m-d'),
            'end_date' => today()->subDays(20)->format('Y-m-d'),
            'total_days' => 1,
            'reason' => 'Emergency situation',
            'status' => 'rejected',
            'reviewed_by' => 1,
            'reviewed_at' => today()->subDays(21),
        ]);
    }

    private function createPayrolls(User $user, Doctor $doctor): void
    {
        $this->command->info('Creating payroll records...');

        // Clear existing payrolls for this user
        Payroll::where('user_id', $user->id)->delete();

        // Create payrolls for last 3 months
        for ($month = 1; $month <= 3; $month++) {
            $date = today()->subMonths($month);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $basicSalary = 5000;
            $allowances = [
                'Transport' => 200,
                'Medical' => 150,
                'Professional' => 300,
            ];
            $deductions = [
                'Tax' => 500,
                'EPF' => 550,
                'SOCSO' => 50,
            ];

            $totalAllowances = array_sum($allowances);
            $totalDeductions = array_sum($deductions);
            $grossSalary = $basicSalary + $totalAllowances;
            $netSalary = $grossSalary - $totalDeductions;

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
                'generated_by' => 1,
                'approved_by' => 1,
                'approved_at' => $end->copy()->addDays(3),
            ]);
        }
    }
}

