<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the patient dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get appointment statistics
        $totalAppointments = Appointment::where('patient_id', $user->id)->count();
        $upcomingAppointments = Appointment::where('patient_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now())
            ->count();
        $completedAppointments = Appointment::where('patient_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Get recent appointments
        $recentAppointments = Appointment::where('patient_id', $user->id)
            ->with(['doctor', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'totalAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'recentAppointments'
        ));
    }
}

