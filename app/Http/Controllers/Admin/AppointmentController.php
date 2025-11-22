<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments
     */
    public function index(Request $request)
    {
        $query = Appointment::withTrashed()->with(['patient', 'doctor', 'service']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by deleted status
        if ($request->has('deleted') && $request->deleted === '1') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time', 'desc')
                              ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::available()->orderBy('first_name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('admin.appointments.create', compact('patients', 'doctors', 'services'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
        ]);

        Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::withTrashed()
            ->with(['patient', 'doctor', 'service', 'user'])
            ->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        
        if ($appointment->trashed()) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Cannot edit a deleted appointment. Please restore it first.');
        }
        
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::available()->orderBy('first_name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        
        if ($appointment->trashed()) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Cannot update a deleted appointment. Please restore it first.');
        }
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment (soft delete)
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }

    /**
     * Restore a soft deleted appointment
     */
    public function restore($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        $appointment->restore();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment restored successfully!');
    }

    /**
     * Permanently delete an appointment
     */
    public function forceDelete($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        $appointment->forceDelete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment permanently deleted!');
    }
}

