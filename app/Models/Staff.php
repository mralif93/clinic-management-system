<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'staff_id',
        'first_name',
        'last_name',
        'phone',
        'position',
        'department',
        'hire_date',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
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

        static::creating(function ($staff) {
            if (empty($staff->staff_id)) {
                $staff->staff_id = static::generateStaffId();
            }
        });
    }

    /**
     * Generate unique staff ID
     */
    protected static function generateStaffId()
    {
        $lastStaff = static::withTrashed()->whereNotNull('staff_id')->orderBy('id', 'desc')->first();
        
        if ($lastStaff && preg_match('/STF-(\d+)/', $lastStaff->staff_id, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }
        
        do {
            $staffId = 'STF-' . str_pad($number, 6, '0', STR_PAD_LEFT);
            $exists = static::withTrashed()->where('staff_id', $staffId)->exists();
            $number++;
        } while ($exists);

        return $staffId;
    }
}

