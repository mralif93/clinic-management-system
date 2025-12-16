<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\User;

class SearchController extends Controller
{
    /**
     * Global search endpoint
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 10);
        $types = $request->input('types', []); // ['patients', 'doctors', 'appointments']

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Search patients
        if (empty($types) || in_array('patients', $types)) {
            $patients = Patient::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('patient_id', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get()
            ->map(function($patient) {
                return [
                    'type' => 'patient',
                    'id' => $patient->id,
                    'title' => $patient->full_name,
                    'subtitle' => $patient->patient_id,
                    'url' => route('admin.patients.show', $patient->id),
                    'icon' => 'bx-user'
                ];
            });

            $results = array_merge($results, $patients->toArray());
        }

        // Search doctors
        if (empty($types) || in_array('doctors', $types)) {
            $doctors = Doctor::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('doctor_id', 'like', "%{$query}%")
                  ->orWhere('specialization', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get()
            ->map(function($doctor) {
                return [
                    'type' => 'doctor',
                    'id' => $doctor->id,
                    'title' => 'Dr. ' . $doctor->full_name,
                    'subtitle' => $doctor->doctor_id,
                    'url' => route('admin.doctors.show', $doctor->id),
                    'icon' => 'bx-plus-medical'
                ];
            });

            $results = array_merge($results, $doctors->toArray());
        }

        // Search appointments
        if (empty($types) || in_array('appointments', $types)) {
            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereHas('patient', function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->orWhere('id', 'like', "%{$query}%")
                ->limit($limit)
                ->get()
                ->map(function($appointment) {
                    return [
                        'type' => 'appointment',
                        'id' => $appointment->id,
                        'title' => 'Appointment #' . $appointment->id,
                        'subtitle' => $appointment->patient?->full_name . ' - ' . ($appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : ''),
                        'url' => route('admin.appointments.show', $appointment->id),
                        'icon' => 'bx-calendar-check'
                    ];
                });

            $results = array_merge($results, $appointments->toArray());
        }

        return response()->json([
            'results' => array_slice($results, 0, $limit),
            'total' => count($results)
        ]);
    }

    /**
     * Autocomplete endpoint
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        return $this->search($request);
    }
}

