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
        'discount_type',
        'discount_value',
        'payment_status',
        'payment_method',
        'confirmation_token',
        'confirmed_at',
        'arrived_at',
        'accepted_by',
        'accepted_at',
        'room_number',
        'record_approved_by',
        'record_approved_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'fee' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'arrived_at' => 'datetime',
        'accepted_at' => 'datetime',
        'record_approved_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';

    const STATUS_SCHEDULED = 'scheduled';

    const STATUS_ARRIVED = 'arrived';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_IN_PROGRESS = 'in_progress';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    const STATUS_NO_SHOW = 'no_show';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending Confirmation',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_ARRIVED => 'Arrived',
            self::STATUS_CONFIRMED => 'Checked In',
            self::STATUS_IN_PROGRESS => 'In Consultation',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_NO_SHOW => 'No Show',
        ];
    }

    /**
     * Determine if the appointment time conflicts with an existing booking
     */
    public static function hasConflict(?int $doctorId, ?int $patientId, $appointmentDate, string $appointmentTime, ?int $ignoreId = null): bool
    {
        $baseQuery = static::whereDate('appointment_date', $appointmentDate)
            ->where('appointment_time', $appointmentTime)
            ->whereNotIn('status', ['cancelled'])
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId));

        if ($doctorId && (clone $baseQuery)->where('doctor_id', $doctorId)->exists()) {
            return true;
        }

        if ($patientId && (clone $baseQuery)->where('patient_id', $patientId)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Calculate discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if (! $this->discount_type || ! $this->discount_value || ! $this->fee) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return round(($this->fee * $this->discount_value) / 100, 2);
        }

        return min($this->discount_value, $this->fee);
    }

    /**
     * Calculate final amount after discount
     */
    public function getFinalAmountAttribute()
    {
        $fee = $this->fee ?? 0;
        $discount = $this->discount_amount;

        return max(0, $fee - $discount);
    }

    /**
     * Get formatted discount display
     */
    public function getDiscountDisplayAttribute()
    {
        if (! $this->discount_type || ! $this->discount_value) {
            return null;
        }

        if ($this->discount_type === 'percentage') {
            return $this->discount_value.'%';
        }

        return get_currency_symbol().number_format($this->discount_value, 2);
    }

    /**
     * Calculate doctor's commission amount
     */
    public function getDoctorCommissionAttribute()
    {
        if (! $this->doctor || ! $this->doctor->user) {
            return 0;
        }

        // Only calculate commission for locum doctors
        if ($this->doctor->user->employment_type !== 'locum') {
            return 0;
        }

        $commissionRate = $this->doctor->commission_rate ?? 60;
        $finalAmount = $this->final_amount ?? $this->fee ?? 0;

        return round(($finalAmount * $commissionRate) / 100, 2);
    }

    /**
     * Get doctor's commission rate
     */
    public function getDoctorCommissionRateAttribute()
    {
        if (! $this->doctor) {
            return 0;
        }

        return $this->doctor->commission_rate ?? 0;
    }

    /**
     * Check if doctor is locum
     */
    public function getIsLocumDoctorAttribute()
    {
        if (! $this->doctor || ! $this->doctor->user) {
            return false;
        }

        return $this->doctor->user->employment_type === 'locum';
    }

    /**
     * Get payment status options
     */
    public static function getPaymentStatuses()
    {
        return [
            'unpaid' => 'Unpaid',
            'paid' => 'Paid',
            'partial' => 'Partial',
        ];
    }

    /**
     * Get payment method options
     */
    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Cash',
            'card' => 'Card',
            'online' => 'Online Transfer',
            'insurance' => 'Insurance',
        ];
    }

    public static function generateConfirmationToken(): string
    {
        do {
            $token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
        } while (static::where('confirmation_token', $token)->exists());

        return $token;
    }

    public function isConfirmed(): bool
    {
        return in_array($this->status, ['scheduled', 'arrived', 'confirmed', 'in_progress', 'completed']) || $this->confirmed_at !== null;
    }

    public function hasArrived(): bool
    {
        return $this->status === self::STATUS_ARRIVED || $this->arrived_at !== null;
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function getQrCodeData(): string
    {
        return json_encode([
            'token' => $this->confirmation_token,
            'appointment_id' => $this->id,
            'patient_name' => $this->patient?->name ?? $this->patient?->user?->name,
            'doctor' => $this->doctor?->user?->name,
            'service' => $this->service?->name,
            'date' => $this->appointment_date?->format('Y-m-d'),
            'time' => $this->appointment_time,
            'status' => $this->status,
        ]);
    }

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

    /**
     * Get user who accepted the patient
     */
    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    /**
     * Get user (doctor) who approved the medical record.
     */
    public function recordApprovedBy()
    {
        return $this->belongsTo(User::class, 'record_approved_by');
    }
}
