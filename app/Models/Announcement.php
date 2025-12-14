<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'type',
        'is_featured',
        'is_published',
        'expires_at',
        'order',
        'link_url',
        'link_text',
        'created_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'expires_at' => 'datetime',
        'order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (empty($announcement->created_by) && auth()->check()) {
                $announcement->created_by = auth()->id();
            }
        });
    }

    /**
     * Get the user who created the announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for published announcements
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for draft announcements
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope for featured announcements
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for active (not expired) announcements
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for news type
     */
    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    /**
     * Scope for announcement type
     */
    public function scopeAnnouncements($query)
    {
        return $query->where('type', 'announcement');
    }

    /**
     * Check if announcement is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Check if image is base64 (for Vercel) or file path (for local)
        if (str_starts_with($this->image, 'data:')) {
            return $this->image; // Base64 data URI
        }

        return asset('storage/' . $this->image); // File path
    }

    /**
     * Publish the announcement
     */
    public function publish()
    {
        $this->update(['is_published' => true]);
        return $this;
    }

    /**
     * Unpublish the announcement
     */
    public function unpublish()
    {
        $this->update(['is_published' => false]);
        return $this;
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);
        return $this;
    }

    /**
     * Scope ordered by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }
}
