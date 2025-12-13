<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index(Request $request)
    {
        $query = Page::withTrashed()->with('creator');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('meta_title', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Filter by deleted status
        if ($request->has('deleted') && $request->deleted === '1') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $pages = $query->ordered()->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'type' => 'required|in:custom,about,team,packages',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Parse content JSON string to array
        if (!empty($validated['content'])) {
            $decoded = json_decode($validated['content'], true);
            $validated['content'] = $decoded !== null ? $decoded : [];
        } else {
            $validated['content'] = null;
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Page::generateSlug($validated['title']);
        }

        // Set default order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = Page::max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        // Set published status
        $validated['is_published'] = $request->has('is_published') ? true : false;

        // Set created_by
        $validated['created_by'] = auth()->id();

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        $page->load('creator');
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page)
    {
        if ($page->trashed()) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot edit a deleted page. Please restore it first.');
        }

        // For system pages (about, team, packages), redirect to their dedicated edit pages
        if (in_array($page->type, ['about', 'team', 'packages'])) {
            return redirect()->route('admin.pages.' . $page->type)
                ->with('info', 'This page is managed through its dedicated editor.');
        }
        
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, Page $page)
    {
        
        if ($page->trashed()) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Cannot update a deleted page. Please restore it first.');
        }

        // System pages should be edited through their dedicated editors
        if (in_array($page->type, ['about', 'team', 'packages'])) {
            return redirect()->route('admin.pages.' . $page->type)
                ->with('info', 'This page is managed through its dedicated editor.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($page->id),
            ],
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Parse content JSON string to array
        if (!empty($validated['content'])) {
            $decoded = json_decode($validated['content'], true);
            $validated['content'] = $decoded !== null ? $decoded : ($page->content ?? []);
        } else {
            $validated['content'] = $page->content ?? null;
        }

        // Update slug if changed
        if (isset($validated['slug']) && $page->slug !== $validated['slug']) {
            $validated['slug'] = Page::generateSlug($validated['slug'], $page->id);
        } elseif (empty($validated['slug']) && $page->title !== $validated['title']) {
            // Regenerate slug if title changed and slug is empty
            $validated['slug'] = Page::generateSlug($validated['title'], $page->id);
        }

        // Set published status
        if ($request->has('is_published')) {
            $validated['is_published'] = true;
        } else {
            $validated['is_published'] = false;
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified page (soft delete)
     */
    public function destroy(Page $page)
    {

        // Prevent deletion of system pages
        if (in_array($page->type, ['about', 'team', 'packages'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'System pages cannot be deleted.');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Restore a soft deleted page
     */
    public function restore($id)
    {
        $page = Page::withTrashed()->findOrFail($id);
        $page->restore();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page restored successfully!');
    }

    /**
     * Permanently delete a page
     */
    public function forceDelete($id)
    {
        $page = Page::withTrashed()->findOrFail($id);

        // Prevent deletion of system pages
        if (in_array($page->type, ['about', 'team', 'packages'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'System pages cannot be permanently deleted.');
        }

        $page->forceDelete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page permanently deleted!');
    }

    /**
     * Duplicate a page
     */
    public function duplicate($id)
    {
        $page = Page::findOrFail($id);

        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Page::generateSlug($newPage->title);
        $newPage->is_published = false;
        $newPage->created_by = auth()->id();
        $newPage->save();

        return redirect()->route('admin.pages.edit', $newPage->id)
            ->with('success', 'Page duplicated successfully!');
    }

    /**
     * Toggle page published status
     */
    public function toggleStatus($id)
    {
        $page = Page::findOrFail($id);
        
        if ($page->is_published) {
            $page->unpublish();
            $message = 'Page unpublished successfully!';
        } else {
            $page->publish();
            $message = 'Page published successfully!';
        }

        return redirect()->route('admin.pages.index')
            ->with('success', $message);
    }

    /**
     * Reorder pages
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'pages' => 'required|array',
            'pages.*.id' => 'required|exists:pages,id',
            'pages.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->pages as $pageData) {
            Page::where('id', $pageData['id'])
                ->update(['order' => $pageData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pages reordered successfully!',
        ]);
    }
}
