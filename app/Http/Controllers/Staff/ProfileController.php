<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the staff's profile
     */
    public function show()
    {
        $staff = Auth::user()->staff;
        
        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Staff profile not found. Please contact administrator.');
        }

        return view('staff.profile.show', compact('staff'));
    }

    /**
     * Show the form for editing the staff's profile
     */
    public function edit()
    {
        $staff = Auth::user()->staff;
        
        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Staff profile not found. Please contact administrator.');
        }

        return view('staff.profile.edit', compact('staff'));
    }

    /**
     * Update the staff's profile
     */
    public function update(Request $request)
    {
        $staff = Auth::user()->staff;
        
        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Staff profile not found. Please contact administrator.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Update user name if changed
        if ($staff->user) {
            $staff->user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name']
            ]);
        }

        $staff->update($validated);

        return redirect()->route('staff.profile.show')
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

        return redirect()->route('staff.profile.show')
            ->with('success', 'Password updated successfully!');
    }
}

