<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Todo;
use App\Models\Attendance;
use App\Models\Doctor;
use Carbon\Carbon;

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

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        return view('staff.dashboard', compact(
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'todayAppointmentsList',
            'todos',
            'todayAttendance'
        ));
    }

    /**
     * Show the patient flow dashboard
     */
    public function patientFlow()
    {
        $today = today();

        // Get today's appointments grouped by flow status
        $appointments = Appointment::whereDate('appointment_date', $today)
            ->with(['patient', 'doctor.user', 'service'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Group appointments by flow stage
        $scheduled = $appointments->filter(fn($a) => $a->status === 'scheduled' && $a->payment_status !== 'paid');
        $checkedIn = $appointments->filter(fn($a) => $a->status === 'confirmed' && $a->payment_status !== 'paid');
        $inConsultation = $appointments->filter(fn($a) => $a->status === 'in_progress');
        $completed = $appointments->filter(fn($a) => $a->status === 'completed' && $a->payment_status !== 'paid');
        $paid = $appointments->filter(fn($a) => $a->payment_status === 'paid');

        // Stats
        $stats = [
            'scheduled' => $scheduled->count(),
            'checked_in' => $checkedIn->count(),
            'in_consultation' => $inConsultation->count(),
            'completed' => $completed->count(),
            'pending_payment' => $completed->count(),
            'paid' => $paid->count(),
            'total' => $appointments->count(),
        ];

        // Get doctors with their current status
        $doctors = Doctor::with('user')
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->get()
            ->map(function ($doctor) use ($today) {
                $currentAppointment = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', $today)
                    ->where('status', 'in_progress')
                    ->with('patient')
                    ->first();

                $upcomingCount = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', $today)
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count();

                $completedCount = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', $today)
                    ->where('status', 'completed')
                    ->count();

                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name ?? 'Unknown',
                    'specialization' => $doctor->specialization,
                    'status' => $currentAppointment ? 'busy' : 'available',
                    'current_patient' => $currentAppointment?->patient?->name,
                    'upcoming' => $upcomingCount,
                    'completed' => $completedCount,
                ];
            });

        return view('staff.patient-flow', compact(
            'scheduled',
            'checkedIn',
            'inConsultation',
            'completed',
            'paid',
            'stats',
            'doctors'
        ));
    }

    /**
     * Update appointment flow status via AJAX
     */
    public function updateFlowStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:check_in,start_consultation,complete,mark_paid,revert_to_scheduled,revert_to_checked_in,revert_to_in_consultation,revert_to_completed',
            'payment_method' => 'nullable|in:cash,card,online,insurance',
        ]);

        $appointment = Appointment::findOrFail($id);
        $action = $request->action;

        switch ($action) {
            // Forward actions
            case 'check_in':
                $appointment->update(['status' => 'confirmed']);
                $message = 'Patient checked in successfully!';
                break;

            case 'start_consultation':
                $appointment->update(['status' => 'in_progress']);
                $message = 'Consultation started!';
                break;

            case 'complete':
                $appointment->update(['status' => 'completed']);
                $message = 'Consultation completed!';
                break;

            case 'mark_paid':
                $appointment->update([
                    'payment_status' => 'paid',
                    'payment_method' => $request->payment_method ?? 'cash',
                ]);
                $message = 'Payment recorded successfully!';
                break;

            // Revert actions
            case 'revert_to_scheduled':
                $appointment->update(['status' => 'scheduled']);
                $message = 'Reverted to Scheduled status.';
                break;

            case 'revert_to_checked_in':
                $appointment->update(['status' => 'confirmed']);
                $message = 'Reverted to Checked In status.';
                break;

            case 'revert_to_in_consultation':
                $appointment->update(['status' => 'in_progress']);
                $message = 'Reverted to In Consultation status.';
                break;

            case 'revert_to_completed':
                $appointment->update([
                    'status' => 'completed',
                    'payment_status' => 'unpaid',
                    'payment_method' => null,
                ]);
                $message = 'Reverted to Pending Payment status.';
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'appointment' => $appointment->fresh()->load(['patient', 'doctor.user', 'service']),
        ]);
    }

    /**
     * Get patient flow data for AJAX refresh
     */
    public function getFlowData()
    {
        $today = today();

        $appointments = Appointment::whereDate('appointment_date', $today)
            ->with(['patient', 'doctor.user', 'service'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        $data = [
            'scheduled' => $appointments->filter(fn($a) => $a->status === 'scheduled' && $a->payment_status !== 'paid')->values(),
            'checked_in' => $appointments->filter(fn($a) => $a->status === 'confirmed' && $a->payment_status !== 'paid')->values(),
            'in_consultation' => $appointments->filter(fn($a) => $a->status === 'in_progress')->values(),
            'completed' => $appointments->filter(fn($a) => $a->status === 'completed' && $a->payment_status !== 'paid')->values(),
            'paid' => $appointments->filter(fn($a) => $a->payment_status === 'paid')->values(),
            'stats' => [
                'scheduled' => $appointments->filter(fn($a) => $a->status === 'scheduled' && $a->payment_status !== 'paid')->count(),
                'checked_in' => $appointments->filter(fn($a) => $a->status === 'confirmed' && $a->payment_status !== 'paid')->count(),
                'in_consultation' => $appointments->filter(fn($a) => $a->status === 'in_progress')->count(),
                'completed' => $appointments->filter(fn($a) => $a->status === 'completed' && $a->payment_status !== 'paid')->count(),
                'paid' => $appointments->filter(fn($a) => $a->payment_status === 'paid')->count(),
                'total' => $appointments->count(),
            ],
        ];

        return response()->json($data);
    }

    /**
     * Show staff check-in page
     */
    public function checkIn()
    {
        $user = Auth::user();

        // Check if already checked in today
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if ($todayAttendance) {
            return redirect()->route('staff.dashboard')
                ->with('info', 'You have already checked in today.');
        }

        return view('staff.check-in', compact('user'));
    }

    /**
     * Process staff check-in
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
            return redirect()->route('staff.dashboard')
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

        return redirect()->route('staff.dashboard')
            ->with('success', 'Welcome! You have successfully checked in at ' . now()->format('h:i A'));
    }
}
