<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@clinic.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('Admin user created: admin@clinic.com / password');

        // Create patient user
        $patientUser = User::updateOrCreate(
            ['email' => 'patient@clinic.com'],
            [
                'name' => 'Patient User',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'email_verified_at' => now(),
            ]
        );
        Patient::updateOrCreate(
            ['user_id' => $patientUser->id],
            [
                'first_name' => 'Patient',
                'last_name' => 'User',
                'email' => $patientUser->email,
                'phone' => '111-222-3333',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
            ]
        );
        $this->command->info('Patient user created: patient@clinic.com / password');

        // Create doctor user
        $doctorUser = User::updateOrCreate(
            ['email' => 'doctor@clinic.com'],
            [
                'name' => 'Doctor User',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'email_verified_at' => now(),
            ]
        );
        Doctor::updateOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'first_name' => 'Doctor',
                'last_name' => 'User',
                'email' => $doctorUser->email,
                'phone' => '444-555-6666',
                'specialization' => 'General Practice',
                'qualification' => 'MD',
                'type' => 'general',
            ]
        );
        $this->command->info('Doctor user created: doctor@clinic.com / password');

        // Create staff user
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@clinic.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );
        Staff::updateOrCreate(
            ['user_id' => $staffUser->id],
            [
                'first_name' => 'Staff',
                'last_name' => 'User',
                'phone' => '777-888-9999',
                'position' => 'Receptionist',
                'department' => 'Admin',
                'hire_date' => '2023-01-01',
            ]
        );
        $this->command->info('Staff user created: staff@clinic.com / password');

        // Create additional test patients
        for ($i = 1; $i <= 3; $i++) {
            $pUser = User::updateOrCreate(
                ['email' => "patient{$i}@clinic.com"],
                [
                    'name' => "Patient {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'patient',
                    'email_verified_at' => now(),
                ]
            );
            Patient::updateOrCreate(
                ['user_id' => $pUser->id],
                [
                    'first_name' => "Patient",
                    'last_name' => "{$i}",
                    'email' => $pUser->email,
                    'phone' => "111-222-333" . $i,
                    'date_of_birth' => now()->subYears(20 + $i)->format('Y-m-d'),
                    'gender' => $i % 2 == 0 ? 'female' : 'male',
                ]
            );
        }
        $this->command->info('Additional test patients created (patient1@clinic.com to patient3@clinic.com)');

        // Create additional test doctors
        for ($i = 1; $i <= 2; $i++) {
            $dUser = User::updateOrCreate(
                ['email' => "doctor{$i}@clinic.com"],
                [
                    'name' => "Doctor {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'doctor',
                    'email_verified_at' => now(),
                ]
            );
            Doctor::updateOrCreate(
                ['user_id' => $dUser->id],
                [
                    'first_name' => "Doctor",
                    'last_name' => "{$i}",
                    'email' => $dUser->email,
                    'phone' => "444-555-666" . $i,
                    'specialization' => $i % 2 == 0 ? 'Psychology' : 'Homeopathy',
                    'qualification' => 'MD',
                    'type' => $i % 2 == 0 ? 'psychology' : 'homeopathy',
                ]
            );
        }
        $this->command->info('Additional test doctors created (doctor1@clinic.com to doctor2@clinic.com)');

        // Create additional test staff
        for ($i = 1; $i <= 2; $i++) {
            $sUser = User::updateOrCreate(
                ['email' => "staff{$i}@clinic.com"],
                [
                    'name' => "Staff {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'email_verified_at' => now(),
                ]
            );
            Staff::updateOrCreate(
                ['user_id' => $sUser->id],
                [
                    'first_name' => "Staff",
                    'last_name' => "{$i}",
                    'phone' => "777-888-999" . $i,
                    'position' => $i % 2 == 0 ? 'Nurse' : 'Administrator',
                    'department' => $i % 2 == 0 ? 'Medical' : 'Admin',
                    'hire_date' => now()->subMonths($i)->format('Y-m-d'),
                ]
            );
        }
        $this->command->info('Additional test staff created (staff1@clinic.com to staff2@clinic.com)');
    }
}

