<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Todo::with(['creator', 'assignedUser']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Show trashed
        if ($request->has('trashed') && $request->trashed == '1') {
            $query->onlyTrashed();
        }

        $todos = $query->latest()->paginate(15);
        $users = User::where('role', 'staff')->get();

        return view('admin.todos.index', compact('todos', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'staff')->get();
        return view('admin.todos.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'assigned_to' => 'nullable|exists:users,id',
            'is_recurring' => 'nullable|boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly',
        ]);

        $validated['created_by'] = Auth::id();

        Todo::create($validated);

        return redirect()->route('admin.todos.index')
            ->with('success', 'To-Do created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        $todo->load(['creator', 'assignedUser']);
        return view('admin.todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        $users = User::where('role', 'staff')->get();
        return view('admin.todos.edit', compact('todo', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'is_recurring' => 'nullable|boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly',
        ]);

        $todo->update($validated);

        return redirect()->route('admin.todos.index')
            ->with('success', 'To-Do updated successfully!');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('admin.todos.index')
            ->with('success', 'To-Do deleted successfully!');
    }

    /**
     * Restore a soft deleted todo.
     */
    public function restore($id)
    {
        $todo = Todo::withTrashed()->findOrFail($id);
        $todo->restore();

        return redirect()->route('admin.todos.index')
            ->with('success', 'To-Do restored successfully!');
    }

    /**
     * Permanently delete a todo.
     */
    public function forceDelete($id)
    {
        $todo = Todo::withTrashed()->findOrFail($id);
        $todo->forceDelete();

        return redirect()->route('admin.todos.index')
            ->with('success', 'To-Do permanently deleted!');
    }
}
