<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Attendance;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $pendingApprovalCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereNull('record_approved_at')
            ->count();

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
            'pendingApprovalCount',
            'todayAppointmentsList',
            'todos',
            'todayAttendance'
        ));
    }

    public function waitingPatients()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return response()->json(['patients' => []], 404);
        }

        $patients = Appointment::with(['patient', 'service'])
            ->where('doctor_id', $doctor->id)
            ->where('status', Appointment::STATUS_ARRIVED)
            ->whereDate('appointment_date', today())
            ->orderBy('arrived_at', 'asc')
            ->get()
            ->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'patient_name' => $appt->patient?->name ?? 'Unknown',
                    'patient_ic' => $appt->patient?->ic_number ?? 'N/A',
                    'service_name' => $appt->service?->name ?? 'N/A',
                    'appointment_time' => $appt->appointment_time,
                    'arrived_at' => $appt->arrived_at?->diffForHumans(),
                    'wait_minutes' => $appt->arrived_at ? now()->diffInMinutes($appt->arrived_at) : 0,
                ];
            });

        return response()->json(['patients' => $patients]);
    }

    public function acceptPatient(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found',
            ], 404);
        }

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->where('status', Appointment::STATUS_ARRIVED)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found or not in arrived status',
            ], 404);
        }

        $request->validate([
            'room_number' => 'nullable|string|max:20',
        ]);

        $appointment->update([
            'status' => Appointment::STATUS_CONFIRMED,
            'accepted_by' => Auth::id(),
            'accepted_at' => now(),
            'room_number' => $request->room_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient accepted successfully',
            'appointment' => $appointment->fresh()->load(['patient', 'service']),
        ]);
    }

    public function rejectPatient(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found',
            ], 404);
        }

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->where('status', Appointment::STATUS_ARRIVED)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found or not in arrived status',
            ], 404);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'status' => Appointment::STATUS_SCHEDULED,
            'arrived_at' => null,
            'notes' => $appointment->notes . "\n[Rejected by Doctor: " . ($request->reason ?? 'No reason provided') . ' at ' . now()->format('Y-m-d H:i') . ']',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient rejected. They will need to check in again.',
            'appointment' => $appointment->fresh(),
        ]);
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
