<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    /**
     * Display patient's medical records
     */
    public function index()
    {
        // Get all completed appointments as medical records
        $records = Appointment::where('patient_id', Auth::id())
            ->where('status', 'completed')
            ->with(['doctor.user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('patient.records.index', compact('records'));
    }
}
