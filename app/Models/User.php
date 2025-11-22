<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'failed_login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'locked_until' => 'datetime',
    ];

    /**
     * Check if user account is locked
     */
    public function isLocked()
    {
        if ($this->locked_until && $this->locked_until->isFuture()) {
            return true;
        }
        
        // Auto-unlock if lockout period has passed
        if ($this->locked_until && $this->locked_until->isPast()) {
            $this->unlockAccount();
        }
        
        return false;
    }

    /**
     * Lock the user account
     */
    public function lockAccount($minutes = 30)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'failed_login_attempts' => 5,
        ]);
    }

    /**
     * Unlock the user account
     */
    public function unlockAccount()
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
        ]);
    }

    /**
     * Increment failed login attempts
     */
    public function incrementFailedAttempts()
    {
        $this->increment('failed_login_attempts');
        
        // Lock account after 5 failed attempts
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount(30); // Lock for 30 minutes
        }
    }

    /**
     * Reset failed login attempts on successful login
     */
    public function resetFailedAttempts()
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Get remaining lockout time in minutes (rounded)
     */
    public function getRemainingLockoutMinutes()
    {
        if (!$this->locked_until || $this->locked_until->isPast()) {
            return 0;
        }
        
        $minutes = now()->diffInMinutes($this->locked_until, false);
        return max(0, (int) round($minutes));
    }

    /**
     * Get patient profile (if user is a patient)
     */
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get doctor profile (if user is a doctor)
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get staff profile (if user is staff)
     */
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }
}

