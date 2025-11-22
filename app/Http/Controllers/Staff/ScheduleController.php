<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display the clinic schedule
     */
    public function index(Request $request)
    {
        // Get selected date or default to today
        $selectedDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
        
        // Get appointments for the selected date
        $appointments = Appointment::whereDate('appointment_date', $selectedDate)
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get appointments for the week
        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();
        
        $weekAppointments = Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get()
            ->groupBy(function($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            });

        // Statistics
        $todayCount = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $weekCount = Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])->count();
        $upcomingCount = Appointment::where('appointment_date', '>=', Carbon::today())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->count();

        // Get all doctors for filter
        $doctors = Doctor::available()->orderBy('first_name')->get();

        return view('staff.schedule.index', compact(
            'appointments',
            'selectedDate',
            'weekAppointments',
            'weekStart',
            'weekEnd',
            'todayCount',
            'weekCount',
            'upcomingCount',
            'doctors'
        ));
    }
}

