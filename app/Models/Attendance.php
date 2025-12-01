<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in_time',
        'clock_out_time',
        'clock_in_location',
        'clock_out_location',
        'status',
        'total_hours',
        'break_duration',
        'notes',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
        'total_hours' => 'decimal:2',
    ];

    /**
     * Status constants
     */
    const STATUS_PRESENT = 'present';
    const STATUS_LATE = 'late';
    const STATUS_HALF_DAY = 'half_day';
    const STATUS_ABSENT = 'absent';
    const STATUS_ON_LEAVE = 'on_leave';

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }

    /**
     * Scopes
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeClockedIn($query)
    {
        return $query->whereNotNull('clock_in_time')
            ->whereNull('clock_out_time');
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Helper methods
     */
    public function isClockedIn()
    {
        return $this->clock_in_time && !$this->clock_out_time;
    }

    public function isClockedOut()
    {
        return $this->clock_in_time && $this->clock_out_time;
    }

    public function isLate()
    {
        // Assuming work starts at 9:00 AM
        $scheduledTime = Carbon::parse($this->date->format('Y-m-d') . ' 09:00:00');
        return $this->clock_in_time->gt($scheduledTime->addMinutes(15)); // 15 min grace period
    }

    public function calculateTotalHours()
    {
        if (!$this->clock_out_time) {
            return null;
        }

        $minutes = $this->clock_in_time->diffInMinutes($this->clock_out_time);
        $minutes -= $this->break_duration;

        return round($minutes / 60, 2);
    }

    public function updateTotalHours()
    {
        $this->total_hours = $this->calculateTotalHours();
        $this->save();
    }

    public function getCurrentBreak()
    {
        return $this->breaks()->whereNull('break_end')->first();
    }

    public function isOnBreak()
    {
        return $this->getCurrentBreak() !== null;
    }

    public function getWorkDuration()
    {
        if (!$this->clock_in_time) {
            return '0h 0m';
        }

        $end = $this->clock_out_time ?? now();
        $minutes = $this->clock_in_time->diffInMinutes($end) - $this->break_duration;

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return "{$hours}h {$mins}m";
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'present' => 'bg-green-100 text-green-700 border-green-300',
            'late' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'half_day' => 'bg-blue-100 text-blue-700 border-blue-300',
            'absent' => 'bg-red-100 text-red-700 border-red-300',
            'on_leave' => 'bg-purple-100 text-purple-700 border-purple-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
}
