<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $query = Service::withTrashed();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status (active/inactive)
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by deleted status
        if ($request->has('deleted') && $request->deleted === '1') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:psychology,homeopathy,general',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Service::withTrashed()->where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully!');
    }

    /**
     * Display the specified service
     */
    public function show($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        
        if ($service->trashed()) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Cannot edit a deleted service. Please restore it first.');
        }
        
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, $id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        
        if ($service->trashed()) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Cannot update a deleted service. Please restore it first.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:psychology,homeopathy,general',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($service->name !== $validated['name']) {
            $newSlug = Str::slug($validated['name']);
            
            // Ensure slug is unique (excluding current service)
            $originalSlug = $newSlug;
            $counter = 1;
            while (Service::withTrashed()->where('slug', $newSlug)->where('id', '!=', $service->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $newSlug;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service (soft delete)
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully!');
    }

    /**
     * Restore a soft deleted service
     */
    public function restore($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        $service->restore();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service restored successfully!');
    }

    /**
     * Permanently delete a service
     */
    public function forceDelete($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        $service->forceDelete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service permanently deleted!');
    }

    /**
     * Toggle module visibility (published/unpublished)
     */
    public function toggleModuleVisibility()
    {
        $page = Page::where('type', 'services')->first();
        
        if (!$page) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Services page not found.');
        }

        if ($page->is_published) {
            $page->unpublish();
            $message = 'Services module hidden successfully!';
        } else {
            $page->publish();
            $message = 'Services module published successfully!';
        }

        return redirect()->route('admin.services.index')
            ->with('success', $message);
    }

    /**
     * Update module order
     */
    public function updateModuleOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|integer|min:0',
        ]);

        $page = Page::where('type', 'services')->first();
        
        if (!$page) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Services page not found.');
        }

        $page->update(['order' => $validated['order']]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Services module order updated successfully!');
    }
}

