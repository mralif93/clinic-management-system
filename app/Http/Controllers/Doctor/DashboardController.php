<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DashboardController extends Controller
{
    /**
     * Show the doctor dashboard
     */
    public function index()
    {
        $doctor = Auth::user()->doctor;

        // Get doctor's appointments statistics
        $totalAppointments = 0;
        $todayAppointments = 0;
        $upcomingAppointments = 0;
        $completedAppointments = 0;
        $todayAppointmentsList = collect();

        if ($doctor) {
            $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
            $todayAppointments = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->count();
            $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', '>', now())
                ->where('status', '!=', 'completed')
                ->count();
            $completedAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->count();

            // Get today's appointments list
            $todayAppointmentsList = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->with(['patient', 'service'])
                ->orderBy('appointment_time', 'asc')
                ->get();
        }

        return view('doctor.dashboard', compact(
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'todayAppointmentsList'
        ));
    }
}
