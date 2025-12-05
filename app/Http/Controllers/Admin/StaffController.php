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
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id|unique:staff,user_id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'position' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'hire_date' => 'nullable|date',
                'employment_type' => 'required|in:full_time,part_time',
                'basic_salary' => 'required_if:employment_type,full_time|nullable|numeric|min:0',
                'hourly_rate' => 'required_if:employment_type,part_time|nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ], [
                'user_id.required' => 'Please select a user account.',
                'user_id.exists' => 'Selected user does not exist.',
                'user_id.unique' => 'This user already has a staff profile.',
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'employment_type.required' => 'Please select an employment type.',
                'basic_salary.required_if' => 'Basic salary is required for full-time employees.',
                'hourly_rate.required_if' => 'Hourly rate is required for part-time employees.',
            ]);

            // Verify user has staff role
            $user = User::findOrFail($validated['user_id']);
            if ($user->role !== 'staff') {
                $message = 'Selected user must have staff role.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', $message);
            }

            // Update user employment fields
            $user->update([
                'employment_type' => $validated['employment_type'],
                'basic_salary' => $validated['employment_type'] === 'full_time' ? $validated['basic_salary'] : null,
                'hourly_rate' => $validated['employment_type'] === 'part_time' ? $validated['hourly_rate'] : null,
            ]);

            $staff = Staff::create($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Staff created successfully!',
                    'staff' => $staff
                ]);
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create staff: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
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
        try {
            $staff = Staff::withTrashed()->findOrFail($id);
            
            if ($staff->trashed()) {
                $message = 'Cannot update a deleted staff. Please restore it first.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.staff.index')
                    ->with('error', $message);
            }
            
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'position' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'hire_date' => 'nullable|date',
                'employment_type' => 'required|in:full_time,part_time',
                'basic_salary' => 'required_if:employment_type,full_time|nullable|numeric|min:0',
                'hourly_rate' => 'required_if:employment_type,part_time|nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ], [
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'employment_type.required' => 'Please select an employment type.',
                'basic_salary.required_if' => 'Basic salary is required for full-time employees.',
                'hourly_rate.required_if' => 'Hourly rate is required for part-time employees.',
            ]);

            // Update user employment fields
            $staff->user->update([
                'employment_type' => $validated['employment_type'],
                'basic_salary' => $validated['employment_type'] === 'full_time' ? $validated['basic_salary'] : null,
                'hourly_rate' => $validated['employment_type'] === 'part_time' ? $validated['hourly_rate'] : null,
            ]);

            $staff->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Staff updated successfully!',
                    'staff' => $staff
                ]);
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update staff: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update staff: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified staff (soft delete)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $staff = Staff::findOrFail($id);
            $staff->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Staff deleted successfully!'
                ]);
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff deleted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete staff: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.staff.index')
                ->with('error', 'Failed to delete staff: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft deleted staff
     */
    public function restore(Request $request, $id)
    {
        try {
            $staff = Staff::withTrashed()->findOrFail($id);
            
            if (!$staff->trashed()) {
                $message = 'This staff is not deleted.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.staff.index')
                    ->with('info', $message);
            }
            
            $staff->restore();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Staff restored successfully!'
                ]);
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff restored successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore staff: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.staff.index')
                ->with('error', 'Failed to restore staff: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a staff
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $staff = Staff::withTrashed()->findOrFail($id);
            $staff->forceDelete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Staff permanently deleted!'
                ]);
            }

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff permanently deleted!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete staff: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.staff.index')
                ->with('error', 'Failed to permanently delete staff: ' . $e->getMessage());
        }
    }
}

