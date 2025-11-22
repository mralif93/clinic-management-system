<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
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
        ]);

        // If user_id is provided, verify user has patient role
        if (isset($validated['user_id'])) {
            $user = User::findOrFail($validated['user_id']);
            if ($user->role !== 'patient') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected user must have patient role.');
            }
        }

        Patient::create($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient created successfully!');
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
        $patient = Patient::withTrashed()->findOrFail($id);
        
        if ($patient->trashed()) {
            return redirect()->route('admin.patients.index')
                ->with('error', 'Cannot update a deleted patient. Please restore it first.');
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
        ]);

        // If user_id is provided, verify user has patient role
        if (isset($validated['user_id'])) {
            $user = User::findOrFail($validated['user_id']);
            if ($user->role !== 'patient') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected user must have patient role.');
            }
        }

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully!');
    }

    /**
     * Remove the specified patient (soft delete)
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully!');
    }

    /**
     * Restore a soft deleted patient
     */
    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient restored successfully!');
    }

    /**
     * Permanently delete a patient
     */
    public function forceDelete($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->forceDelete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient permanently deleted!');
    }
}

