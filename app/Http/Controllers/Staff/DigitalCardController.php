<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DigitalCardController extends Controller
{
    /**
     * Show the staff member's own digital card.
     */
    public function show()
    {
        $staff = Auth::user()->staff;

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Staff profile not found. Please contact administrator.');
        }

        // Auto-stamp card_issued_at if never set
        if (!$staff->card_issued_at) {
            $staff->update(['card_issued_at' => now()]);
        }

        $clinicName = get_setting('clinic_name', 'Clinic Management System');
        $clinicAddress = get_setting('clinic_address', '');
        $clinicPhone = get_setting('clinic_phone', '');
        $logoPath = get_setting('clinic_logo');
        $logoUrl = $logoPath
            ? (str_starts_with($logoPath, 'data:') ? $logoPath : asset('storage/' . $logoPath))
            : null;

        return view('staff.digital-card.show', compact(
            'staff',
            'clinicName',
            'clinicAddress',
            'clinicPhone',
            'logoUrl'
        ));
    }

    /**
     * Update digital-card-specific fields (photo, NRIC, emergency contact, etc.)
     */
    public function updateCard(Request $request)
    {
        $staff = Auth::user()->staff;

        if (!$staff) {
            return back()->with('error', 'Staff profile not found.');
        }

        $validated = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nric' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'clinic_location' => 'nullable|string|max:255',
            'card_expires_at' => 'nullable|date',
        ]);

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            if ($staff->profile_photo) {
                Storage::disk('public')->delete($staff->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('staff-photos', 'public');
        }

        // Ensure card_issued_at is set
        if (!$staff->card_issued_at) {
            $validated['card_issued_at'] = now();
        }

        $staff->update($validated);

        return redirect()->route('staff.digital-card.show')
            ->with('success', 'Digital card updated successfully!');
    }
}
