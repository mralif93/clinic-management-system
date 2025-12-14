<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'patient_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'medical_history',
        'allergies',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';
        $fullName = trim("{$firstName} {$lastName}");
        return $fullName ?: 'N/A';
    }

    /**
     * Get appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                $patient->patient_id = static::generatePatientId();
            }
        });
    }

    /**
     * Generate unique patient ID
     */
    protected static function generatePatientId()
    {
        $lastPatient = static::withTrashed()->whereNotNull('patient_id')->orderBy('id', 'desc')->first();
        
        if ($lastPatient && preg_match('/PAT-(\d+)/', $lastPatient->patient_id, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }
        
        do {
            $patientId = 'PAT-' . str_pad($number, 6, '0', STR_PAD_LEFT);
            $exists = static::withTrashed()->where('patient_id', $patientId)->exists();
            $number++;
        } while ($exists);

        return $patientId;
    }
}

