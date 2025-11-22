<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        'qualification',
        'bio',
        'type',
        'is_available',
    ];

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope for available doctors
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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

        static::creating(function ($doctor) {
            if (empty($doctor->doctor_id)) {
                $doctor->doctor_id = static::generateDoctorId();
            }
        });
    }

    /**
     * Generate unique doctor ID
     */
    protected static function generateDoctorId()
    {
        $lastDoctor = static::withTrashed()->whereNotNull('doctor_id')->orderBy('id', 'desc')->first();
        
        if ($lastDoctor && preg_match('/DOC-(\d+)/', $lastDoctor->doctor_id, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }
        
        do {
            $doctorId = 'DOC-' . str_pad($number, 6, '0', STR_PAD_LEFT);
            $exists = static::withTrashed()->where('doctor_id', $doctorId)->exists();
            $number++;
        } while ($exists);

        return $doctorId;
    }
}

