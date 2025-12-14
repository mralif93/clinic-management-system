<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Symfony\Component\HttpFoundation\Response;

class StaffCheckInMiddleware
{
    /**
     * Routes that are excluded from check-in requirement
     */
    protected $excludedRoutes = [
        'staff.check-in',
        'staff.check-in.store',
        'staff.attendance.clock-in',
        'logout',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to staff users
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return $next($request);
        }

        // Skip check for excluded routes
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, $this->excludedRoutes)) {
            return $next($request);
        }

        // Check if staff has clocked in today
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        // If not clocked in, redirect to check-in page
        if (!$todayAttendance) {
            return redirect()->route('staff.check-in')
                ->with('warning', 'Please check in before accessing the system.');
        }

        return $next($request);
    }
}

