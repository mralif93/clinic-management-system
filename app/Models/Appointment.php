<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'user_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'diagnosis',
        'prescription',
        'fee',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'fee' => 'decimal:2',
    ];

    /**
     * Get patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get user (patient who booked)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

