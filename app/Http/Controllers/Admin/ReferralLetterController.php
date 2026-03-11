<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralLetter;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ReferralLetterController extends Controller
{
    /**
     * List all referral letters across all doctors (admin view).
     */
    public function index(Request $request)
    {
        $query = ReferralLetter::with(['patient', 'doctor', 'appointment'])
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

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $letters = $query->paginate(20)->withQueryString();

        // For the doctor filter dropdown
        $doctors = Doctor::with('user')->get()->sortBy(fn($d) => $d->full_name);

        return view('admin.referral-letters.index', compact('letters', 'doctors'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::with('user')->get()->sortBy(fn($d) => $d->full_name);
        $urgencies = ReferralLetter::urgencyOptions();

        return view('admin.referral-letters.create', compact('patients', 'doctors', 'urgencies'));
    }

    /**
     * Store a new referral letter.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'referred_to_name' => 'required|string|max:255',
            'referred_to_facility' => 'required|string|max:255',
            'referred_to_specialty' => 'required|string|max:255',
            'reason' => 'required|string',
            'clinical_notes' => 'nullable|string',
            'urgency' => 'required|in:routine,urgent,emergency',
            'valid_until' => 'nullable|date|after_or_equal:today',
        ]);

        $letter = ReferralLetter::create(array_merge($validated, [
            'status' => ReferralLetter::STATUS_DRAFT,
        ]));

        return redirect()
            ->route('admin.referral-letters.show', $letter->id)
            ->with('success', 'Referral letter created successfully.');
    }

    /**
     * Show a single referral letter (read-only, printable).
     */
    public function show($id)
    {
        $letter = ReferralLetter::with(['patient', 'doctor.user', 'appointment'])
            ->findOrFail($id);

        return view('admin.referral-letters.show', compact('letter'));
    }

    /**
     * Show the edit form.
     */
    public function edit($id)
    {
        $letter = ReferralLetter::findOrFail($id);

        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::with('user')->get()->sortBy(fn($d) => $d->full_name);
        $urgencies = ReferralLetter::urgencyOptions();

        return view('admin.referral-letters.edit', compact('letter', 'patients', 'doctors', 'urgencies'));
    }

    /**
     * Update a referral letter.
     */
    public function update(Request $request, $id)
    {
        $letter = ReferralLetter::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
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
            ->route('admin.referral-letters.show', $letter->id)
            ->with('success', 'Referral letter updated successfully.');
    }

    /**
     * Mark a letter as issued.
     */
    public function issue($id)
    {
        $letter = ReferralLetter::findOrFail($id);

        if ($letter->isIssued()) {
            return back()->with('info', 'Letter is already issued.');
        }

        $letter->update([
            'status' => ReferralLetter::STATUS_ISSUED,
            'issued_at' => now(),
        ]);

        return redirect()
            ->route('admin.referral-letters.show', $letter->id)
            ->with('success', 'Referral letter has been officially issued.');
    }

    /**
     * Delete any referral letter (admin override).
     */
    public function destroy($id)
    {
        $letter = ReferralLetter::findOrFail($id);

        $letter->delete();

        return redirect()
            ->route('admin.referral-letters.index')
            ->with('success', 'Referral letter deleted.');
    }
}
