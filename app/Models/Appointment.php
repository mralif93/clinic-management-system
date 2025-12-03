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
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'fee' => 'decimal:2',
        'discount_value' => 'decimal:2',
    ];

    /**
     * Calculate discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if (!$this->discount_type || !$this->discount_value || !$this->fee) {
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
        if (!$this->discount_type || !$this->discount_value) {
            return null;
        }

        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        }

        return get_currency_symbol() . number_format($this->discount_value, 2);
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

