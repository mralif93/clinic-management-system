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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,patient,doctor,staff',
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'The password field is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'role.required' => 'Please select a role.',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => now(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully!',
                    'user' => $user
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
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
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            // Prevent updating deleted users
            if ($user->trashed()) {
                $message = 'Cannot update a deleted user. Please restore it first.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.users.index')
                    ->with('error', $message);
            }
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|in:admin,patient,doctor,staff',
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'role.required' => 'Please select a role.',
            ]);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully!',
                    'user' => $user
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user (soft delete)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent admin from deleting themselves
            if ($user->id === auth()->id()) {
                $message = 'You cannot delete your own account!';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.users.index')
                    ->with('error', $message);
            }

            $user->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully!'
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft deleted user
     */
    public function restore(Request $request, $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            if (!$user->trashed()) {
                $message = 'This user is not deleted.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.users.index')
                    ->with('info', $message);
            }
            
            $user->restore();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User restored successfully!'
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User restored successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to restore user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to restore user: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a user
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            // Prevent admin from permanently deleting themselves
            if ($user->id === auth()->id()) {
                $message = 'You cannot permanently delete your own account!';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->route('admin.users.index')
                    ->with('error', $message);
            }

            $user->forceDelete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User permanently deleted!'
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User permanently deleted!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to permanently delete user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to permanently delete user: ' . $e->getMessage());
        }
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

