<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'content',
        'meta_title',
        'meta_description',
        'is_published',
        'order',
        'created_by',
    ];

    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = static::generateSlug($page->title);
            }
            if (empty($page->created_by) && auth()->check()) {
                $page->created_by = auth()->id();
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->getOriginal('slug'))) {
                $page->slug = static::generateSlug($page->title);
            }
        });
    }

    /**
     * Generate unique slug from title
     */
    public static function generateSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the user who created the page
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get URL attribute
     */
    public function getUrlAttribute()
    {
        if ($this->type === 'about') {
            return route('about');
        } elseif ($this->type === 'team') {
            return route('team.index');
        } elseif ($this->type === 'packages') {
            return route('packages.index');
        } elseif ($this->type === 'services') {
            return route('services.index');
        }
        return route('page.show', $this->slug);
    }

    /**
     * Check if page is custom
     */
    public function getIsCustomAttribute()
    {
        return $this->type === 'custom';
    }

    /**
     * Publish the page
     */
    public function publish()
    {
        $this->update(['is_published' => true]);
        return $this;
    }

    /**
     * Unpublish the page
     */
    public function unpublish()
    {
        $this->update(['is_published' => false]);
        return $this;
    }

    /**
     * Scope for published pages
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for draft pages
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope for custom pages
     */
    public function scopeCustom($query)
    {
        return $query->where('type', 'custom');
    }

    /**
     * Scope for system pages
     */
    public function scopeSystem($query)
    {
        return $query->whereIn('type', ['about', 'team', 'packages', 'services']);
    }

    /**
     * Scope for module pages (services, packages, team)
     */
    public function scopeModules($query)
    {
        return $query->whereIn('type', ['services', 'packages', 'team']);
    }

    /**
     * Scope for a specific module type
     */
    public function scopeModuleType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if module is visible (published)
     */
    public static function isModuleVisible($moduleType)
    {
        $page = static::where('type', $moduleType)->first();
        return $page && $page->is_published;
    }

    /**
     * Scope ordered by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }
}
