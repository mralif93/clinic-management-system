<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display the specified doctor.
     */
    public function show($id)
    {
        $doctor = Doctor::with([
            'user',
            'appointments' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($id);

        // Calculate statistics
        $totalAppointments = $doctor->appointments->count();
        $completedAppointments = $doctor->appointments->where('status', 'completed')->count();
        $upcomingAppointments = $doctor->appointments->whereIn('status', ['scheduled', 'confirmed'])->count();

        return view('staff.doctors.show', compact('doctor', 'totalAppointments', 'completedAppointments', 'upcomingAppointments'));
    }
}
