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
     * Show the admin dashboard (Optimized for performance)
     */
    public function index()
    {
        try {
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // ========== QUICK STATS (Simplified) ==========
            $totalPatients = Patient::count();
            $totalDoctors = Doctor::count();
            $totalStaff = Staff::count();
            $totalUsers = User::count();
            $totalAppointments = Appointment::count();
            
            // Simplified today's appointments
            $todayAppointments = Appointment::whereDate('appointment_date', $today)->count();
            
            // Simplified today's appointments by status (single query)
            $todayAppointmentsByStatus = [
                'scheduled' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'cancelled' => 0,
            ];
            
            try {
                $todayStatusCounts = Appointment::whereDate('appointment_date', $today)
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
                
                foreach ($todayStatusCounts as $status => $count) {
                    if (isset($todayAppointmentsByStatus[$status])) {
                        $todayAppointmentsByStatus[$status] = $count;
                    }
                }
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Simplified attendance
            $todayAttendance = [
                'present' => 0,
                'late' => 0,
                'absent' => 0,
                'on_leave' => 0,
                'not_checked_in' => 0,
            ];
            
            try {
                $attendanceCounts = Attendance::whereDate('date', $today)
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
                
                foreach ($attendanceCounts as $status => $count) {
                    if (isset($todayAttendance[$status])) {
                        $todayAttendance[$status] = $count;
                    }
                }
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Simplified revenue (single query)
            $todayRevenue = 0;
            $monthlyRevenue = 0;
            $pendingPayments = 0;
            
            try {
                $todayRevenue = (float) (Appointment::whereDate('appointment_date', $today)
                    ->where('payment_status', 'paid')
                    ->sum('fee') ?? 0);

                $monthlyRevenue = (float) (Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
                    ->where('payment_status', 'paid')
                    ->sum('fee') ?? 0);

                $pendingPayments = (float) (Appointment::where('payment_status', 'unpaid')
                    ->whereIn('status', ['completed', 'confirmed'])
                    ->sum('fee') ?? 0);
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Simplified revenue data (reduced to 3 days instead of 7)
            $revenueData = [];
            try {
                for ($i = 2; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $revenue = (float) (Appointment::whereDate('appointment_date', $date)
                        ->where('payment_status', 'paid')
                        ->sum('fee') ?? 0);
                    $revenueData[] = [
                        'date' => $date->format('M d'),
                        'day' => $date->format('D'),
                        'revenue' => $revenue,
                    ];
                }
            } catch (\Exception $e) {
                // Default empty data
                for ($i = 2; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $revenueData[] = [
                        'date' => $date->format('M d'),
                        'day' => $date->format('D'),
                        'revenue' => 0,
                    ];
                }
            }

            // Simplified appointment status counts
            $appointmentsByStatus = [
                'scheduled' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'cancelled' => 0,
            ];
            
            try {
                $statusCounts = Appointment::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
                
                foreach ($statusCounts as $status => $count) {
                    if (isset($appointmentsByStatus[$status])) {
                        $appointmentsByStatus[$status] = $count;
                    }
                }
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Simplified pending items (reduced to 3 each)
            $pendingLeaves = collect();
            $pendingTodos = collect();
            $overdueTodos = 0;
            
            try {
                $pendingLeaves = Leave::where('status', 'pending')
                    ->with('user:id,name')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            try {
                $pendingTodos = Todo::whereIn('status', ['pending', 'in_progress'])
                    ->where(function ($query) {
                        $query->whereNull('due_date')
                            ->orWhere('due_date', '>=', Carbon::today());
                    })
                    ->orderBy('priority', 'desc')
                    ->orderBy('due_date', 'asc')
                    ->take(3)
                    ->get();
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            try {
                $overdueTodos = Todo::where('status', '!=', 'completed')
                    ->where('due_date', '<', Carbon::today())
                    ->count();
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Simplified upcoming appointments (reduced to 3)
            $upcomingAppointments = collect();
            try {
                $upcomingAppointments = Appointment::where('appointment_date', '>=', $today)
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->with(['patient:id,first_name,last_name', 'doctor:id,first_name,last_name'])
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('appointment_time', 'asc')
                    ->take(3)
                    ->get();
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Simplified recent activity (reduced to 5 items)
            $recentActivity = collect();
            try {
                $recentAppointments = Appointment::with(['patient:id,first_name,last_name', 'doctor:id,first_name,last_name'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get()
                    ->map(function ($apt) {
                        $patientName = ($apt->patient) ? trim(($apt->patient->first_name ?? '') . ' ' . ($apt->patient->last_name ?? '')) : 'Patient';
                        $doctorName = ($apt->doctor) ? trim(($apt->doctor->first_name ?? '') . ' ' . ($apt->doctor->last_name ?? '')) : 'Doctor';
                        return [
                            'type' => 'appointment',
                            'icon' => 'bx-calendar',
                            'color' => 'blue',
                            'title' => 'New Appointment',
                            'description' => ($patientName ?: 'Patient') . ' with Dr. ' . ($doctorName ?: 'Doctor'),
                            'time' => $apt->created_at,
                        ];
                    });

                $recentLeaves = Leave::with('user:id,name')
                    ->orderBy('created_at', 'desc')
                    ->take(2)
                    ->get()
                    ->map(function ($leave) {
                        $userName = ($leave->user && $leave->user->name) ? $leave->user->name : 'User';
                        return [
                            'type' => 'leave',
                            'icon' => 'bx-calendar-check',
                            'color' => 'purple',
                            'title' => 'Leave Request',
                            'description' => $userName . ' - ' . ucfirst($leave->leave_type ?? 'leave'),
                            'time' => $leave->created_at,
                        ];
                    });

                $recentActivity = collect()
                    ->merge($recentAppointments)
                    ->merge($recentLeaves)
                    ->sortByDesc('time')
                    ->take(5)
                    ->values();
            } catch (\Exception $e) {
                // Ignore if query fails
            }

            // Calculate derived values
            $newPatientsThisMonth = 0;
            $availableDoctors = 0;
            $monthlyAppointments = 0;
            $totalStaffExpected = 0;
            
            try {
                $newPatientsThisMonth = Patient::where('created_at', '>=', $startOfMonth)->count();
                $availableDoctors = Doctor::where('is_available', true)->count();
                $monthlyAppointments = Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])->count();
                $totalStaffExpected = User::whereIn('role', ['staff', 'doctor'])->where('status', 'active')->count();
                $todayAttendance['not_checked_in'] = max(0, $totalStaffExpected - array_sum($todayAttendance));
            } catch (\Exception $e) {
                // Ignore if query fails
            }

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
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return minimal dashboard on error
            return view('admin.dashboard', [
                'totalPatients' => 0,
                'newPatientsThisMonth' => 0,
                'totalDoctors' => 0,
                'availableDoctors' => 0,
                'totalStaff' => 0,
                'totalUsers' => 0,
                'totalAppointments' => 0,
                'todayAppointments' => 0,
                'monthlyAppointments' => 0,
                'todayAppointmentsByStatus' => ['scheduled' => 0, 'confirmed' => 0, 'completed' => 0, 'cancelled' => 0],
                'todayAttendance' => ['present' => 0, 'late' => 0, 'absent' => 0, 'on_leave' => 0, 'not_checked_in' => 0],
                'totalStaffExpected' => 0,
                'todayRevenue' => 0,
                'monthlyRevenue' => 0,
                'pendingPayments' => 0,
                'revenueData' => [],
                'appointmentsByStatus' => ['scheduled' => 0, 'confirmed' => 0, 'completed' => 0, 'cancelled' => 0],
                'pendingLeaves' => collect(),
                'pendingTodos' => collect(),
                'overdueTodos' => 0,
                'upcomingAppointments' => collect(),
                'recentActivity' => collect(),
            ]);
        }
    }
}
