<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance page
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', today())
            ->first();

        // Get this month's attendance
        $attendances = Attendance::where('user_id', $userId)
            ->thisMonth()
            ->orderBy('date', 'desc')
            ->get();

        // Calculate stats
        $stats = [
            'total_days' => $attendances->count(),
            'present_days' => $attendances->where('status', 'present')->count(),
            'late_days' => $attendances->where('status', 'late')->count(),
            'total_hours' => $attendances->sum('total_hours'),
        ];

        return view('staff.attendance.index', compact('todayAttendance', 'attendances', 'stats'));
    }

    /**
     * Clock in
     */
    public function clockIn(Request $request)
    {
        $userId = Auth::id();
        $today = today();

        // Check if already clocked in today
        $existing = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today!');
        }

        // Get location (IP address)
        $location = $request->ip();

        // Create attendance record
        $attendance = Attendance::create([
            'user_id' => $userId,
            'date' => $today,
            'clock_in_time' => now(),
            'clock_in_location' => $location,
            'status' => 'present', // Will be updated if late
        ]);

        // Check if late
        if ($attendance->isLate()) {
            $attendance->status = 'late';
            $attendance->save();
        }

        return back()->with('success', 'Clocked in successfully!');
    }

    /**
     * Clock out
     */
    public function clockOut(Request $request)
    {
        $userId = Auth::id();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'You haven\'t clocked in today!');
        }

        if ($attendance->clock_out_time) {
            return back()->with('error', 'You have already clocked out!');
        }

        // End any active break
        $activeBreak = $attendance->getCurrentBreak();
        if ($activeBreak) {
            $activeBreak->break_end = now();
            $activeBreak->save();
            $attendance->break_duration += $activeBreak->getDuration();
        }

        // Clock out
        $attendance->clock_out_time = now();
        $attendance->clock_out_location = $request->ip();
        $attendance->updateTotalHours();
        $attendance->save();

        return back()->with('success', 'Clocked out successfully! Total hours: ' . $attendance->total_hours);
    }

    /**
     * Request attendance correction
     */
    public function requestCorrection(Request $request)
    {
        $validated = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'nullable|date_format:H:i',
            'reason' => 'required|string|max:500',
        ]);

        $attendance = Attendance::where('id', $validated['attendance_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Check if pending correction exists
        $existing = \App\Models\AttendanceCorrection::where('attendance_id', $attendance->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending correction request for this date.');
        }

        $clockInTime = \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $validated['clock_in_time']);
        $clockOutTime = $validated['clock_out_time']
            ? \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $validated['clock_out_time'])
            : null;

        \App\Models\AttendanceCorrection::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'clock_in_time' => $clockInTime,
            'clock_out_time' => $clockOutTime,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Correction request submitted successfully.');
    }
    /**
     * Start break
     */
    public function startBreak(Request $request)
    {
        $userId = Auth::id();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->isClockedIn()) {
            return back()->with('error', 'You must be clocked in to take a break!');
        }

        if ($attendance->isOnBreak()) {
            return back()->with('error', 'You are already on a break!');
        }

        AttendanceBreak::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
            'break_type' => $request->break_type ?? 'personal',
        ]);

        return back()->with('success', 'Break started!');
    }

    /**
     * End break
     */
    public function endBreak(Request $request)
    {
        $userId = Auth::id();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No attendance record found!');
        }

        $activeBreak = $attendance->getCurrentBreak();

        if (!$activeBreak) {
            return back()->with('error', 'You are not on a break!');
        }

        $activeBreak->break_end = now();
        $activeBreak->save();

        // Update total break duration
        $attendance->break_duration += $activeBreak->getDuration();
        $attendance->save();

        return back()->with('success', 'Break ended! Duration: ' . $activeBreak->getDurationFormatted());
    }
}
