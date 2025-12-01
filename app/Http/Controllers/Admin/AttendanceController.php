<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display attendance management page (grouped by month)
     */
    public function index()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $yearExpr = "strftime('%Y', date)";
            $monthExpr = "strftime('%m', date)";
        } else {
            $yearExpr = "YEAR(date)";
            $monthExpr = "MONTH(date)";
        }

        $months = Attendance::select(
            DB::raw("$yearExpr as year"),
            DB::raw("$monthExpr as month"),
            DB::raw('COUNT(*) as total_records'),
            DB::raw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count"),
            DB::raw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count"),
            DB::raw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count")
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.attendance.months', compact('months'));
    }

    /**
     * Display a listing of attendance for a specific month
     */
    public function byMonth(Request $request, $year, $month)
    {
        $query = Attendance::with(['user', 'approver'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('clock_in_time', 'desc')
            ->paginate(20);

        // Get users for filter
        $users = User::whereIn('role', ['staff', 'doctor'])->get();
        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

        // Stats for this month
        $stats = [
            'total' => Attendance::whereYear('date', $year)->whereMonth('date', $month)->count(),
            'present' => Attendance::whereYear('date', $year)->whereMonth('date', $month)->where('status', 'present')->count(),
            'late' => Attendance::whereYear('date', $year)->whereMonth('date', $month)->where('status', 'late')->count(),
        ];

        return view('admin.attendance.list', compact('attendances', 'users', 'year', 'month', 'monthName', 'stats'));
    }

    /**
     * Show live dashboard
     */
    public function live()
    {
        $clockedIn = Attendance::with('user')
            ->whereDate('date', today())
            ->clockedIn()
            ->get();

        $lateToday = Attendance::with('user')
            ->whereDate('date', today())
            ->where('status', 'late')
            ->get();

        return view('admin.attendance.live', compact('clockedIn', 'lateToday'));
    }

    /**
     * Create manual attendance entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,half_day,absent,on_leave',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time
        $clockInTime = Carbon::parse($validated['date'] . ' ' . $validated['clock_in_time']);
        $clockOutTime = $validated['clock_out_time']
            ? Carbon::parse($validated['date'] . ' ' . $validated['clock_out_time'])
            : null;

        $attendance = Attendance::create([
            'user_id' => $validated['user_id'],
            'date' => $validated['date'],
            'clock_in_time' => $clockInTime,
            'clock_out_time' => $clockOutTime,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        if ($clockOutTime) {
            $attendance->updateTotalHours();
        }

        // Redirect back to month view if date is set, or index
        if(isset($validated['date'])) {
             $date = Carbon::parse($validated['date']);
             return redirect()->route('admin.attendance.by-month', ['year' => $date->year, 'month' => $date->month])
                ->with('success', 'Attendance entry created successfully!');
        }

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance entry created successfully!');
    }

    /**
     * Update attendance
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,half_day,absent,on_leave',
            'notes' => 'nullable|string',
        ]);

        $clockInTime = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $validated['clock_in_time']);
        $clockOutTime = $validated['clock_out_time']
            ? Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $validated['clock_out_time'])
            : null;

        $attendance->update([
            'clock_in_time' => $clockInTime,
            'clock_out_time' => $clockOutTime,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($clockOutTime) {
            $attendance->updateTotalHours();
        }

        return redirect()->route('admin.attendance.by-month', ['year' => $attendance->date->year, 'month' => $attendance->date->month])
            ->with('success', 'Attendance updated successfully!');
    }

    /**
     * Approve attendance
     */
    public function approve(Attendance $attendance)
    {
        $attendance->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Attendance approved!');
    }

    /**
     * Delete attendance
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance deleted!');
    }

    /**
     * Reports page
     */
    public function reports(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $attendances = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $summary = $attendances->groupBy('user_id')->map(function ($userAttendances) {
            return [
                'user' => $userAttendances->first()->user,
                'total_days' => $userAttendances->count(),
                'present' => $userAttendances->where('status', 'present')->count(),
                'late' => $userAttendances->where('status', 'late')->count(),
                'absent' => $userAttendances->where('status', 'absent')->count(),
                'total_hours' => $userAttendances->sum('total_hours'),
            ];
        });

        return view('admin.attendance.reports', compact('summary', 'startDate', 'endDate'));
    }
    /**
     * Export attendance report to CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $attendances = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $filename = "attendance_report_{$startDate}_to_{$endDate}.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'User', 'Role', 'Clock In', 'Clock Out', 'Total Hours', 'Status', 'Notes'];

        $callback = function () use ($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attendances as $attendance) {
                $row = [
                    $attendance->date->format('Y-m-d'),
                    $attendance->user->name,
                    ucfirst($attendance->user->role),
                    $attendance->clock_in_time ? $attendance->clock_in_time->format('H:i') : '-',
                    $attendance->clock_out_time ? $attendance->clock_out_time->format('H:i') : '-',
                    $attendance->total_hours ?? '0',
                    ucfirst(str_replace('_', ' ', $attendance->status)),
                    $attendance->notes
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * List pending corrections
     */
    public function corrections()
    {
        $corrections = \App\Models\AttendanceCorrection::with(['user', 'attendance'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.attendance.corrections', compact('corrections'));
    }

    /**
     * Approve correction request
     */
    public function approveCorrection(\App\Models\AttendanceCorrection $correction)
    {
        $attendance = $correction->attendance;

        $attendance->update([
            'clock_in_time' => $correction->clock_in_time,
            'clock_out_time' => $correction->clock_out_time,
            'notes' => $attendance->notes . "\n[Correction Approved] " . $correction->reason,
        ]);

        if ($correction->clock_out_time) {
            $attendance->updateTotalHours();
        }

        $correction->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Correction request approved successfully.');
    }

    /**
     * Reject correction request
     */
    public function rejectCorrection(\App\Models\AttendanceCorrection $correction)
    {
        $correction->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Correction request rejected.');
    }

    /**
     * Trash
     */
    public function trash()
    {
        $attendances = Attendance::onlyTrashed()
            ->with('user')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view('admin.attendance.trash', compact('attendances'));
    }

    /**
     * Restore
     */
    public function restore($id)
    {
        $attendance = Attendance::onlyTrashed()->findOrFail($id);
        $attendance->restore();

        return back()->with('success', 'Attendance record restored successfully.');
    }

    /**
     * Force Delete
     */
    public function forceDelete($id)
    {
        $attendance = Attendance::onlyTrashed()->findOrFail($id);
        $attendance->forceDelete();

        return back()->with('success', 'Attendance record permanently deleted.');
    }
}