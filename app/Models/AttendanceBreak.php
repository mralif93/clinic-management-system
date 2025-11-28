<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
        'break_type',
    ];

    protected $casts = [
        'break_start' => 'datetime',
        'break_end' => 'datetime',
    ];

    /**
     * Break type constants
     */
    const TYPE_LUNCH = 'lunch';
    const TYPE_TEA = 'tea';
    const TYPE_PERSONAL = 'personal';

    /**
     * Relationships
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Helper methods
     */
    public function isActive()
    {
        return $this->break_start && !$this->break_end;
    }

    public function getDuration()
    {
        if (!$this->break_end) {
            return null;
        }

        return $this->break_start->diffInMinutes($this->break_end);
    }

    public function getDurationFormatted()
    {
        $minutes = $this->getDuration();

        if (!$minutes) {
            return 'Ongoing';
        }

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return "{$mins}m";
    }
}
