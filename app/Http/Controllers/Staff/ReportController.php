<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports and statistics
     */
    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Overall statistics
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalServices = Service::count();
        $totalAppointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->count();

        // Appointment statistics
        $scheduledAppointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'scheduled')->count();
        $confirmedAppointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'confirmed')->count();
        $completedAppointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'completed')->count();
        $cancelledAppointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'cancelled')->count();

        // Revenue (from completed appointments)
        $revenue = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('fee');

        // Today's statistics
        $todayAppointments = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $todayCompleted = Appointment::whereDate('appointment_date', Carbon::today())
            ->where('status', 'completed')->count();

        return view('staff.reports.index', compact(
            'totalPatients',
            'totalDoctors',
            'totalServices',
            'totalAppointments',
            'scheduledAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'revenue',
            'todayAppointments',
            'todayCompleted',
            'startDate',
            'endDate'
        ));
    }
}
