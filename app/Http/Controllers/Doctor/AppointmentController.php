<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the doctor's appointments
     */
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $query = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient', 'service']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by pending approval (approved=0 means not yet approved)
        if ($request->get('approved') === '0') {
            $query->whereNull('record_approved_at');
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        // Track active filter for UI
        $filterApproved = $request->get('approved');

        return view('doctor.appointments.index', compact('appointments', 'filterApproved'));
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $appointment = Appointment::with(['patient', 'service', 'user', 'recordApprovedBy'])
            ->where('doctor_id', $doctor->id)
            ->findOrFail($id);

        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $appointment = Appointment::with(['patient', 'service', 'user'])
            ->where('doctor_id', $doctor->id)
            ->findOrFail($id);

        return view('doctor.appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $updates = [
            'status' => $request->status,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ];

        $recordDataChanged = $appointment->diagnosis !== $request->diagnosis
            || $appointment->prescription !== $request->prescription
            || $appointment->notes !== $request->notes;

        if ($recordDataChanged) {
            $updates['record_approved_by'] = null;
            $updates['record_approved_at'] = null;
        }

        $appointment->update($updates);

        return redirect()->route('doctor.appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Approve the medical record for a completed appointment.
     */
    public function approveRecord($id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->findOrFail($id);

        if ($appointment->status !== Appointment::STATUS_COMPLETED) {
            return redirect()->back()
                ->with('error', 'Only completed appointments can be approved as medical records.');
        }

        $appointment->update([
            'record_approved_by' => Auth::id(),
            'record_approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Medical record approved successfully.');
    }

    /**
     * Display the invoice for an appointment
     */
    public function invoice($id)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $appointment = Appointment::with(['patient', 'service', 'user'])
            ->where('doctor_id', $doctor->id)
            ->findOrFail($id);

        return view('doctor.appointments.invoice', compact('appointment'));
    }
}
