<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors
     */
    public function index(Request $request)
    {
        $query = Doctor::withTrashed();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status (active/deleted)
        if ($request->has('status') && $request->status === 'deleted') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor
     */
    public function create()
    {
        // Get users with doctor role who don't have a doctor profile yet
        $availableUsers = User::where('role', 'doctor')
            ->whereDoesntHave('doctor')
            ->orderBy('name')
            ->get();
        
        return view('admin.doctors.create', compact('availableUsers'));
    }

    /**
     * Store a newly created doctor
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id|unique:doctors,user_id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors',
                'phone' => 'nullable|string|max:20',
                'specialization' => 'nullable|string|max:255',
                'qualification' => 'nullable|string|max:255',
                'bio' => 'nullable|string',
                'type' => 'required|in:psychology,homeopathy,general',
                'is_available' => 'boolean',
            ], [
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'type.required' => 'Please select a doctor type.',
            ]);

            // If user_id is provided, verify user has doctor role
            if (isset($validated['user_id'])) {
                $user = User::findOrFail($validated['user_id']);
                if ($user->role !== 'doctor') {
                    $message = 'Selected user must have doctor role.';
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
            }

            $validated['is_available'] = $request->has('is_available') ? true : false;

            $doctor = Doctor::create($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor created successfully!',
                    'doctor' => $doctor
                ]);
            }

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor created successfully!');
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
                    'message' => 'Failed to create doctor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create doctor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified doctor
     */
    public function show($id)
    {
        $doctor = Doctor::withTrashed()->with('user')->findOrFail($id);
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified doctor
     */
    public function edit($id)
    {
        $doctor = Doctor::withTrashed()->with('user')->findOrFail($id);
        
        if ($doctor->trashed()) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Cannot edit a deleted doctor. Please restore it first.');
        }
        
        // Get users with doctor role who don't have a doctor profile yet, or the current user
        $availableUsers = User::where('role', 'doctor')
            ->where(function($query) use ($doctor) {
                $query->whereDoesntHave('doctor')
                      ->orWhere('id', $doctor->user_id);
            })
            ->orderBy('name')
            ->get();
        
        return view('admin.doctors.edit', compact('doctor', 'availableUsers'));
    }

    /**
     * Update the specified doctor
     */
    public function update(Request $request, $id)
    {
        try {
            $doctor = Doctor::withTrashed()->findOrFail($id);
            
            if ($doctor->trashed()) {
                $message = 'Cannot update a deleted doctor. Please restore it first.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.doctors.index')
                    ->with('error', $message);
            }
            
            $validated = $request->validate([
                'user_id' => ['nullable', 'exists:users,id', Rule::unique('doctors')->ignore($doctor->id)],
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('doctors')->ignore($doctor->id)],
                'phone' => 'nullable|string|max:20',
                'specialization' => 'nullable|string|max:255',
                'qualification' => 'nullable|string|max:255',
                'bio' => 'nullable|string',
                'type' => 'required|in:psychology,homeopathy,general',
                'is_available' => 'boolean',
            ], [
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'type.required' => 'Please select a doctor type.',
            ]);

            // If user_id is provided, verify user has doctor role
            if (isset($validated['user_id'])) {
                $user = User::findOrFail($validated['user_id']);
                if ($user->role !== 'doctor') {
                    $message = 'Selected user must have doctor role.';
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
            }

            $validated['is_available'] = $request->has('is_available') ? true : false;

            $doctor->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor updated successfully!',
                    'doctor' => $doctor
                ]);
            }

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully!');
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
                    'message' => 'Failed to update doctor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update doctor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified doctor (soft delete)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor deleted successfully!'
                ]);
            }

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor deleted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete doctor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft deleted doctor
     */
    public function restore(Request $request, $id)
    {
        try {
            $doctor = Doctor::withTrashed()->findOrFail($id);
            
            if (!$doctor->trashed()) {
                $message = 'This doctor is not deleted.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.doctors.index')
                    ->with('info', $message);
            }
            
            $doctor->restore();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor restored successfully!'
                ]);
            }

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor restored successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore doctor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Failed to restore doctor: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a doctor
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $doctor = Doctor::withTrashed()->findOrFail($id);
            $doctor->forceDelete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor permanently deleted!'
                ]);
            }

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor permanently deleted!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete doctor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Failed to permanently delete doctor: ' . $e->getMessage());
        }
    }
}

