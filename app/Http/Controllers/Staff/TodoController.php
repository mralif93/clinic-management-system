<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of tasks assigned to the logged-in staff member.
     */
    public function index(Request $request)
    {
        $query = Todo::with(['creator', 'assignedUser'])
            ->where('assigned_to', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $todos = $query->latest()->paginate(15);

        return view('staff.todos.index', compact('todos'));
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        $todo = Todo::with(['creator', 'assignedUser'])
            ->where('assigned_to', Auth::id())
            ->findOrFail($id);

        return view('staff.todos.show', compact('todo'));
    }

    /**
     * Update the status of a task.
     */
    public function updateStatus(Request $request, $id)
    {
        $todo = Todo::where('assigned_to', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $todo->update(['status' => $validated['status']]);

        return redirect()->route('staff.todos.index')
            ->with('success', 'Task status updated successfully!');
    }
}
