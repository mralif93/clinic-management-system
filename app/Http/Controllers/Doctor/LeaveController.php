<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    /**
     * Display a listing of the doctor's leaves
     */
    public function index(Request $request)
    {
        $query = Leave::with(['reviewer'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->latest()->paginate(15);

        // Get leave statistics
        $stats = [
            'total' => Leave::byUser(Auth::id())->count(),
            'pending' => Leave::byUser(Auth::id())->pending()->count(),
            'approved' => Leave::byUser(Auth::id())->approved()->count(),
            'rejected' => Leave::byUser(Auth::id())->rejected()->count(),
        ];

        return view('doctor.leaves.index', compact('leaves', 'stats'));
    }

    /**
     * Show the form for creating a new leave
     */
    public function create()
    {
        return view('doctor.leaves.create');
    }

    /**
     * Store a newly created leave
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:sick,annual,emergency,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
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

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Leave::create($validated);

        return redirect()->route('doctor.leaves.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    /**
     * Display the specified leave
     */
    public function show(Leave $leave)
    {
        // Ensure the leave belongs to the authenticated doctor
        if ($leave->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $leave->load(['reviewer']);
        return view('doctor.leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave
     */
    public function edit(Leave $leave)
    {
        // Ensure the leave belongs to the authenticated doctor
        if ($leave->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow editing pending leaves
        if ($leave->status !== 'pending') {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Only pending leaves can be edited!');
        }

        return view('doctor.leaves.edit', compact('leave'));
    }

    /**
     * Update the specified leave
     */
    public function update(Request $request, Leave $leave)
    {
        // Ensure the leave belongs to the authenticated doctor
        if ($leave->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow editing pending leaves
        if ($leave->status !== 'pending') {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Only pending leaves can be edited!');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:sick,annual,emergency,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
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

        $leave->update($validated);

        return redirect()->route('doctor.leaves.index')
            ->with('success', 'Leave request updated successfully!');
    }

    /**
     * Remove the specified leave
     */
    public function destroy(Leave $leave)
    {
        // Ensure the leave belongs to the authenticated doctor
        if ($leave->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow deleting pending leaves
        if ($leave->status !== 'pending') {
            return redirect()->route('doctor.leaves.index')
                ->with('error', 'Only pending leaves can be deleted!');
        }

        // Delete attachment file
        if ($leave->attachment) {
            Storage::disk('public')->delete($leave->attachment);
        }

        $leave->delete();

        return redirect()->route('doctor.leaves.index')
            ->with('success', 'Leave request cancelled successfully!');
    }
}
