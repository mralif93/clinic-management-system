<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use App\Models\Attendance;
use App\Models\Appointment;
use Carbon\Carbon;

class PayrollCalculationService
{
    /**
     * Calculate comprehensive payroll details for an employee
     */
    public function calculate(
        User $user,
        string $startDate,
        string $endDate,
        float $manualBasicSalary = null,
        array $allowanceAmounts = [],
        float $overtimePay = 0
    ) {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $baseDetails = $this->calculateBaseSalary($user, $start, $end, $manualBasicSalary);
        $basicSalary = $baseDetails['amount'];

        // Sum allowances
        $totalAllowances = array_sum($allowanceAmounts);

        // As per simplified Option 2, we assume overtime and allowances are subject to EPF/SOCSO/EIS
        // Subjective earnings = Basic + Allowances + Overtime
        $subjectiveEarnings = $basicSalary + $totalAllowances + $overtimePay;

        // Calculate Statutory Deductions
        $statutory = $this->calculateStatutoryDeductions($subjectiveEarnings, $user);

        return [
            'success' => true,
            'employment_type' => $user->employment_type,
            'base_details' => $baseDetails,
            'basic_salary' => $basicSalary,
            'total_allowances' => $totalAllowances,
            'overtime_pay' => $overtimePay,
            'subjective_earnings' => $subjectiveEarnings,
            'statutory_deductions' => $statutory,
        ];
    }

    /**
     * Calculate base salary depending on employment type and prorating
     */
    private function calculateBaseSalary(User $user, Carbon $start, Carbon $end, ?float $manualBasicSalary)
    {
        switch ($user->employment_type) {
            case 'full_time':
                $fullSalary = $manualBasicSalary ?? ($user->basic_salary ?? 0);

                $isFullMonth = $start->copy()->startOfMonth()->isSameDay($start) && $end->copy()->endOfMonth()->isSameDay($end);

                if ($isFullMonth) {
                    $months = $start->copy()->startOfMonth()->diffInMonths($end->copy()->startOfMonth()) + 1;
                    $proratedSalary = $fullSalary * $months;

                    $desc = $months > 1 ? "Full salary for {$months} months" : "Full month salary";
                    return [
                        'type' => 'Full Time',
                        'description' => $desc,
                        'full_salary' => $fullSalary,
                        'amount' => round($proratedSalary, 2),
                    ];
                }

                // Calculate actual working days in the period
                $actualWorkingDays = $this->getWorkingDays($start, $end);

                // If within the same month, use exact month working days to prorate
                if ($start->format('Y-m') === $end->format('Y-m')) {
                    $monthStart = $start->copy()->startOfMonth();
                    $monthEnd = $start->copy()->endOfMonth();
                    $totalWorkingDaysInMonth = $this->getWorkingDays($monthStart, $monthEnd);

                    $proratedSalary = ($fullSalary / $totalWorkingDaysInMonth) * $actualWorkingDays;

                    return [
                        'type' => 'Full Time',
                        'description' => "Prorated: {$actualWorkingDays} of {$totalWorkingDaysInMonth} working days",
                        'full_salary' => $fullSalary,
                        'amount' => round($proratedSalary, 2),
                    ];
                }

                // Fallback for weird partial multi-month ranges using EA 1955 standard 26 days
                $dailyRate = $fullSalary / 26;
                $proratedSalary = $dailyRate * $actualWorkingDays;

                return [
                    'type' => 'Full Time',
                    'description' => "Prorated: {$actualWorkingDays} working days (based on 26 days/month)",
                    'full_salary' => $fullSalary,
                    'amount' => round($proratedSalary, 2),
                ];

            case 'part_time':
                $hourlyRate = $user->hourly_rate ?? Setting::get('payroll_part_time_hourly_rate', 8);
                $totalHours = Attendance::where('user_id', $user->id)
                    ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->where('is_approved', true)
                    ->sum('total_hours');

                return [
                    'type' => 'Part Time',
                    'description' => "Total hours: {$totalHours}h × RM{$hourlyRate}/hour",
                    'hours' => $totalHours,
                    'rate' => $hourlyRate,
                    'amount' => round($totalHours * $hourlyRate, 2),
                ];

            case 'locum':
                if (!$user->doctor) {
                    return [
                        'type' => 'Locum',
                        'description' => 'No doctor profile found',
                        'amount' => 0,
                    ];
                }

                $commissionRate = $user->doctor->commission_rate ?? Setting::get('payroll_locum_commission_rate', 60);

                $appointments = Appointment::where('doctor_id', $user->doctor->id)
                    ->whereBetween('appointment_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->whereIn('status', ['completed', 'confirmed'])
                    ->get();

                $totalFee = $appointments->sum('fee');
                $appointmentCount = $appointments->count();

                return [
                    'type' => 'Locum',
                    'description' => "{$appointmentCount} appointments × {$commissionRate}% commission",
                    'appointments' => $appointmentCount,
                    'total_fee' => $totalFee,
                    'commission_rate' => $commissionRate,
                    'amount' => round(($totalFee * $commissionRate) / 100, 2),
                ];

            default:
                return [
                    'type' => 'Unknown',
                    'description' => 'Employment type not set',
                    'amount' => 0,
                ];
        }
    }

    /**
     * Calculate working days between two dates (excluding weekends)
     */
    private function getWorkingDays(Carbon $startDate, Carbon $endDate)
    {
        $days = 0;
        $date = $startDate->copy();

        while ($date->lte($endDate)) {
            if (!$date->isWeekend()) {
                $days++;
            }
            $date->addDay();
        }

        return $days;
    }

    /**
     * Calculate EPF, SOCSO, EIS based on subjective earnings
     */
    private function calculateStatutoryDeductions(float $subjectiveEarnings, ?User $user = null)
    {
        // Settings for base rates
        $epfEmployeeRate = Setting::get('payroll_epf_employee_rate', 11) / 100;

        // KWSP Employer Contribution: 13% for salary <= RM 5,000, 12% for salary > RM 5,000 (if not hardcoded)
        $settingEpfEmployer = Setting::get('payroll_epf_employer_rate', null);
        $epfEmployerRate = $settingEpfEmployer !== null
            ? ((float) $settingEpfEmployer / 100)
            : ($subjectiveEarnings <= 5000 ? 0.13 : 0.12);

        $socsoEmployeeRate = Setting::get('payroll_socso_employee_rate', 0.5) / 100;
        $socsoEmployerRate = Setting::get('payroll_socso_employer_rate', 1.75) / 100;

        $eisEmployeeRate = Setting::get('payroll_eis_employee_rate', 0.2) / 100;
        $eisEmployerRate = Setting::get('payroll_eis_employer_rate', 0.2) / 100;

        $pcbRate = Setting::get('payroll_tax_rate', 0) / 100;

        // --- EPF (KWSP) ---
        // Third Schedule of KWSP Act 1991: Amount is generally rounded UP to the next ringgit.
        // e.g., RM 50.01 -> RM 51.00
        $epfEmployee = ceil($subjectiveEarnings * $epfEmployeeRate);
        $epfEmployer = ceil($subjectiveEarnings * $epfEmployerRate);

        // --- SOCSO (PERKESO) ---
        // Capped at RM6,000 monthly wage ceiling (latest PERKESO amendment)
        $socsoWage = min($subjectiveEarnings, 6000);
        $socsoEmployee = round($socsoWage * $socsoEmployeeRate, 2);
        $socsoEmployer = round($socsoWage * $socsoEmployerRate, 2);

        // --- EIS (SIP) ---
        // Capped at RM6,000 monthly wage ceiling
        $eisWage = min($subjectiveEarnings, 6000);
        $eisEmployee = round($eisWage * $eisEmployeeRate, 2);
        $eisEmployer = round($eisWage * $eisEmployerRate, 2);

        // --- PCB (LHDN TAX) ---
        // Implementation of standard LHDN PCB calculation
        if ($user) {
            $pcb = $this->calculateLHDNTax($subjectiveEarnings, $epfEmployee, $user);
        } else {
            // Fallback to simplified percentage if no user context is provided
            $pcbRate = Setting::get('payroll_tax_rate', 0) / 100;
            $epfRelief = min($epfEmployee, 4000 / 12);
            $taxableIncome = max(0, $subjectiveEarnings - $epfRelief);
            $pcb = round($taxableIncome * $pcbRate, 2);
        }

        return [
            'epf_employee' => $epfEmployee,
            'epf_employer' => $epfEmployer,
            'socso_employee' => $socsoEmployee,
            'socso_employer' => $socsoEmployer,
            'eis_employee' => $eisEmployee,
            'eis_employer' => $eisEmployer,
            'pcb' => $pcb,
            'total_employee_deductions' => $epfEmployee + $socsoEmployee + $eisEmployee + $pcb,
        ];
    }

    /**
     * Calculate statutory deductions only, based on subjective earnings
     */
    public function calculateStatutoryOnly(float $basicSalary, array $allowanceAmounts = [], float $overtimePay = 0, ?User $user = null)
    {
        $totalAllowances = array_sum(array_map('floatval', $allowanceAmounts));
        $subjectiveEarnings = $basicSalary + $totalAllowances + $overtimePay;
        return $this->calculateStatutoryDeductions($subjectiveEarnings, $user);
    }

    /**
     * Calculate LHDN Tax (PCB) based on annualize income and reliefs
     */
    private function calculateLHDNTax(float $monthlyEarnings, float $monthlyEpf, User $user)
    {
        // 1. Calculate Monthly EPF relief (capped at RM 4,000 annually or RM 333.33 monthly)
        $monthlyEpfRelief = min($monthlyEpf, 4000 / 12);

        // 2. Annualize income
        $annualIncome = $monthlyEarnings * 12;
        $annualEpfRelief = $monthlyEpfRelief * 12;

        // 3. Apply Reliefs
        $personalRelief = 9000;
        $spouseRelief = ($user->marital_status === 'married_spouse_not_working') ? 4000 : 0;
        $childRelief = ($user->number_of_children ?? 0) * 2000;

        $chargeableIncome = $annualIncome - $annualEpfRelief - $personalRelief - $spouseRelief - $childRelief;

        if ($chargeableIncome <= 5000) {
            return 0;
        }

        // 4. Calculate Annual Tax based on 2024 Brackets
        $annualTax = 0;
        if ($chargeableIncome <= 20000) {
            $annualTax = ($chargeableIncome - 5000) * 0.01;
        } elseif ($chargeableIncome <= 35000) {
            $annualTax = 150 + (($chargeableIncome - 20000) * 0.03);
        } elseif ($chargeableIncome <= 50000) {
            $annualTax = 600 + (($chargeableIncome - 35000) * 0.06);
        } elseif ($chargeableIncome <= 70000) {
            $annualTax = 1500 + (($chargeableIncome - 50000) * 0.11);
        } elseif ($chargeableIncome <= 100000) {
            $annualTax = 3700 + (($chargeableIncome - 70000) * 0.19);
        } elseif ($chargeableIncome <= 400000) {
            $annualTax = 9400 + (($chargeableIncome - 100000) * 0.25);
        } elseif ($chargeableIncome <= 600000) {
            $annualTax = 84400 + (($chargeableIncome - 400000) * 0.26);
        } elseif ($chargeableIncome <= 2000000) {
            $annualTax = 136400 + (($chargeableIncome - 600000) * 0.28);
        } else {
            $annualTax = 528400 + (($chargeableIncome - 2000000) * 0.30);
        }

        // 5. Monthly PCB
        return round($annualTax / 12, 2);
    }
}
