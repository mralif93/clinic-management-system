<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        $query = Announcement::withTrashed()->with('creator');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status (published/draft)
        if ($request->has('status') && $request->status) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured === '1') {
            $query->where('is_featured', true);
        }

        // Filter by expired
        if ($request->has('expired') && $request->expired === '1') {
            $query->whereNotNull('expires_at')->where('expires_at', '<', now());
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by deleted status
        if ($request->has('deleted') && $request->deleted === '1') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $announcements = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // 5MB max
            'type' => 'required|in:news,announcement',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
            'order' => 'nullable|integer|min:0',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:255',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            try {
                // For Vercel/serverless: Use base64 encoding and store in database
                // For local development: Use file storage
                $isVercel = env('VERCEL') === '1' || env('VERCEL_ENV') !== null || str_contains(env('APP_URL', ''), 'vercel.app');
                if ($isVercel || env('APP_ENV') === 'production') {
                    // Vercel/serverless environment - store as base64 in database
                    $imageData = file_get_contents($file->getRealPath());
                    $base64 = base64_encode($imageData);
                    $mimeType = $file->getMimeType();
                    $validated['image'] = 'data:' . $mimeType . ';base64,' . $base64;
                } else {
                    // Local development - use file storage
                    $imagePath = $file->store('announcements', 'public');
                    $validated['image'] = $imagePath;
                }
            } catch (\Exception $e) {
                return redirect()->route('admin.announcements.create')
                    ->with('error', 'Failed to upload image: ' . $e->getMessage())
                    ->withInput();
            }
        }

        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['order'] = $validated['order'] ?? 0;

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Display the specified announcement
     */
    public function show($id)
    {
        $announcement = Announcement::withTrashed()->with('creator')->findOrFail($id);
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        
        if ($announcement->trashed()) {
            return redirect()->route('admin.announcements.index')
                ->with('error', 'Cannot edit a deleted announcement. Please restore it first.');
        }
        
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        
        if ($announcement->trashed()) {
            return redirect()->route('admin.announcements.index')
                ->with('error', 'Cannot update a deleted announcement. Please restore it first.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // 5MB max
            'type' => 'required|in:news,announcement',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:255',
            'remove_image' => 'boolean',
        ]);

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            $oldImage = $announcement->image;
            if ($oldImage && !str_starts_with($oldImage, 'data:')) {
                if (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $validated['image'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            try {
                // Delete old image if exists
                $oldImage = $announcement->image;
                if ($oldImage && !str_starts_with($oldImage, 'data:')) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }

                // For Vercel/serverless: Use base64 encoding and store in database
                // For local development: Use file storage
                $isVercel = env('VERCEL') === '1' || env('VERCEL_ENV') !== null || str_contains(env('APP_URL', ''), 'vercel.app');
                if ($isVercel || env('APP_ENV') === 'production') {
                    // Vercel/serverless environment - store as base64 in database
                    $imageData = file_get_contents($file->getRealPath());
                    $base64 = base64_encode($imageData);
                    $mimeType = $file->getMimeType();
                    $validated['image'] = 'data:' . $mimeType . ';base64,' . $base64;
                } else {
                    // Local development - use file storage
                    $imagePath = $file->store('announcements', 'public');
                    $validated['image'] = $imagePath;
                }
            } catch (\Exception $e) {
                return redirect()->route('admin.announcements.edit', $announcement->id)
                    ->with('error', 'Failed to upload image: ' . $e->getMessage())
                    ->withInput();
            }
        } else {
            // Keep existing image if not uploading new one
            unset($validated['image']);
        }

        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['order'] = $validated['order'] ?? $announcement->order;

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement (soft delete)
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Restore a soft deleted announcement
     */
    public function restore($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        $announcement->restore();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement restored successfully!');
    }

    /**
     * Permanently delete an announcement
     */
    public function forceDelete($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        
        // Delete image if exists
        $oldImage = $announcement->image;
        if ($oldImage && !str_starts_with($oldImage, 'data:')) {
            if (Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }
        
        $announcement->forceDelete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement permanently deleted!');
    }

    /**
     * Toggle publish/unpublish status
     */
    public function togglePublish($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        if ($announcement->is_published) {
            $announcement->unpublish();
            $message = 'Announcement unpublished successfully!';
        } else {
            $announcement->publish();
            $message = 'Announcement published successfully!';
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', $message);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->toggleFeatured();

        $message = $announcement->is_featured 
            ? 'Announcement marked as featured!' 
            : 'Announcement unmarked as featured!';

        return redirect()->route('admin.announcements.index')
            ->with('success', $message);
    }
}
