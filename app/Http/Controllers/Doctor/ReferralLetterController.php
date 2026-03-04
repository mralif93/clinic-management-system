<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\ReferralLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralLetterController extends Controller
{
    protected function getDoctor()
    {
        return Auth::user()->doctor;
    }

    /**
     * List all referral letters for the logged-in doctor.
     */
    public function index(Request $request)
    {
        $doctor = $this->getDoctor();
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        $query = ReferralLetter::with(['patient', 'appointment'])
            ->forDoctor($doctor->id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas(
                'patient',
                fn($q) =>
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $letters = $query->paginate(15)->withQueryString();

        return view('doctor.referral-letters.index', compact('letters'));
    }

    /**
     * Show the create form, optionally pre-filled from appointment or patient.
     */
    public function create(Request $request)
    {
        $doctor = $this->getDoctor();
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        $appointment = null;
        $patient = null;

        if ($request->filled('appointment_id')) {
            $appointment = Appointment::with('patient')
                ->where('doctor_id', $doctor->id)
                ->findOrFail($request->appointment_id);
            $patient = $appointment->patient;
        } elseif ($request->filled('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        $patients = Patient::orderBy('first_name')->get();
        $urgencies = ReferralLetter::urgencyOptions();

        return view('doctor.referral-letters.create', compact(
            'appointment',
            'patient',
            'patients',
            'urgencies',
            'doctor'
        ));
    }

    /**
     * Store a new referral letter.
     */
    public function store(Request $request)
    {
        $doctor = $this->getDoctor();
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'referred_to_name' => 'required|string|max:255',
            'referred_to_facility' => 'required|string|max:255',
            'referred_to_specialty' => 'required|string|max:255',
            'reason' => 'required|string',
            'clinical_notes' => 'nullable|string',
            'urgency' => 'required|in:routine,urgent,emergency',
            'valid_until' => 'nullable|date|after:today',
        ]);

        $letter = ReferralLetter::create(array_merge($validated, [
            'doctor_id' => $doctor->id,
            'status' => 'draft',
        ]));

        return redirect()
            ->route('doctor.referral-letters.show', $letter->id)
            ->with('success', 'Referral letter created successfully.');
    }

    /**
     * Show / print a referral letter.
     */
    public function show($id)
    {
        $doctor = $this->getDoctor();
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        $letter = ReferralLetter::with(['patient', 'doctor.user', 'appointment'])
            ->forDoctor($doctor->id)
            ->findOrFail($id);

        return view('doctor.referral-letters.show', compact('letter', 'doctor'));
    }

    /**
     * Show the edit form (draft only).
     */
    public function edit($id)
    {
        $doctor = $this->getDoctor();
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        $letter = ReferralLetter::forDoctor($doctor->id)->findOrFail($id);

        if ($letter->isIssued()) {
            return redirect()->route('doctor.referral-letters.show', $id)
                ->with('error', 'Issued letters cannot be edited.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $urgencies = ReferralLetter::urgencyOptions();

        return view('doctor.referral-letters.edit', compact('letter', 'patients', 'urgencies', 'doctor'));
    }

    /**
     * Update a draft referral letter.
     */
    public function update(Request $request, $id)
    {
        $doctor = $this->getDoctor();
        $letter = ReferralLetter::forDoctor($doctor->id)->findOrFail($id);

        if ($letter->isIssued()) {
            return back()->with('error', 'Issued letters cannot be edited.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'referred_to_name' => 'required|string|max:255',
            'referred_to_facility' => 'required|string|max:255',
            'referred_to_specialty' => 'required|string|max:255',
            'reason' => 'required|string',
            'clinical_notes' => 'nullable|string',
            'urgency' => 'required|in:routine,urgent,emergency',
            'valid_until' => 'nullable|date',
        ]);

        $letter->update($validated);

        return redirect()
            ->route('doctor.referral-letters.show', $letter->id)
            ->with('success', 'Referral letter updated successfully.');
    }

    /**
     * Mark a letter as issued (locked).
     */
    public function issue($id)
    {
        $doctor = $this->getDoctor();
        $letter = ReferralLetter::forDoctor($doctor->id)->findOrFail($id);

        if ($letter->isIssued()) {
            return back()->with('info', 'Letter is already issued.');
        }

        $letter->update([
            'status' => ReferralLetter::STATUS_ISSUED,
            'issued_at' => now(),
        ]);

        return redirect()
            ->route('doctor.referral-letters.show', $letter->id)
            ->with('success', 'Referral letter has been officially issued.');
    }

    /**
     * Delete a draft letter.
     */
    public function destroy($id)
    {
        $doctor = $this->getDoctor();
        $letter = ReferralLetter::forDoctor($doctor->id)->findOrFail($id);

        if ($letter->isIssued()) {
            return back()->with('error', 'Issued letters cannot be deleted.');
        }

        $letter->delete();

        return redirect()
            ->route('doctor.referral-letters.index')
            ->with('success', 'Referral letter deleted.');
    }
}
