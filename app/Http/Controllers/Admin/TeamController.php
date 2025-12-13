<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\Page;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of team members
     */
    public function index(Request $request)
    {
        $query = TeamMember::withTrashed();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
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

        $teamMembers = $query->ordered()->paginate(15);

        // Get the Team module page for visibility and order management
        $modulePage = Page::where('type', 'team')->first();

        return view('admin.team.index', compact('teamMembers', 'modulePage'));
    }

    /**
     * Show the form for creating a new team member
     */
    public function create()
    {
        return view('admin.team.create');
    }

    /**
     * Store a newly created team member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Set default order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = TeamMember::max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member created successfully!');
    }

    /**
     * Display the specified team member
     */
    public function show($id)
    {
        $teamMember = TeamMember::withTrashed()->findOrFail($id);
        return view('admin.team.show', compact('teamMember'));
    }

    /**
     * Show the form for editing the specified team member
     */
    public function edit($id)
    {
        $teamMember = TeamMember::withTrashed()->findOrFail($id);
        
        if ($teamMember->trashed()) {
            return redirect()->route('admin.team.index')
                ->with('error', 'Cannot edit a deleted team member. Please restore it first.');
        }
        
        return view('admin.team.edit', compact('teamMember'));
    }

    /**
     * Update the specified team member
     */
    public function update(Request $request, $id)
    {
        $teamMember = TeamMember::withTrashed()->findOrFail($id);
        
        if ($teamMember->trashed()) {
            return redirect()->route('admin.team.index')
                ->with('error', 'Cannot update a deleted team member. Please restore it first.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $teamMember->update($validated);

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member updated successfully!');
    }

    /**
     * Remove the specified team member (soft delete)
     */
    public function destroy($id)
    {
        $teamMember = TeamMember::findOrFail($id);
        $teamMember->delete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member deleted successfully!');
    }

    /**
     * Restore a soft deleted team member
     */
    public function restore($id)
    {
        $teamMember = TeamMember::withTrashed()->findOrFail($id);
        $teamMember->restore();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member restored successfully!');
    }

    /**
     * Permanently delete a team member
     */
    public function forceDelete($id)
    {
        $teamMember = TeamMember::withTrashed()->findOrFail($id);
        $teamMember->forceDelete();

        return redirect()->route('admin.team.index')
            ->with('success', 'Team member permanently deleted!');
    }

    /**
     * Toggle module visibility (published/unpublished)
     */
    public function toggleModuleVisibility()
    {
        $page = Page::where('type', 'team')->first();
        
        if (!$page) {
            return redirect()->route('admin.team.index')
                ->with('error', 'Team page not found.');
        }

        if ($page->is_published) {
            $page->unpublish();
            $message = 'Team module hidden successfully!';
        } else {
            $page->publish();
            $message = 'Team module published successfully!';
        }

        return redirect()->route('admin.team.index')
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

        $page = Page::where('type', 'team')->first();
        
        if (!$page) {
            return redirect()->route('admin.team.index')
                ->with('error', 'Team page not found.');
        }

        $page->update(['order' => $validated['order']]);

        return redirect()->route('admin.team.index')
            ->with('success', 'Team module order updated successfully!');
    }
}
