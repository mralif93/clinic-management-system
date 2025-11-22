<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        // If user exists, check if account is locked
        if ($user && $user->isLocked()) {
            $remainingMinutes = $user->getRemainingLockoutMinutes();
            throw ValidationException::withMessages([
                'email' => ["Your account has been locked due to multiple failed login attempts. Please try again in {$remainingMinutes} minute(s)."],
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Reset failed login attempts on successful login
            $user = Auth::user();
            $user->resetFailedAttempts();

            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'doctor') {
                return redirect()->intended(route('doctor.dashboard'));
            } elseif ($user->role === 'staff') {
                return redirect()->intended(route('staff.dashboard'));
            } else {
                // Patient
                return redirect()->intended(route('patient.dashboard'));
            }
        }

        // Increment failed login attempts if user exists
        if ($user) {
            $user->incrementFailedAttempts();
            
            // Check if account was just locked
            $user->refresh();
            if ($user->isLocked()) {
                throw ValidationException::withMessages([
                    'email' => ['Your account has been locked due to 5 failed login attempts. Please try again in 30 minutes.'],
                ]);
            }
            
            // Show remaining attempts
            $remainingAttempts = 5 - $user->failed_login_attempts;
            if ($remainingAttempts > 0) {
                throw ValidationException::withMessages([
                    'email' => ["The provided credentials do not match our records. You have {$remainingAttempts} attempt(s) remaining."],
                ]);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient', // Default role
        ]);

        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        } elseif ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        } else {
            // Patient
            return redirect()->route('patient.dashboard');
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request
     * Note: Email functionality is not yet implemented
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Email service is not ready yet
        return back()->with('status', 'Email hosting is not yet configured. Please contact the administrator to reset your password.');
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset
     * Note: Email functionality is not yet implemented
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Email service is not ready yet
        return back()->with('status', 'Email hosting is not yet configured. Please contact the administrator to reset your password.');
    }
}

