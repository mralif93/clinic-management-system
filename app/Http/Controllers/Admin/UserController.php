<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::withTrashed();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status (active/deleted/locked)
        if ($request->has('status')) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } elseif ($request->status === 'locked') {
                $query->whereNotNull('locked_until')
                      ->where('locked_until', '>', now())
                      ->whereNull('deleted_at');
            } else {
                $query->whereNull('deleted_at');
            }
        } else {
            $query->whereNull('deleted_at');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,patient,doctor,staff',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent editing deleted users
        if ($user->trashed()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot edit a deleted user. Please restore it first.');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent updating deleted users
        if ($user->trashed()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot update a deleted user. Please restore it first.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,patient,doctor,staff',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user (soft delete)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Restore a soft deleted user
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'User restored successfully!');
    }

    /**
     * Permanently delete a user
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Prevent admin from permanently deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot permanently delete your own account!');
        }

        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User permanently deleted!');
    }

    /**
     * Unlock a locked user account
     */
    public function unlock($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        if (!$user->isLocked()) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('info', 'This account is not locked.');
        }

        $user->unlockAccount();

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User account unlocked successfully!');
    }

    /**
     * Reset failed login attempts for a user
     */
    public function resetAttempts($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        $user->resetFailedAttempts();

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Failed login attempts reset successfully!');
    }
}

