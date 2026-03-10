<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DigitalCardController extends Controller
{
    /**
     * Show the doctor's own digital card.
     */
    public function show()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        // Auto-stamp card_issued_at if never set
        if (!$doctor->card_issued_at) {
            $doctor->update(['card_issued_at' => now()]);
        }

        $clinicName = get_setting('clinic_name', 'Clinic Management System');
        $clinicAddress = get_setting('clinic_address', '');
        $clinicPhone = get_setting('clinic_phone', '');
        $logoPath = get_setting('clinic_logo');
        $logoUrl = $logoPath
            ? (str_starts_with($logoPath, 'data:') ? $logoPath : asset('storage/' . $logoPath))
            : null;

        return view('doctor.digital-card.show', compact(
            'doctor',
            'clinicName',
            'clinicAddress',
            'clinicPhone',
            'logoUrl'
        ));
    }

    /**
     * Update digital-card-specific fields (photo, licence, etc.)
     */
    public function updateCard(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return back()->with('error', 'Doctor profile not found.');
        }

        $validated = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'license_number' => 'nullable|string|max:100',
            'license_expiry' => 'nullable|date',
            'years_of_experience' => 'nullable|integer|min:0|max:70',
            'languages_spoken' => 'nullable|string|max:255',
            'clinic_location' => 'nullable|string|max:255',
            'card_expires_at' => 'nullable|date',
        ]);

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($doctor->profile_photo) {
                Storage::disk('public')->delete($doctor->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('doctor-photos', 'public');
        }

        // Ensure card_issued_at is set
        if (!$doctor->card_issued_at) {
            $validated['card_issued_at'] = now();
        }

        $doctor->update($validated);

        return redirect()->route('doctor.digital-card.show')
            ->with('success', 'Digital card updated successfully!');
    }
}
