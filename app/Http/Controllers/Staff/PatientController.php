<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of all patients
     */
    public function index(Request $request)
    {
        $query = Patient::with(['user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->orderBy('first_name', 'asc')
            ->paginate(15);

        return view('staff.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient
     */
    public function show($id)
    {
        $patient = Patient::with(['user', 'appointments' => function($q) {
            $q->orderBy('appointment_date', 'desc');
        }])->findOrFail($id);

        // Get appointment statistics
        $totalAppointments = $patient->appointments()->count();
        $completedAppointments = $patient->appointments()->where('status', 'completed')->count();
        $upcomingAppointments = $patient->appointments()
            ->where('appointment_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->count();

        return view('staff.patients.show', compact(
            'patient',
            'totalAppointments',
            'completedAppointments',
            'upcomingAppointments'
        ));
    }
}

