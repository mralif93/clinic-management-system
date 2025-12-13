<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index(Request $request)
    {
        $query = Package::withTrashed();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
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

        $packages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sessions' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Package::withTrashed()->where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully!');
    }

    /**
     * Display the specified package
     */
    public function show($id)
    {
        $package = Package::withTrashed()->findOrFail($id);
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit($id)
    {
        $package = Package::withTrashed()->findOrFail($id);
        
        if ($package->trashed()) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Cannot edit a deleted package. Please restore it first.');
        }
        
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, $id)
    {
        $package = Package::withTrashed()->findOrFail($id);
        
        if ($package->trashed()) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Cannot update a deleted package. Please restore it first.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sessions' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($package->name !== $validated['name']) {
            $newSlug = Str::slug($validated['name']);
            
            // Ensure slug is unique (excluding current package)
            $originalSlug = $newSlug;
            $counter = 1;
            while (Package::withTrashed()->where('slug', $newSlug)->where('id', '!=', $package->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $newSlug;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully!');
    }

    /**
     * Remove the specified package (soft delete)
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully!');
    }

    /**
     * Restore a soft deleted package
     */
    public function restore($id)
    {
        $package = Package::withTrashed()->findOrFail($id);
        $package->restore();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package restored successfully!');
    }

    /**
     * Permanently delete a package
     */
    public function forceDelete($id)
    {
        $package = Package::withTrashed()->findOrFail($id);
        $package->forceDelete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package permanently deleted!');
    }

    /**
     * Toggle module visibility (published/unpublished)
     */
    public function toggleModuleVisibility()
    {
        $page = Page::where('type', 'packages')->first();
        
        if (!$page) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Packages page not found.');
        }

        if ($page->is_published) {
            $page->unpublish();
            $message = 'Packages module hidden successfully!';
        } else {
            $page->publish();
            $message = 'Packages module published successfully!';
        }

        return redirect()->route('admin.packages.index')
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

        $page = Page::where('type', 'packages')->first();
        
        if (!$page) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Packages page not found.');
        }

        $page->update(['order' => $validated['order']]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Packages module order updated successfully!');
    }
}
