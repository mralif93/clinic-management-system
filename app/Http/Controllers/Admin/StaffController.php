<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of staff
     */
    public function index(Request $request)
    {
        $query = Staff::withTrashed()->with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by department
        if ($request->has('department') && $request->department) {
            $query->where('department', $request->department);
        }

        // Filter by status (active/deleted)
        if ($request->has('status') && $request->status === 'deleted') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $staff = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff
     */
    public function create()
    {
        // Get users with staff role who don't have a staff profile yet
        $availableUsers = User::where('role', 'staff')
            ->whereDoesntHave('staff')
            ->orderBy('name')
            ->get();

        return view('admin.staff.create', compact('availableUsers'));
    }

    /**
     * Store a newly created staff
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:staff,user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Verify user has staff role
        $user = User::findOrFail($validated['user_id']);
        if ($user->role !== 'staff') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected user must have staff role.');
        }

        Staff::create($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff created successfully!');
    }

    /**
     * Display the specified staff
     */
    public function show($id)
    {
        $staff = Staff::withTrashed()->with('user')->findOrFail($id);
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff
     */
    public function edit($id)
    {
        $staff = Staff::withTrashed()->with('user')->findOrFail($id);
        
        if ($staff->trashed()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Cannot edit a deleted staff. Please restore it first.');
        }
        
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff
     */
    public function update(Request $request, $id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        
        if ($staff->trashed()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Cannot update a deleted staff. Please restore it first.');
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff updated successfully!');
    }

    /**
     * Remove the specified staff (soft delete)
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff deleted successfully!');
    }

    /**
     * Restore a soft deleted staff
     */
    public function restore($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->restore();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff restored successfully!');
    }

    /**
     * Permanently delete a staff
     */
    public function forceDelete($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->forceDelete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff permanently deleted!');
    }
}

