<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Attendance;
use App\Models\Doctor;
use App\Models\Leave;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        // Date ranges
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $lastMonth = Carbon::now()->subMonth();

        // ========== QUICK STATS ==========
        // Patients
        $totalPatients = Patient::count();
        $newPatientsThisMonth = Patient::where('created_at', '>=', $startOfMonth)->count();

        // Doctors
        $totalDoctors = Doctor::count();
        $availableDoctors = Doctor::where('is_available', true)->count();

        // Staff
        $totalStaff = Staff::count();

        // Users
        $totalUsers = User::count();

        // Appointments
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('appointment_date', $today)->count();
        $monthlyAppointments = Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->count();

        // ========== TODAY'S OVERVIEW ==========
        $todayAppointmentsByStatus = [
            'scheduled' => Appointment::whereDate('appointment_date', $today)->where('status', 'scheduled')->count(),
            'confirmed' => Appointment::whereDate('appointment_date', $today)->where('status', 'confirmed')->count(),
            'completed' => Appointment::whereDate('appointment_date', $today)->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereDate('appointment_date', $today)->where('status', 'cancelled')->count(),
        ];

        // Today's Attendance
        $todayAttendance = [
            'present' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
            'late' => Attendance::whereDate('date', $today)->where('status', 'late')->count(),
            'absent' => Attendance::whereDate('date', $today)->where('status', 'absent')->count(),
            'on_leave' => Attendance::whereDate('date', $today)->where('status', 'on_leave')->count(),
        ];
        $totalStaffExpected = User::whereIn('role', ['staff', 'doctor'])->where('status', 'active')->count();
        $todayAttendance['not_checked_in'] = max(0, $totalStaffExpected - array_sum($todayAttendance));

        // ========== REVENUE INSIGHTS ==========
        $todayRevenue = Appointment::whereDate('appointment_date', $today)
            ->where('payment_status', 'paid')
            ->sum('fee') ?? 0;

        $monthlyRevenue = Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->where('payment_status', 'paid')
            ->sum('fee') ?? 0;

        $pendingPayments = Appointment::where('payment_status', 'unpaid')
            ->whereIn('status', ['completed', 'confirmed'])
            ->sum('fee') ?? 0;

        // Last 7 days revenue for chart
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Appointment::whereDate('appointment_date', $date)
                ->where('payment_status', 'paid')
                ->sum('fee') ?? 0;
            $revenueData[] = [
                'date' => $date->format('M d'),
                'day' => $date->format('D'),
                'revenue' => (float) $revenue,
            ];
        }

        // ========== APPOINTMENT ANALYTICS ==========
        $appointmentsByStatus = [
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        // ========== PENDING ACTIONS ==========
        $pendingLeaves = Leave::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pendingTodos = Todo::whereIn('status', ['pending', 'in_progress'])
            ->where(function ($query) {
                $query->whereNull('due_date')
                    ->orWhere('due_date', '>=', Carbon::today());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        $overdueTodos = Todo::where('status', '!=', 'completed')
            ->where('due_date', '<', Carbon::today())
            ->count();

        // ========== UPCOMING APPOINTMENTS ==========
        $upcomingAppointments = Appointment::where('appointment_date', '>=', $today)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->take(5)
            ->get();

        // ========== RECENT ACTIVITY ==========
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($apt) {
                $patientName = ($apt->patient && $apt->patient->full_name) ? $apt->patient->full_name : 'Patient';
                $doctorName = ($apt->doctor && $apt->doctor->full_name) ? $apt->doctor->full_name : 'Doctor';
                return [
                    'type' => 'appointment',
                    'icon' => 'bx-calendar',
                    'color' => 'blue',
                    'title' => 'New Appointment',
                    'description' => $patientName . ' with Dr. ' . $doctorName,
                    'time' => $apt->created_at,
                ];
            });

        $recentLeaves = Leave::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($leave) {
                $userName = ($leave->user && $leave->user->name) ? $leave->user->name : 'User';
                return [
                    'type' => 'leave',
                    'icon' => 'bx-calendar-check',
                    'color' => 'purple',
                    'title' => 'Leave Request',
                    'description' => $userName . ' - ' . ucfirst($leave->leave_type),
                    'time' => $leave->created_at,
                ];
            });

        $recentAttendance = Attendance::with('user')
            ->whereDate('date', $today)
            ->orderBy('clock_in_time', 'desc')
            ->take(3)
            ->get()
            ->map(function ($att) {
                $userName = ($att->user && $att->user->name) ? $att->user->name : 'User';
                return [
                    'type' => 'attendance',
                    'icon' => 'bx-time-five',
                    'color' => 'green',
                    'title' => 'Clocked In',
                    'description' => $userName . ' - ' . ($att->clock_in_time ? $att->clock_in_time->format('h:i A') : 'N/A'),
                    'time' => $att->clock_in_time ?? $att->created_at,
                ];
            });

        // Merge and sort activities
        $recentActivity = collect()
            ->merge($recentAppointments)
            ->merge($recentLeaves)
            ->merge($recentAttendance)
            ->sortByDesc('time')
            ->take(8)
            ->values();

        return view('admin.dashboard', compact(
            'totalPatients',
            'newPatientsThisMonth',
            'totalDoctors',
            'availableDoctors',
            'totalStaff',
            'totalUsers',
            'totalAppointments',
            'todayAppointments',
            'monthlyAppointments',
            'todayAppointmentsByStatus',
            'todayAttendance',
            'totalStaffExpected',
            'todayRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'revenueData',
            'appointmentsByStatus',
            'pendingLeaves',
            'pendingTodos',
            'overdueTodos',
            'upcomingAppointments',
            'recentActivity'
        ));
    }
}
