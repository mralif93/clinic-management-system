<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\Attendance;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PayrollTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test users for payroll system...');

        // 1. Create Full-Time Staff
        $fullTimeUser = User::firstOrCreate(
            ['email' => 'john.fulltime@test.com'],
            [
                'name' => 'John Fulltime',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'employment_type' => 'full_time',
                'basic_salary' => 3000.00,
            ]
        );

        // Update employment fields if user already exists
        $fullTimeUser->update([
            'employment_type' => 'full_time',
            'basic_salary' => 3000.00,
        ]);

        Staff::firstOrCreate(
            ['user_id' => $fullTimeUser->id],
            [
                'first_name' => 'John',
                'last_name' => 'Fulltime',
                'phone' => '0123456789',
                'position' => 'Receptionist',
            ]
        );

        $this->command->info('✅ Created full-time staff: John Fulltime (RM 3000/month)');

        // 2. Create Part-Time Staff
        $partTimeUser = User::firstOrCreate(
            ['email' => 'jane.parttime@test.com'],
            [
                'name' => 'Jane Parttime',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'employment_type' => 'part_time',
                'hourly_rate' => 8.00,
            ]
        );

        // Update employment fields if user already exists
        $partTimeUser->update([
            'employment_type' => 'part_time',
            'hourly_rate' => 8.00,
        ]);

        Staff::firstOrCreate(
            ['user_id' => $partTimeUser->id],
            [
                'first_name' => 'Jane',
                'last_name' => 'Parttime',
                'phone' => '0123456788',
                'position' => 'Assistant',
            ]
        );

        $this->command->info('✅ Created part-time staff: Jane Parttime (RM 8/hour)');

        // 3. Create Locum Doctor
        $locumUser = User::firstOrCreate(
            ['email' => 'mike.locum@test.com'],
            [
                'name' => 'Dr. Mike Locum',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'employment_type' => 'locum',
            ]
        );

        // Update employment fields if user already exists
        $locumUser->update([
            'employment_type' => 'locum',
        ]);

        $locumDoctor = Doctor::firstOrCreate(
            ['email' => 'mike.locum@test.com'],
            [
                'user_id' => $locumUser->id,
                'first_name' => 'Mike',
                'last_name' => 'Locum',
                'phone' => '0123456787',
                'specialization' => 'General Practice',
                'qualification' => 'MBBS',
                'type' => 'general',
                'commission_rate' => 60.00,
                'is_available' => true,
            ]
        );

        // Update commission rate if doctor already exists
        $locumDoctor->update([
            'commission_rate' => 60.00,
        ]);

        $this->command->info('✅ Created locum doctor: Dr. Mike Locum (60% commission)');

        // 4. Create attendance records for part-time staff (last month)
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();
        $totalHours = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekday()) { // Monday to Friday
                $hours = 8; // 8 hours per day
                Attendance::create([
                    'user_id' => $partTimeUser->id,
                    'date' => $date->format('Y-m-d'),
                    'clock_in_time' => $date->copy()->setTime(9, 0, 0),
                    'clock_out_time' => $date->copy()->setTime(17, 0, 0),
                    'total_hours' => $hours,
                    'status' => 'present',
                    'is_approved' => true,
                    'approved_by' => 1, // Assuming admin user ID is 1
                    'approved_at' => now(),
                ]);
                $totalHours += $hours;
            }
        }

        $this->command->info("✅ Created {$totalHours} hours of attendance for Jane Parttime");

        // 5. Create test patient for appointments
        $testPatient = Patient::firstOrCreate(
            ['email' => 'test.patient@test.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'Patient',
                'phone' => '0123456786',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'address' => 'Test Address',
            ]
        );

        // 6. Get or create a service
        $service = Service::first();
        if (!$service) {
            $service = Service::create([
                'name' => 'General Consultation',
                'description' => 'General medical consultation',
                'price' => 100.00,
                'duration' => 30,
            ]);
        }

        // 7. Create appointments for locum doctor (last month)
        $appointmentCount = 0;
        $totalFees = 0;

        for ($i = 0; $i < 15; $i++) {
            $appointmentDate = $startDate->copy()->addDays($i);
            if ($appointmentDate->isWeekday()) {
                $fee = rand(80, 150);
                Appointment::create([
                    'patient_id' => $testPatient->id,
                    'doctor_id' => $locumDoctor->id,
                    'service_id' => $service->id,
                    'appointment_date' => $appointmentDate->format('Y-m-d'),
                    'appointment_time' => '10:00:00',
                    'status' => 'completed',
                    'fee' => $fee,
                    'notes' => 'Test appointment for payroll calculation',
                ]);
                $appointmentCount++;
                $totalFees += $fee;
            }
        }

        $this->command->info("✅ Created {$appointmentCount} completed appointments for Dr. Mike Locum (Total fees: RM {$totalFees})");

        $this->command->info("\n📊 Test Data Summary:");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("1. Full-Time Staff: John Fulltime");
        $this->command->info("   Email: john.fulltime@test.com | Password: password");
        $this->command->info("   Expected Salary: RM 3,000.00");
        $this->command->info("");
        $this->command->info("2. Part-Time Staff: Jane Parttime");
        $this->command->info("   Email: jane.parttime@test.com | Password: password");
        $this->command->info("   Expected Salary: {$totalHours} hours × RM 8 = RM " . number_format($totalHours * 8, 2));
        $this->command->info("");
        $this->command->info("3. Locum Doctor: Dr. Mike Locum");
        $this->command->info("   Email: mike.locum@test.com | Password: password");
        $this->command->info("   Expected Salary: RM {$totalFees} × 60% = RM " . number_format($totalFees * 0.6, 2));
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }
}

