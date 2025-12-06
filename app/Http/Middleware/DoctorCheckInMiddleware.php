<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Symfony\Component\HttpFoundation\Response;

class DoctorCheckInMiddleware
{
    /**
     * Routes that are excluded from check-in requirement
     */
    protected $excludedRoutes = [
        'doctor.check-in',
        'doctor.check-in.store',
        'doctor.attendance.clock-in',
        'logout',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to doctor users
        if (!Auth::check() || Auth::user()->role !== 'doctor') {
            return $next($request);
        }

        // Skip check for excluded routes
        $currentRoute = $request->route()->getName();
        if (in_array($currentRoute, $this->excludedRoutes)) {
            return $next($request);
        }

        // Check if doctor has clocked in today
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        // If not clocked in, redirect to check-in page
        if (!$todayAttendance) {
            return redirect()->route('doctor.check-in')
                ->with('warning', 'Please check in before accessing the system.');
        }

        return $next($request);
    }
}

