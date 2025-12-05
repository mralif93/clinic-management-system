<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Leave type constants
     */
    const TYPE_SICK = 'sick';
    const TYPE_ANNUAL = 'annual';
    const TYPE_EMERGENCY = 'emergency';
    const TYPE_UNPAID = 'unpaid';
    const TYPE_MATERNITY = 'maternity';
    const TYPE_PATERNITY = 'paternity';

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get all leave types
     */
    public static function getLeaveTypes()
    {
        return [
            self::TYPE_SICK => 'Sick Leave',
            self::TYPE_ANNUAL => 'Annual Leave',
            self::TYPE_EMERGENCY => 'Emergency Leave',
            self::TYPE_UNPAID => 'Unpaid Leave',
            self::TYPE_MATERNITY => 'Maternity Leave',
            self::TYPE_PATERNITY => 'Paternity Leave',
        ];
    }

    /**
     * Check if there is an overlapping leave for a user
     */
    public static function hasOverlap(int $userId, $startDate, $endDate, ?int $ignoreId = null): bool
    {
        return static::where('user_id', $userId)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', self::STATUS_APPROVED);
    }

    /**
     * Helper methods
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isActive()
    {
        return $this->isApproved() &&
            $this->start_date <= now() &&
            $this->end_date >= now();
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'approved' => 'bg-green-100 text-green-700 border-green-300',
            'rejected' => 'bg-red-100 text-red-700 border-red-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    /**
     * Get leave type badge color
     */
    public function getTypeColorAttribute()
    {
        return match ($this->leave_type) {
            'sick' => 'bg-red-100 text-red-700 border-red-300',
            'annual' => 'bg-blue-100 text-blue-700 border-blue-300',
            'emergency' => 'bg-orange-100 text-orange-700 border-orange-300',
            'unpaid' => 'bg-gray-100 text-gray-700 border-gray-300',
            'maternity' => 'bg-pink-100 text-pink-700 border-pink-300',
            'paternity' => 'bg-purple-100 text-purple-700 border-purple-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
}
