<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralLetter;
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
        $doctors = \App\Models\Doctor::orderBy('first_name')->get();

        return view('admin.referral-letters.index', compact('letters', 'doctors'));
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
