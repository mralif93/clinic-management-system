<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the doctor's profile
     */
    public function show()
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        return view('doctor.profile.show', compact('doctor'));
    }

    /**
     * Show the form for editing the doctor's profile
     */
    public function edit()
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        return view('doctor.profile.edit', compact('doctor'));
    }

    /**
     * Update the doctor's profile
     */
    public function update(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'type' => 'required|in:psychology,homeopathy,general',
            'is_available' => 'boolean',
        ]);

        // Update user name if changed
        if ($doctor->user) {
            $doctor->user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name']
            ]);
        }

        $doctor->update($validated);

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Password updated successfully!');
    }
}

