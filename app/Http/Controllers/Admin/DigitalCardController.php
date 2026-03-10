<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DigitalCardController extends Controller
{
    /**
     * Show admin's own digital card.
     */
    public function selfCard()
    {
        $user = Auth::user();

        // Auto-stamp card_issued_at if never set
        if (!$user->card_issued_at) {
            $user->update(['card_issued_at' => now()]);
        }

        $clinicName = get_setting('clinic_name', 'Clinic Management System');
        $clinicAddress = get_setting('clinic_address', '');
        $clinicPhone = get_setting('clinic_phone', '');
        $logoPath = get_setting('clinic_logo');
        $logoUrl = $logoPath
            ? (str_starts_with($logoPath, 'data:') ? $logoPath : asset('storage/' . $logoPath))
            : null;

        return view('admin.digital-cards.self', compact(
            'user',
            'clinicName',
            'clinicAddress',
            'clinicPhone',
            'logoUrl'
        ));
    }

    /**
     * List all doctors' and staff' digital cards (admin overview).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all'); // all, doctor, staff

        $doctors = collect();
        $staff = collect();

        if ($filter === 'all' || $filter === 'doctor') {
            $doctors = Doctor::with('user')
                ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('doctor_id', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%");
                }))
                ->get();
        }

        if ($filter === 'all' || $filter === 'staff') {
            $staff = Staff::with('user')
                ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('staff_id', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                }))
                ->get();
        }

        $clinicName = get_setting('clinic_name', 'Clinic Management System');
        $logoPath = get_setting('clinic_logo');
        $logoUrl = $logoPath
            ? (str_starts_with($logoPath, 'data:') ? $logoPath : asset('storage/' . $logoPath))
            : null;

        return view('admin.digital-cards.index', compact(
            'doctors',
            'staff',
            'search',
            'filter',
            'clinicName',
            'logoUrl'
        ));
    }

    /**
     * Show a specific doctor or staff digital card (admin viewing).
     */
    public function show(string $type, int $id)
    {
        $clinicName = get_setting('clinic_name', 'Clinic Management System');
        $clinicAddress = get_setting('clinic_address', '');
        $clinicPhone = get_setting('clinic_phone', '');
        $logoPath = get_setting('clinic_logo');
        $logoUrl = $logoPath
            ? (str_starts_with($logoPath, 'data:') ? $logoPath : asset('storage/' . $logoPath))
            : null;

        if ($type === 'doctor') {
            $doctor = Doctor::with('user')->findOrFail($id);
            if (!$doctor->card_issued_at) {
                $doctor->update(['card_issued_at' => now()]);
            }
            return view('admin.digital-cards.show-doctor', compact(
                'doctor',
                'clinicName',
                'clinicAddress',
                'clinicPhone',
                'logoUrl'
            ));
        }

        if ($type === 'staff') {
            $staff = Staff::with('user')->findOrFail($id);
            if (!$staff->card_issued_at) {
                $staff->update(['card_issued_at' => now()]);
            }
            return view('admin.digital-cards.show-staff', compact(
                'staff',
                'clinicName',
                'clinicAddress',
                'clinicPhone',
                'logoUrl'
            ));
        }

        abort(404);
    }

    /**
     * Update admin's own card fields (photo, NRIC, etc.)
     */
    public function updateCard(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nric' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'card_expires_at' => 'nullable|date',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('admin-photos', 'public');
        }

        if (!$user->card_issued_at) {
            $validated['card_issued_at'] = now();
        }

        $user->update($validated);

        return redirect()->route('admin.digital-card.self')
            ->with('success', 'Digital card updated successfully!');
    }
}
