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

        // Search by patient name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
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

        $appointment = Appointment::with(['patient', 'service', 'user'])
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
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => $request->status,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully!');
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

