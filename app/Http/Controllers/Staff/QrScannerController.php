<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class QrScannerController extends Controller
{
    public function index()
    {
        return view('staff.qr-scanner');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:12',
        ]);

        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])
            ->where('confirmation_token', $request->token)
            ->first();

        if (! $appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code. Appointment not found.',
            ], 404);
        }

        if ($appointment->status === Appointment::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment has been cancelled.',
                'appointment' => $appointment,
            ], 400);
        }

        if ($appointment->status === Appointment::STATUS_COMPLETED) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment has already been completed.',
                'appointment' => $appointment,
            ], 400);
        }

        if ($appointment->appointment_date->format('Y-m-d') !== now()->format('Y-m-d')) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment is not scheduled for today.',
                'appointment' => $appointment,
            ], 400);
        }

        if ($appointment->status === Appointment::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment is still pending confirmation. Please confirm it first.',
                'appointment' => $appointment,
            ], 400);
        }

        if ($appointment->status === Appointment::STATUS_ARRIVED) {
            return response()->json([
                'success' => true,
                'message' => 'Patient already checked in. Waiting for doctor to accept.',
                'appointment' => $appointment,
                'already_arrived' => true,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'appointment' => $appointment,
        ]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:12',
        ]);

        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])
            ->where('confirmation_token', $request->token)
            ->first();

        if (! $appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code. Appointment not found.',
            ], 404);
        }

        if (! in_array($appointment->status, [Appointment::STATUS_SCHEDULED, Appointment::STATUS_ARRIVED])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot check in patient with current status: '.$appointment->status,
            ], 400);
        }

        $appointment->update([
            'status' => Appointment::STATUS_ARRIVED,
            'arrived_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient checked in successfully! Waiting for doctor to accept.',
            'appointment' => $appointment->fresh(),
        ]);
    }

    public function waiting()
    {
        $appointments = Appointment::with(['patient', 'doctor.user', 'service'])
            ->where('status', Appointment::STATUS_ARRIVED)
            ->whereDate('appointment_date', today())
            ->orderBy('arrived_at')
            ->get()
            ->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'patient_name' => $appt->patient?->name ?? 'Unknown',
                    'doctor_name' => $appt->doctor?->user?->name ?? 'TBA',
                    'service_name' => $appt->service?->name ?? 'N/A',
                    'arrived_at' => $appt->arrived_at?->toISOString(),
                    'wait_time' => $appt->arrived_at ? $appt->arrived_at->diffForHumans() : 'N/A',
                    'is_accepted' => $appt->accepted_at !== null,
                    'room_number' => $appt->room_number,
                ];
            });

        return response()->json([
            'appointments' => $appointments,
        ]);
    }
}
