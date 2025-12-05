<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentConflictTest extends TestCase
{
    use RefreshDatabase;

    public function test_detects_conflict_for_same_doctor_or_patient(): void
    {
        $doctorUser = User::create([
            'name' => 'Dr. Who',
            'email' => 'doctor@example.com',
            'password' => 'secret',
            'role' => 'doctor',
        ]);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'first_name' => 'Dr',
            'last_name' => 'Who',
            'email' => 'doctor@example.com',
            'type' => 'general',
        ]);

        $patientUser = User::create([
            'name' => 'Pat One',
            'email' => 'patient@example.com',
            'password' => 'secret',
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'first_name' => 'Pat',
            'last_name' => 'One',
            'email' => 'patient@example.com',
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => '2025-01-01',
            'appointment_time' => '10:00:00',
            'status' => 'scheduled',
        ]);

        $this->assertTrue(Appointment::hasConflict($doctor->id, null, '2025-01-01', '10:00:00'));
        $this->assertTrue(Appointment::hasConflict(null, $patient->id, '2025-01-01', '10:00:00'));
        $this->assertFalse(Appointment::hasConflict($doctor->id, null, '2025-01-01', '11:00:00'));
    }
}

