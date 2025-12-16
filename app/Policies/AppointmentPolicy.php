<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'staff' || $user->role === 'doctor';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'admin' || $user->role === 'staff') {
            return true;
        }

        if ($user->role === 'doctor') {
            return $appointment->doctor_id === $user->doctor->id;
        }

        if ($user->role === 'patient') {
            return $appointment->patient_id === $user->patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create appointments (patients book, staff/admin schedule)
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'admin' || $user->role === 'staff') {
            return true;
        }

        if ($user->role === 'doctor') {
            // Doctors can update their own appointments
            return $appointment->doctor_id === $user->doctor->id;
        }

        // Patients can only 'update' by cancelling, which is handled separately or via update with specific logic
        // But generally 'update' implies editing details. Patients unlikely to edit details directly.
        // Keeping it false for patients for generic update, specific actions like cancel handled in controller logic or separate policy method
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can cancel the appointment.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'admin' || $user->role === 'staff') {
            return true;
        }

        if ($user->role === 'patient') {
            return $appointment->patient_id === $user->patient->id;
        }

        return false;
    }
}
