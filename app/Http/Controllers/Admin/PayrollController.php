<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /**
     * Display a listing of payroll months
     */
    public function index()
    {
        // Group payrolls by year and month
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $yearExpr = "strftime('%Y', pay_period_end)";
            $monthExpr = "strftime('%m', pay_period_end)";
        } else {
            $yearExpr = "YEAR(pay_period_end)";
            $monthExpr = "MONTH(pay_period_end)";
        }

        $months = Payroll::select(
            DB::raw("$yearExpr as year"),
            DB::raw("$monthExpr as month"),
            DB::raw('COUNT(*) as total_payrolls'),
            DB::raw('SUM(net_salary) as total_amount'),
            DB::raw("SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as pending_count"),
            DB::raw("SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_count")
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.payrolls.months', compact('months'));
    }

    /**
     * Display a listing of payrolls for a specific month
     */
    public function byMonth(Request $request, $year, $month)
    {
        $query = Payroll::with(['user', 'generatedBy', 'approvedBy'])
            ->whereYear('pay_period_end', $year)
            ->whereMonth('pay_period_end', $month);

        // Filter by employee
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->latest()->paginate(15);

        // Get statistics for this month
        $stats = [
            'total' => $query->count(), // Use filtered query count or total for month? Usually contextual stats are better.
            // Let's stick to month stats regardless of filter for top cards usually, but if filtered, maybe filtered stats.
            // The previous implementation had global stats. Let's make them specific to this month.
            'total_payrolls' => Payroll::whereYear('pay_period_end', $year)->whereMonth('pay_period_end', $month)->count(),
            'pending_approval' => Payroll::whereYear('pay_period_end', $year)->whereMonth('pay_period_end', $month)->where('status', 'draft')->count(),
            'total_paid' => Payroll::whereYear('pay_period_end', $year)
                ->whereMonth('pay_period_end', $month)
                ->where('status', 'paid')
                ->sum('net_salary'),
        ];

        // Get employees (doctors and staff)
        $employees = User::whereIn('role', ['doctor', 'staff'])
            ->orderBy('name')
            ->get();

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

        return view('admin.payrolls.list', compact('payrolls', 'stats', 'employees', 'year', 'month', 'monthName'));
    }

    /**
     * Show the form for creating a new payroll
     */
    public function create()
    {
        // Get employees (doctors and staff)
        $employees = User::whereIn('role', ['doctor', 'staff'])
            ->orderBy('name')
            ->get();

        // Get payroll settings
        $payrollSettings = [
            'epf_employee' => \App\Models\Setting::get('payroll_epf_employee_rate', 11),
            'epf_employer' => \App\Models\Setting::get('payroll_epf_employer_rate', 13),
            'socso_employee' => \App\Models\Setting::get('payroll_socso_employee_rate', 0.5),
            'socso_employer' => \App\Models\Setting::get('payroll_socso_employer_rate', 1.75),
            'eis_employee' => \App\Models\Setting::get('payroll_eis_employee_rate', 0.2),
            'eis_employer' => \App\Models\Setting::get('payroll_eis_employer_rate', 0.2),
            'tax' => \App\Models\Setting::get('payroll_tax_rate', 0),
        ];

        return view('admin.payrolls.create', compact('employees', 'payrollSettings'));
    }

    /**
     * Store a newly created payroll
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'basic_salary' => 'required|numeric|min:0',
            'allowance_names' => 'nullable|array',
            'allowance_names.*' => 'nullable|string',
            'allowance_amounts' => 'nullable|array',
            'allowance_amounts.*' => 'nullable|numeric|min:0',
            'deduction_names' => 'nullable|array',
            'deduction_names.*' => 'nullable|string',
            'deduction_amounts' => 'nullable|array',
            'deduction_amounts.*' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:bank_transfer,cash,cheque',
            'notes' => 'nullable|string',
        ]);

        // Process allowances
        $allowances = [];
        $totalAllowances = 0;
        if ($request->has('allowance_names') && $request->has('allowance_amounts')) {
            foreach ($request->allowance_names as $index => $name) {
                if (!empty($name) && isset($request->allowance_amounts[$index])) {
                    $amount = (float) $request->allowance_amounts[$index];
                    if ($amount > 0) {
                        $allowances[$name] = $amount;
                        $totalAllowances += $amount;
                    }
                }
            }
        }

        // Process deductions
        $deductions = [];
        $totalDeductions = 0;
        if ($request->has('deduction_names') && $request->has('deduction_amounts')) {
            foreach ($request->deduction_names as $index => $name) {
                if (!empty($name) && isset($request->deduction_amounts[$index])) {
                    $amount = (float) $request->deduction_amounts[$index];
                    if ($amount > 0) {
                        $deductions[$name] = $amount;
                        $totalDeductions += $amount;
                    }
                }
            }
        }

        // Calculate gross and net salary
        $basicSalary = $validated['basic_salary'];
        $overtimePay = $validated['overtime_pay'] ?? 0;

        $grossSalary = $basicSalary + $totalAllowances + $overtimePay;
        $netSalary = $grossSalary - $totalDeductions;

        $data = [
            'user_id' => $validated['user_id'],
            'pay_period_start' => $validated['pay_period_start'],
            'pay_period_end' => $validated['pay_period_end'],
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'overtime_hours' => $validated['overtime_hours'] ?? 0,
            'overtime_pay' => $overtimePay,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'payment_method' => $validated['payment_method'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'generated_by' => Auth::id(),
            'status' => 'draft',
        ];

        Payroll::create($data);

        return redirect()->route('admin.payrolls.index')
            ->with('success', 'Payroll created successfully!');
    }

    /**
     * Display the specified payroll
     */
    public function show(Payroll $payroll)
    {
        $payroll->load(['user', 'generatedBy', 'approvedBy']);
        return view('admin.payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified payroll
     */
    public function edit(Payroll $payroll)
    {
        // Only allow editing draft payrolls
        if ($payroll->status !== 'draft') {
            return redirect()->route('admin.payrolls.index')
                ->with('error', 'Only draft payrolls can be edited!');
        }

        // Get employees (doctors and staff)
        $employees = User::whereIn('role', ['doctor', 'staff'])
            ->orderBy('name')
            ->get();

        // Get payroll settings
        $payrollSettings = [
            'epf_employee' => \App\Models\Setting::get('payroll_epf_employee_rate', 11),
            'epf_employer' => \App\Models\Setting::get('payroll_epf_employer_rate', 13),
            'socso_employee' => \App\Models\Setting::get('payroll_socso_employee_rate', 0.5),
            'socso_employer' => \App\Models\Setting::get('payroll_socso_employer_rate', 1.75),
            'eis_employee' => \App\Models\Setting::get('payroll_eis_employee_rate', 0.2),
            'eis_employer' => \App\Models\Setting::get('payroll_eis_employer_rate', 0.2),
            'tax' => \App\Models\Setting::get('payroll_tax_rate', 0),
        ];

        return view('admin.payrolls.edit', compact('payroll', 'employees', 'payrollSettings'));
    }

    /**
     * Update the specified payroll
     */
    public function update(Request $request, Payroll $payroll)
    {
        // Only allow editing draft payrolls
        if ($payroll->status !== 'draft') {
            return redirect()->route('admin.payrolls.index')
                ->with('error', 'Only draft payrolls can be edited!');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'basic_salary' => 'required|numeric|min:0',
            'allowance_names' => 'nullable|array',
            'allowance_names.*' => 'nullable|string',
            'allowance_amounts' => 'nullable|array',
            'allowance_amounts.*' => 'nullable|numeric|min:0',
            'deduction_names' => 'nullable|array',
            'deduction_names.*' => 'nullable|string',
            'deduction_amounts' => 'nullable|array',
            'deduction_amounts.*' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:bank_transfer,cash,cheque',
            'notes' => 'nullable|string',
        ]);

        // Process allowances
        $allowances = [];
        $totalAllowances = 0;
        if ($request->has('allowance_names') && $request->has('allowance_amounts')) {
            foreach ($request->allowance_names as $index => $name) {
                if (!empty($name) && isset($request->allowance_amounts[$index])) {
                    $amount = (float) $request->allowance_amounts[$index];
                    if ($amount > 0) {
                        $allowances[$name] = $amount;
                        $totalAllowances += $amount;
                    }
                }
            }
        }

        // Process deductions
        $deductions = [];
        $totalDeductions = 0;
        if ($request->has('deduction_names') && $request->has('deduction_amounts')) {
            foreach ($request->deduction_names as $index => $name) {
                if (!empty($name) && isset($request->deduction_amounts[$index])) {
                    $amount = (float) $request->deduction_amounts[$index];
                    if ($amount > 0) {
                        $deductions[$name] = $amount;
                        $totalDeductions += $amount;
                    }
                }
            }
        }

        // Calculate gross and net salary
        $basicSalary = $validated['basic_salary'];
        $overtimePay = $validated['overtime_pay'] ?? 0;

        $grossSalary = $basicSalary + $totalAllowances + $overtimePay;
        $netSalary = $grossSalary - $totalDeductions;

        $data = [
            'user_id' => $validated['user_id'],
            'pay_period_start' => $validated['pay_period_start'],
            'pay_period_end' => $validated['pay_period_end'],
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'overtime_hours' => $validated['overtime_hours'] ?? 0,
            'overtime_pay' => $overtimePay,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'payment_method' => $validated['payment_method'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        $payroll->update($data);

        return redirect()->route('admin.payrolls.by-month', [
            'year' => \Carbon\Carbon::parse($validated['pay_period_end'])->year,
            'month' => \Carbon\Carbon::parse($validated['pay_period_end'])->month
        ])->with('success', 'Payroll updated successfully!');
    }

    /**
     * Remove the specified payroll
     */
    public function destroy(Payroll $payroll)
    {
        // Only allow deleting draft payrolls
        if ($payroll->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft payrolls can be deleted!');
        }

        $payroll->delete();

        return redirect()->back()
            ->with('success', 'Payroll deleted successfully!');
    }

    /**
     * Approve the specified payroll
     */
    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft payrolls can be approved!');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Payroll approved successfully!');
    }

    /**
     * Mark the specified payroll as paid
     */
    public function markAsPaid(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only approved payrolls can be marked as paid!');
        }

        $validated = $request->validate([
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_reference' => $validated['payment_reference'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Payroll marked as paid successfully!');
    }

    /**
     * Display trashed payrolls
     */
    public function trash()
    {
        $payrolls = Payroll::onlyTrashed()
            ->with(['user', 'generatedBy'])
            ->latest()
            ->paginate(15);

        return view('admin.payrolls.trash', compact('payrolls'));
    }

    /**
     * Restore a trashed payroll
     */
    public function restore($id)
    {
        $payroll = Payroll::onlyTrashed()->findOrFail($id);
        $payroll->restore();

        return redirect()->route('admin.payrolls.trash')
            ->with('success', 'Payroll restored successfully!');
    }

    /**
     * Force delete a payroll
     */
    public function forceDelete($id)
    {
        $payroll = Payroll::onlyTrashed()->findOrFail($id);
        $payroll->forceDelete();

        return redirect()->route('admin.payrolls.trash')
            ->with('success', 'Payroll permanently deleted!');
    }
}