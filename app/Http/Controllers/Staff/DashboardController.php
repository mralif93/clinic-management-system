<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Todo;

class DashboardController extends Controller
{
    /**
     * Show the staff dashboard
     */
    public function index()
    {
        // Get appointments statistics
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $upcomingAppointments = Appointment::where('appointment_date', '>', now())
            ->where('status', '!=', 'completed')
            ->count();
        $totalPatients = User::where('role', 'patient')->count();

        // Get today's appointments list
        $todayAppointmentsList = Appointment::whereDate('appointment_date', today())
            ->with(['patient', 'doctor.user', 'service'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get staff's pending and in-progress tasks
        $todos = Todo::where('assigned_to', Auth::id())
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->limit(10)
            ->get();

        return view('staff.dashboard', compact(
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'todayAppointmentsList',
            'todos'
        ));
    }
}
