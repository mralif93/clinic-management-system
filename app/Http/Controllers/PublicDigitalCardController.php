<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Staff;
use Illuminate\Http\Request;

class PublicDigitalCardController extends Controller
{
    /**
     * Display a doctor's public digital card for verification.
     */
    public function verifyDoctor($doctor_id)
    {
        $doctor = Doctor::where('doctor_id', $doctor_id)->firstOrFail();

        // Check if card is expired
        $isExpired = $doctor->card_expires_at && $doctor->card_expires_at->isPast();

        return view('public.digital-card', [
            'type' => 'Doctor',
            'person' => $doctor,
            'id' => $doctor->doctor_id,
            'name' => $doctor->full_name,
            'photo' => $doctor->profile_photo,
            'title' => $doctor->specialization ?? ucfirst($doctor->type),
            'department' => 'Medical',
            'location' => $doctor->clinic_location,
            'issued_at' => $doctor->card_issued_at,
            'expires_at' => $doctor->card_expires_at,
            'isExpired' => $isExpired,
            'clinicName' => get_setting('clinic_name', 'Clinic Management System'),
            'logoUrl' => $this->getLogoUrl(),
            'theme' => 'emerald' // Green theme for doctors
        ]);
    }

    /**
     * Display a staff's public digital card for verification.
     */
    public function verifyStaff($staff_id)
    {
        $staff = Staff::where('staff_id', $staff_id)->firstOrFail();

        // Check if card is expired
        $isExpired = $staff->card_expires_at && $staff->card_expires_at->isPast();

        return view('public.digital-card', [
            'type' => 'Staff',
            'person' => $staff,
            'id' => $staff->staff_id,
            'name' => $staff->full_name,
            'photo' => $staff->profile_photo,
            'title' => $staff->position ?? 'Staff',
            'department' => $staff->department ?? 'Operations',
            'location' => $staff->clinic_location,
            'issued_at' => $staff->card_issued_at,
            'expires_at' => $staff->card_expires_at,
            'isExpired' => $isExpired,
            'clinicName' => get_setting('clinic_name', 'Clinic Management System'),
            'logoUrl' => $this->getLogoUrl(),
            'theme' => 'amber' // Amber theme for staff
        ]);
    }

    /**
     * Helper to get the public clinic logo URL.
     */
    private function getLogoUrl()
    {
        $logoPath = get_setting('clinic_logo');
        if ($logoPath && str_starts_with($logoPath, 'data:')) {
            return $logoPath;
        } elseif ($logoPath) {
            return asset('storage/' . $logoPath);
        }
        return null;
    }
}
