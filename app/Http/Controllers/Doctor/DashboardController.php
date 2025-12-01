<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Attendance;

class DashboardController extends Controller
{
    /**
     * Show the doctor dashboard
     */
    public function index()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('login')->with('error', 'Doctor profile not found');
        }

        // Get appointments statistics
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
        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');

        // Get today's appointments list
        $todayAppointmentsList = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->with(['patient', 'service'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        return view('doctor.dashboard', compact(
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'totalPatients',
            'todayAppointmentsList',
            'todayAttendance'
        ));
    }
}
