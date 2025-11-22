<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Patient;

class DashboardController extends Controller
{
    /**
     * Show the staff dashboard
     */
    public function index()
    {
        // Get staff statistics
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $upcomingAppointments = Appointment::where('appointment_date', '>', now())
            ->where('status', '!=', 'completed')
            ->count();
        $totalPatients = Patient::count();
        
        return view('staff.dashboard', compact(
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients'
        ));
    }
}

