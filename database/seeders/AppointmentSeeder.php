<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $services = Service::all();

        if ($doctors->isEmpty() || $patients->isEmpty() || $services->isEmpty()) {
            $this->command->info('Skipping AppointmentSeeder: Missing doctors, patients, or services.');
            return;
        }

        // Create past appointments (completed)
        foreach ($patients as $patient) {
            // 3-5 past appointments per patient
            $count = rand(3, 5);
            for ($i = 0; $i < $count; $i++) {
                $doctor = $doctors->random();
                $service = $services->random();
                $date = Carbon::now()->subDays(rand(1, 60))->setTime(rand(9, 16), 0);

                Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'service_id' => $service->id,
                    'appointment_date' => $date->format('Y-m-d'),
                    'appointment_time' => $date->format('H:i:s'),
                    'status' => 'completed',
                    'notes' => 'Regular checkup completed.',
                    'diagnosis' => 'Patient is healthy.',
                    'prescription' => 'Vitamins',
                ]);
            }
        }

        // Create future appointments (scheduled/confirmed)
        foreach ($patients as $patient) {
            // 1-2 future appointments per patient
            $count = rand(1, 2);
            for ($i = 0; $i < $count; $i++) {
                $doctor = $doctors->random();
                $service = $services->random();
                $date = Carbon::now()->addDays(rand(1, 14))->setTime(rand(9, 16), 0);
                $status = rand(0, 1) ? 'scheduled' : 'confirmed';

                Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'service_id' => $service->id,
                    'appointment_date' => $date->format('Y-m-d'),
                    'appointment_time' => $date->format('H:i:s'),
                    'status' => $status,
                    'notes' => 'Follow-up appointment.',
                ]);
            }
        }

        $this->command->info('Appointments seeded successfully.');
    }
}
