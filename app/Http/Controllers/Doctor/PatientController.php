<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the doctor's patients
     */
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        // Get unique patients who have appointments with this doctor
        $patientIds = Appointment::where('doctor_id', $doctor->id)
            ->distinct()
            ->pluck('patient_id');

        $query = Patient::whereIn('id', $patientIds)
            ->with(['user', 'appointments' => function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id)
                  ->orderBy('appointment_date', 'desc')
                  ->limit(1);
            }]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('first_name', 'asc')
            ->paginate(15);

        return view('doctor.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient
     */
    public function show($id)
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        // Verify patient has appointments with this doctor
        $hasAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $id)
            ->exists();

        if (!$hasAppointments) {
            return redirect()->route('doctor.patients.index')
                ->with('error', 'Patient not found in your patient list.');
        }

        $patient = Patient::with(['user', 'appointments' => function($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id)
              ->orderBy('appointment_date', 'desc');
        }])->findOrFail($id);

        // Get appointment statistics
        $totalAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->count();
        
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->count();
        
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->where('appointment_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->count();

        return view('doctor.patients.show', compact(
            'patient',
            'totalAppointments',
            'completedAppointments',
            'upcomingAppointments'
        ));
    }
}

