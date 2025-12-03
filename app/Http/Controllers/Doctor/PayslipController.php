<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayslipController extends Controller
{
    /**
     * Display a listing of the authenticated user's payslips.
     */
    public function index(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');

        $query = Payroll::where('user_id', Auth::id())
            ->whereIn('status', ['approved', 'paid']);

        // Filter by month (Laravel's whereMonth is cross-database compatible)
        if ($month) {
            $query->whereMonth('pay_period_start', $month);
        }

        // Filter by year (Laravel's whereYear is cross-database compatible)
        if ($year) {
            $query->whereYear('pay_period_start', $year);
        }

        $payslips = $query->orderBy('pay_period_end', 'desc')->paginate(12);

        // Get available years for filter (database-agnostic approach)
        $years = Payroll::where('user_id', Auth::id())
            ->whereIn('status', ['approved', 'paid'])
            ->whereNotNull('pay_period_start')
            ->pluck('pay_period_start')
            ->map(fn($date) => Carbon::parse($date)->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('doctor.payslips.index', compact('payslips', 'years', 'month', 'year'));
    }

    /**
     * Display the specified payslip.
     */
    public function show($id)
    {
        $payroll = Payroll::where('user_id', Auth::id())
            ->whereIn('status', ['approved', 'paid'])
            ->findOrFail($id);

        return view('doctor.payslips.show', compact('payroll'));
    }
}

