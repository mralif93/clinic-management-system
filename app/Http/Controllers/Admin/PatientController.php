<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of patients
     */
    public function index(Request $request)
    {
        $query = Patient::withTrashed();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        // Filter by status (active/deleted)
        if ($request->has('status') && $request->status === 'deleted') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient
     */
    public function create()
    {
        // Get users with patient role who don't have a patient profile yet
        $availableUsers = User::where('role', 'patient')
            ->whereDoesntHave('patient')
            ->orderBy('name')
            ->get();
        
        return view('admin.patients.create', compact('availableUsers'));
    }

    /**
     * Store a newly created patient
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id|unique:patients,user_id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:patients',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string',
                'medical_history' => 'nullable|string',
                'allergies' => 'nullable|string',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
            ], [
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
            ]);

            // If user_id is provided, verify user has patient role
            if (isset($validated['user_id'])) {
                $user = User::findOrFail($validated['user_id']);
                if ($user->role !== 'patient') {
                    $message = 'Selected user must have patient role.';
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

            $patient = Patient::create($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient created successfully!',
                    'patient' => $patient
                ]);
            }

            return redirect()->route('admin.patients.index')
                ->with('success', 'Patient created successfully!');
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
                    'message' => 'Failed to create patient: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create patient: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified patient
     */
    public function show($id)
    {
        $patient = Patient::withTrashed()->with('user')->findOrFail($id);
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient
     */
    public function edit($id)
    {
        $patient = Patient::withTrashed()->with('user')->findOrFail($id);
        
        if ($patient->trashed()) {
            return redirect()->route('admin.patients.index')
                ->with('error', 'Cannot edit a deleted patient. Please restore it first.');
        }
        
        // Get users with patient role who don't have a patient profile yet, or the current user
        $availableUsers = User::where('role', 'patient')
            ->where(function($query) use ($patient) {
                $query->whereDoesntHave('patient')
                      ->orWhere('id', $patient->user_id);
            })
            ->orderBy('name')
            ->get();
        
        return view('admin.patients.edit', compact('patient', 'availableUsers'));
    }

    /**
     * Update the specified patient
     */
    public function update(Request $request, $id)
    {
        try {
            $patient = Patient::withTrashed()->findOrFail($id);
            
            if ($patient->trashed()) {
                $message = 'Cannot update a deleted patient. Please restore it first.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.patients.index')
                    ->with('error', $message);
            }
            
            $validated = $request->validate([
                'user_id' => ['nullable', 'exists:users,id', \Illuminate\Validation\Rule::unique('patients')->ignore($patient->id)],
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['nullable', 'email', \Illuminate\Validation\Rule::unique('patients')->ignore($patient->id)],
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string',
                'medical_history' => 'nullable|string',
                'allergies' => 'nullable|string',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
            ], [
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
            ]);

            // If user_id is provided, verify user has patient role
            if (isset($validated['user_id'])) {
                $user = User::findOrFail($validated['user_id']);
                if ($user->role !== 'patient') {
                    $message = 'Selected user must have patient role.';
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

            $patient->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient updated successfully!',
                    'patient' => $patient
                ]);
            }

            return redirect()->route('admin.patients.index')
                ->with('success', 'Patient updated successfully!');
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
                    'message' => 'Failed to update patient: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update patient: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified patient (soft delete)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $patient->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient deleted successfully!'
                ]);
            }

            return redirect()->route('admin.patients.index')
                ->with('success', 'Patient deleted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete patient: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.patients.index')
                ->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft deleted patient
     */
    public function restore(Request $request, $id)
    {
        try {
            $patient = Patient::withTrashed()->findOrFail($id);
            
            if (!$patient->trashed()) {
                $message = 'This patient is not deleted.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.patients.index')
                    ->with('info', $message);
            }
            
            $patient->restore();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient restored successfully!'
                ]);
            }

            return redirect()->route('admin.patients.index')
                ->with('success', 'Patient restored successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore patient: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.patients.index')
                ->with('error', 'Failed to restore patient: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a patient
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $patient = Patient::withTrashed()->findOrFail($id);
            $patient->forceDelete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient permanently deleted!'
                ]);
            }

            return redirect()->route('admin.patients.index')
                ->with('success', 'Patient permanently deleted!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete patient: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.patients.index')
                ->with('error', 'Failed to permanently delete patient: ' . $e->getMessage());
        }
    }
}

