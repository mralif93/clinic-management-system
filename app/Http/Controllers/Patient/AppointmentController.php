<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of patient's appointments
     */
    public function index()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with(['doctor', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $doctors = Doctor::with('user')->get();
        $services = Service::all();

        return view('patient.appointments.create', compact('doctors', 'services'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:500'
        ]);

        $patientId = Auth::id();

        if (Appointment::hasConflict($validated['doctor_id'], $patientId, $validated['appointment_date'], $validated['appointment_time'])) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is already booked for you or the doctor.'])
                ->withInput();
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $validated['doctor_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending'
        ]);

        return redirect()->route('patient.appointments.show', $appointment->id)
            ->with('success', 'Appointment booked successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::where('patient_id', Auth::id())
            ->with(['doctor.user', 'service'])
            ->findOrFail($id);

        return view('patient.appointments.show', compact('appointment'));
    }
}
