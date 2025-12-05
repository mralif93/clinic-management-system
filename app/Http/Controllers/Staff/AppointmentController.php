<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of all appointments
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'service']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by doctor
        if ($request->has('doctor_id') && $request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Search by patient name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        $doctors = Doctor::available()->orderBy('first_name')->get();

        return view('staff.appointments.index', compact('appointments', 'doctors'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::available()->orderBy('first_name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('staff.appointments.create', compact('patients', 'doctors', 'services'));
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
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:unpaid,paid,partial',
            'payment_method' => 'nullable|in:cash,card,online,insurance',
        ]);

        if (Appointment::hasConflict($validated['doctor_id'] ?? null, $validated['patient_id'], $validated['appointment_date'], $validated['appointment_time'])) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is already booked for this doctor or patient.'])
                ->withInput();
        }

        $validated['user_id'] = Auth::id();

        $appointment = Appointment::create($validated);

        return redirect()->route('staff.appointments.show', $appointment->id)
            ->with('success', 'Appointment scheduled successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'service', 'user'])
            ->findOrFail($id);

        return view('staff.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::available()->orderBy('first_name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('staff.appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:unpaid,paid,partial',
            'payment_method' => 'nullable|in:cash,card,online,insurance',
        ]);

        if (Appointment::hasConflict($validated['doctor_id'] ?? null, $validated['patient_id'], $validated['appointment_date'], $validated['appointment_time'], $appointment->id)) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is already booked for this doctor or patient.'])
                ->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('staff.appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('staff.appointments.show', $appointment->id)
            ->with('success', 'Appointment status updated successfully!');
    }

    /**
     * Display the invoice for an appointment
     */
    public function invoice($id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'service', 'user'])
            ->findOrFail($id);

        return view('staff.appointments.invoice', compact('appointment'));
    }
}

