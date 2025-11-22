<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display the doctor's schedule
     */
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        // Get selected date or default to today
        $selectedDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
        
        // Get appointments for the selected date
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $selectedDate)
            ->with(['patient', 'service'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get appointments for the week
        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();
        
        $weekAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->with(['patient', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get()
            ->groupBy(function($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            });

        // Statistics
        $todayCount = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', Carbon::today())
            ->count();
        
        $weekCount = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->count();
        
        $upcomingCount = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', '>=', Carbon::today())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->count();

        return view('doctor.schedule.index', compact(
            'appointments',
            'selectedDate',
            'weekAppointments',
            'weekStart',
            'weekEnd',
            'todayCount',
            'weekCount',
            'upcomingCount'
        ));
    }
}

