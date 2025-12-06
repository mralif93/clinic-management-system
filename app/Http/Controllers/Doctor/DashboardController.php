<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Todo;

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

        // Get doctor's pending and in-progress tasks
        $todos = Todo::where('assigned_to', Auth::id())
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->limit(10)
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
            'todos',
            'todayAttendance'
        ));
    }

    /**
     * Show doctor check-in page
     */
    public function checkIn()
    {
        $user = Auth::user();

        // Check if already checked in today
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if ($todayAttendance) {
            return redirect()->route('doctor.dashboard')
                ->with('info', 'You have already checked in today.');
        }

        return view('doctor.check-in', compact('user'));
    }

    /**
     * Process doctor check-in
     */
    public function storeCheckIn(Request $request)
    {
        $userId = Auth::id();
        $today = today();

        // Check if already checked in today
        $existing = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return redirect()->route('doctor.dashboard')
                ->with('info', 'You have already checked in today.');
        }

        // Get location (IP address)
        $location = $request->ip();

        // Create attendance record
        $attendance = Attendance::create([
            'user_id' => $userId,
            'date' => $today,
            'clock_in_time' => now(),
            'clock_in_location' => $location,
            'status' => 'present',
        ]);

        // Check if late (after 9:15 AM)
        if ($attendance->isLate()) {
            $attendance->status = 'late';
            $attendance->save();
        }

        return redirect()->route('doctor.dashboard')
            ->with('success', 'Welcome! You have successfully checked in at ' . now()->format('h:i A'));
    }
}
