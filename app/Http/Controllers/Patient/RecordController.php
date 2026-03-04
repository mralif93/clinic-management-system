<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    /**
     * Display patient's medical records
     */
    public function index()
    {
        $patient = Auth::user()?->patient;

        if (! $patient) {
            abort(404, 'Patient profile not found.');
        }

        // Get completed and doctor-approved appointments as medical records
        $records = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->whereNotNull('record_approved_at')
            ->with(['doctor.user', 'service', 'recordApprovedBy'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('patient.records.index', compact('records'));
    }
}
