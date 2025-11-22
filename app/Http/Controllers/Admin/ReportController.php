<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports page
     */
    public function index(Request $request)
    {
        // Date range filters (default to last 30 days)
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $startDateCarbon = Carbon::parse($startDate)->startOfDay();
        $endDateCarbon = Carbon::parse($endDate)->endOfDay();

        // Overall Statistics
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalServices = Service::count();
        $totalUsers = User::whereIn('role', ['patient', 'doctor', 'staff'])->count();
        $totalAppointments = Appointment::count();

        // Appointments Statistics
        $appointmentsInRange = Appointment::whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])->get();
        
        $appointmentsByStatus = Appointment::selectRaw('status, COUNT(*) as count')
            ->whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $totalRevenue = Appointment::whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])
            ->whereNotNull('fee')
            ->sum('fee');

        $completedAppointments = Appointment::where('status', 'completed')
            ->whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])
            ->count();

        $cancelledAppointments = Appointment::where('status', 'cancelled')
            ->whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])
            ->count();

        // Patients Statistics
        $newPatientsInRange = Patient::whereBetween('created_at', [$startDateCarbon, $endDateCarbon])->count();
        $patientsByGender = Patient::selectRaw('gender, COUNT(*) as count')
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender');

        // Doctors Statistics
        $doctorsByType = Doctor::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        $availableDoctors = Doctor::where('is_available', true)->count();
        $unavailableDoctors = Doctor::where('is_available', false)->count();

        // Services Statistics
        $servicesByType = Service::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        $activeServices = Service::where('is_active', true)->count();
        $inactiveServices = Service::where('is_active', false)->count();

        // Appointments by Doctor
        $appointmentsByDoctor = Appointment::selectRaw('doctor_id, COUNT(*) as count')
            ->whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])
            ->whereNotNull('doctor_id')
            ->groupBy('doctor_id')
            ->with('doctor')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Appointments by Service
        $appointmentsByService = Appointment::selectRaw('service_id, COUNT(*) as count')
            ->whereBetween('appointment_date', [$startDateCarbon, $endDateCarbon])
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->with('service')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Monthly Revenue (last 6 months)
        $monthlyRevenue = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $revenue = Appointment::whereBetween('appointment_date', [$monthStart, $monthEnd])
                ->whereNotNull('fee')
                ->sum('fee');
            
            $monthlyRevenue->put($month->format('M Y'), $revenue);
        }

        // Daily Appointments (last 7 days)
        $dailyAppointments = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayStart = $day->copy()->startOfDay();
            $dayEnd = $day->copy()->endOfDay();
            
            $count = Appointment::whereBetween('appointment_date', [$dayStart, $dayEnd])->count();
            
            $dailyAppointments->put($day->format('M d'), $count);
        }

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalPatients',
            'totalDoctors',
            'totalServices',
            'totalUsers',
            'totalAppointments',
            'appointmentsInRange',
            'appointmentsByStatus',
            'totalRevenue',
            'completedAppointments',
            'cancelledAppointments',
            'newPatientsInRange',
            'patientsByGender',
            'doctorsByType',
            'availableDoctors',
            'unavailableDoctors',
            'servicesByType',
            'activeServices',
            'inactiveServices',
            'appointmentsByDoctor',
            'appointmentsByService',
            'monthlyRevenue',
            'dailyAppointments'
        ));
    }
}

