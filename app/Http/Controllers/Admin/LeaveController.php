<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of leave months
     */
    public function index()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $yearExpr = "strftime('%Y', start_date)";
            $monthExpr = "strftime('%m', start_date)";
        } else {
            $yearExpr = "YEAR(start_date)";
            $monthExpr = "MONTH(start_date)";
        }

        $months = Leave::select(
            DB::raw("$yearExpr as year"),
            DB::raw("$monthExpr as month"),
            DB::raw('COUNT(*) as total_leaves'),
            DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
            DB::raw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count"),
            DB::raw("SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count")
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.leaves.months', compact('months'));
    }

    /**
     * Display a listing of leaves for a specific month
     */
    public function byMonth(Request $request, $year, $month)
    {
        $query = Leave::with(['user', 'reviewer'])
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Show trashed
        if ($request->has('trashed') && $request->trashed == '1') {
            $query->onlyTrashed();
        }

        $leaves = $query->latest()->paginate(15);
        $users = User::whereIn('role', ['doctor', 'staff'])->get();
        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

        // Stats for this month
        $stats = [
            'total' => Leave::whereYear('start_date', $year)->whereMonth('start_date', $month)->count(),
            'pending' => Leave::whereYear('start_date', $year)->whereMonth('start_date', $month)->where('status', 'pending')->count(),
            'approved' => Leave::whereYear('start_date', $year)->whereMonth('start_date', $month)->where('status', 'approved')->count(),
        ];

        return view('admin.leaves.list', compact('leaves', 'users', 'year', 'month', 'monthName', 'stats'));
    }

    /**
     * Show the form for creating a new leave
     */
    public function create()
    {
        $users = User::whereIn('role', ['doctor', 'staff'])->get();
        return view('admin.leaves.create', compact('users'));
    }

    /**
     * Store a newly created leave
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:sick,annual,emergency,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['total_days'] = $startDate->diffInDays($endDate) + 1;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('leave-attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Set reviewer if approved/rejected
        if (in_array($validated['status'], ['approved', 'rejected'])) {
            $validated['reviewed_by'] = Auth::id();
            $validated['reviewed_at'] = now();
        }

        Leave::create($validated);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave created successfully!');
    }

    /**
     * Display the specified leave
     */
    public function show(Leave $leave)
    {
        $leave->load(['user', 'reviewer']);
        return view('admin.leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave
     */
    public function edit(Leave $leave)
    {
        $users = User::whereIn('role', ['doctor', 'staff'])->get();
        return view('admin.leaves.edit', compact('leave', 'users'));
    }

    /**
     * Update the specified leave
     */
    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:sick,annual,emergency,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['total_days'] = $startDate->diffInDays($endDate) + 1;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file
            if ($leave->attachment) {
                Storage::disk('public')->delete($leave->attachment);
            }
            $path = $request->file('attachment')->store('leave-attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Set reviewer if status changed to approved/rejected
        if (in_array($validated['status'], ['approved', 'rejected']) && $leave->status === 'pending') {
            $validated['reviewed_by'] = Auth::id();
            $validated['reviewed_at'] = now();
        }

        $leave->update($validated);

        return redirect()->route('admin.leaves.by-month', [
            'year' => \Carbon\Carbon::parse($validated['start_date'])->year,
            'month' => \Carbon\Carbon::parse($validated['start_date'])->month
        ])->with('success', 'Leave updated successfully!');
    }

    /**
     * Remove the specified leave (soft delete)
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();

        return redirect()->back()
            ->with('success', 'Leave deleted successfully!');
    }

    /**
     * Display trashed leaves
     */
    public function trash()
    {
        $leaves = Leave::onlyTrashed()
            ->with(['user', 'reviewer'])
            ->latest()
            ->paginate(15);

        return view('admin.leaves.trash', compact('leaves'));
    }

    /**
     * Restore a soft deleted leave
     */
    public function restore($id)
    {
        $leave = Leave::withTrashed()->findOrFail($id);
        $leave->restore();

        return redirect()->route('admin.leaves.trash')
            ->with('success', 'Leave restored successfully!');
    }

    /**
     * Permanently delete a leave
     */
    public function forceDelete($id)
    {
        $leave = Leave::withTrashed()->findOrFail($id);

        // Delete attachment file
        if ($leave->attachment) {
            Storage::disk('public')->delete($leave->attachment);
        }

        $leave->forceDelete();

        return redirect()->route('admin.leaves.trash')
            ->with('success', 'Leave permanently deleted!');
    }

    /**
     * Approve a leave request
     */
    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Leave approved successfully!');
    }

    /**
     * Reject a leave request
     */
    public function reject(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->back()
            ->with('success', 'Leave rejected successfully!');
    }
}