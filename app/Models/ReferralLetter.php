<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'referred_to_name',
        'referred_to_facility',
        'referred_to_specialty',
        'reason',
        'clinical_notes',
        'urgency',
        'valid_until',
        'status',
        'issued_at',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'issued_at' => 'datetime',
    ];

    const URGENCY_ROUTINE = 'routine';
    const URGENCY_URGENT = 'urgent';
    const URGENCY_EMERGENCY = 'emergency';

    const STATUS_DRAFT = 'draft';
    const STATUS_ISSUED = 'issued';

    public static function urgencyOptions(): array
    {
        return [
            self::URGENCY_ROUTINE => 'Routine',
            self::URGENCY_URGENT => 'Urgent',
            self::URGENCY_EMERGENCY => 'Emergency',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function scopeIssued($query)
    {
        return $query->where('status', self::STATUS_ISSUED);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isIssued(): bool
    {
        return $this->status === self::STATUS_ISSUED;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($letter) {
            if (empty($letter->referral_number)) {
                $letter->referral_number = static::generateReferralNumber();
            }
        });
    }

    protected static function generateReferralNumber(): string
    {
        $last = static::orderBy('id', 'desc')->first();
        $number = $last ? ((int) substr($last->referral_number, 4)) + 1 : 1;

        do {
            $ref = 'REF-' . str_pad($number, 6, '0', STR_PAD_LEFT);
            $exists = static::where('referral_number', $ref)->exists();
            $number++;
        } while ($exists);

        return $ref;
    }
}
