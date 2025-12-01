<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'pay_period_start',
        'pay_period_end',
        'basic_salary',
        'allowances',
        'deductions',
        'overtime_hours',
        'overtime_pay',
        'gross_salary',
        'net_salary',
        'status',
        'payment_date',
        'payment_method',
        'payment_reference',
        'notes',
        'generated_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
        'allowances' => 'array',
        'deductions' => 'array',
        'basic_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_APPROVED = 'approved';
    const STATUS_PAID = 'paid';

    /**
     * Payment method constants
     */
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_CASH = 'cash';
    const PAYMENT_CHEQUE = 'cheque';

    /**
     * Get the user (employee) this payroll belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who generated this payroll
     */
    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Get the admin who approved this payroll
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by pay period
     */
    public function scopeByPayPeriod($query, $start, $end)
    {
        return $query->whereBetween('pay_period_start', [$start, $end]);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800 border-gray-300',
            'approved' => 'bg-green-100 text-green-800 border-green-300',
            'paid' => 'bg-blue-100 text-blue-800 border-blue-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /**
     * Get formatted pay period
     */
    public function getPayPeriodAttribute()
    {
        if (!$this->pay_period_start || !$this->pay_period_end) {
            return 'N/A';
        }
        return $this->pay_period_start->format('M d, Y') . ' - ' . $this->pay_period_end->format('M d, Y');
    }

    /**
     * Get total allowances
     */
    public function getTotalAllowancesAttribute()
    {
        if (!$this->allowances) {
            return 0;
        }
        return array_sum($this->allowances);
    }

    /**
     * Get total deductions
     */
    public function getTotalDeductionsAttribute()
    {
        if (!$this->deductions) {
            return 0;
        }
        return array_sum($this->deductions);
    }

    /**
     * Get payment method label
     */
    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_BANK_TRANSFER => 'Bank Transfer',
            self::PAYMENT_CASH => 'Cash',
            self::PAYMENT_CHEQUE => 'Cheque',
        ];
    }

    /**
     * Get status label
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_PAID => 'Paid',
        ];
    }
}
