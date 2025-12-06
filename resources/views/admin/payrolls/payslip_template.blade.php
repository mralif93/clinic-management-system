@php
    $clinicLogo = get_setting('clinic_logo');
    $clinicName = get_setting('clinic_name', 'Clinic Management System');
    $clinicAddress = get_setting('clinic_address', '123 Medical Street, Health City');
    $clinicPhone = get_setting('clinic_phone', '+1 (555) 123-4567');
    $clinicEmail = get_setting('clinic_email', 'info@clinic.com');
    $currencySymbol = get_currency_symbol();
@endphp

<style>
    #payslip-content {
        width: 100%;
        max-width: 100%;
    }
    @media print {
        #payslip-content {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            font-size: 11px !important;
        }
        #payslip-content * {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }
        .no-print {
            display: none !important;
        }
    }
    @page {
        size: A4 portrait;
        margin: 8mm;
    }
</style>

<div id="payslip-content" class="bg-white overflow-hidden shadow-sm border border-gray-100 rounded-2xl print:rounded-none print:border-none print:shadow-none">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 px-5 py-4 text-white">
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-3">
                <!-- Logo -->
                @if($clinicLogo)
                    @if(str_starts_with($clinicLogo, 'data:'))
                        <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}" class="w-11 h-11 object-contain rounded-lg bg-white/20 p-1">
                    @else
                        <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}" class="w-11 h-11 object-contain rounded-lg bg-white/20 p-1">
                    @endif
                @else
                    <div class="w-11 h-11 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($clinicName, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-base font-bold">{{ strtoupper($clinicName) }}</h1>
                    <p class="text-xs text-emerald-100">{{ $clinicAddress }}</p>
                    <p class="text-xs text-emerald-100">Tel: {{ $clinicPhone }} | Email: {{ $clinicEmail }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur px-3 py-1.5 rounded-lg">
                    <i class='bx bx-receipt text-lg'></i>
                    <span class="text-base font-bold tracking-wide">PAYSLIP</span>
                </div>
                <p class="text-emerald-100 mt-1 text-xs" id="preview-period">Period: {{ $payroll->pay_period ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="p-4">

    <!-- Employee Info -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                <i class='bx bx-user text-xs'></i> Employee Details
            </h3>
            <table class="w-full text-xs">
                <tr>
                    <td class="py-1 text-gray-500 w-24">Name:</td>
                    <td class="py-1 font-semibold text-gray-900" id="preview-name">{{ $payroll->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Employee ID:</td>
                    <td class="py-1 font-medium text-gray-900">EMP-{{ str_pad($payroll->user->id ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Position:</td>
                    <td class="py-1 text-gray-900">{{ ucfirst($payroll->user->role ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Employment:</td>
                    <td class="py-1 text-gray-900">
                        @if($payroll->user->employment_type)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold
                                {{ $payroll->user->employment_type === 'locum' ? 'bg-purple-100 text-purple-700' :
                                   ($payroll->user->employment_type === 'part_time' ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ ucfirst(str_replace('_', ' ', $payroll->user->employment_type)) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700">Full Time</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Department:</td>
                    <td class="py-1 text-gray-900">Medical</td>
                </tr>
            </table>
        </div>
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                <i class='bx bx-credit-card text-xs'></i> Payment Details
            </h3>
            <table class="w-full text-xs">
                <tr>
                    <td class="py-1 text-gray-500 w-24">Bank Name:</td>
                    <td class="py-1 text-gray-900">Maybank</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Account No:</td>
                    <td class="py-1 text-gray-900">123456789012</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Payment Date:</td>
                    <td class="py-1 text-gray-900" id="preview-payment-date">
                        {{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : now()->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Method:</td>
                    <td class="py-1 text-gray-900" id="preview-payment-method">
                        {{ \App\Models\Payroll::getPaymentMethods()[$payroll->payment_method ?? 'bank_transfer'] ?? 'Bank Transfer' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Hours Breakdown for Part-Time Staff -->
    @if($payroll->user->employment_type === 'part_time')
        @php
            $attendances = \App\Models\Attendance::where('user_id', $payroll->user_id)
                ->whereBetween('date', [$payroll->pay_period_start, $payroll->pay_period_end])
                ->where('is_approved', true)
                ->orderBy('date')
                ->get();
            $hourlyRate = $payroll->user->hourly_rate ?? 8;
        @endphp

        @if($attendances->count() > 0)
            <div class="mb-4 border border-amber-200 rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-3 py-2 flex items-center gap-1">
                    <i class='bx bx-time text-sm text-white'></i>
                    <h3 class="text-xs font-bold text-white">Hours Breakdown ({{ $currencySymbol }}{{ number_format($hourlyRate, 2) }}/hr)</h3>
                </div>
                <table class="w-full text-xs bg-amber-50">
                    <thead>
                        <tr class="border-b border-amber-200">
                            <th class="py-1.5 px-2 text-left text-[10px] font-bold text-amber-800 uppercase">Date</th>
                            <th class="py-1.5 px-2 text-center text-[10px] font-bold text-amber-800 uppercase">Hours</th>
                            <th class="py-1.5 px-2 text-right text-[10px] font-bold text-amber-800 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr class="border-b border-amber-100">
                                <td class="py-1 px-2 text-gray-700">{{ $attendance->date->format('d M Y') }}</td>
                                <td class="py-1 px-2 text-center text-gray-900 font-semibold">{{ number_format($attendance->total_hours, 2) }}h</td>
                                <td class="py-1 px-2 text-right text-amber-700 font-semibold">{{ $currencySymbol }}{{ number_format($attendance->total_hours * $hourlyRate, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-amber-100 font-bold">
                            <td class="py-1.5 px-2 text-amber-900">TOTAL ({{ $attendances->count() }} days)</td>
                            <td class="py-1.5 px-2 text-center text-amber-900">{{ number_format($attendances->sum('total_hours'), 2) }}h</td>
                            <td class="py-1.5 px-2 text-right text-amber-900">{{ $currencySymbol }}{{ number_format($attendances->sum('total_hours') * $hourlyRate, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    @endif

    <!-- Commission Breakdown for Locum Doctors -->
    @if($payroll->user->employment_type === 'locum' && $payroll->user->doctor)
        @php
            $appointments = \App\Models\Appointment::where('doctor_id', $payroll->user->doctor->id)
                ->whereBetween('appointment_date', [$payroll->pay_period_start, $payroll->pay_period_end])
                ->whereIn('status', ['completed', 'confirmed'])
                ->with('patient')
                ->get();
            $commissionRate = $payroll->user->doctor->commission_rate ?? 60;
        @endphp

        @if($appointments->count() > 0)
            <div class="mb-4 border border-purple-200 rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-violet-500 px-3 py-2 flex items-center gap-1">
                    <i class='bx bx-wallet text-sm text-white'></i>
                    <h3 class="text-xs font-bold text-white">Commission Breakdown ({{ number_format($commissionRate, 0) }}%)</h3>
                </div>
                <table class="w-full text-xs bg-purple-50">
                    <thead>
                        <tr class="border-b border-purple-200">
                            <th class="py-1.5 px-2 text-left text-[10px] font-bold text-purple-800 uppercase">Date</th>
                            <th class="py-1.5 px-2 text-left text-[10px] font-bold text-purple-800 uppercase">Patient</th>
                            <th class="py-1.5 px-2 text-right text-[10px] font-bold text-purple-800 uppercase">Fee</th>
                            <th class="py-1.5 px-2 text-right text-[10px] font-bold text-purple-800 uppercase">Commission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr class="border-b border-purple-100">
                                <td class="py-1 px-2 text-gray-700">{{ $appointment->appointment_date->format('d M') }}</td>
                                <td class="py-1 px-2 text-gray-700">{{ $appointment->patient->full_name }}</td>
                                <td class="py-1 px-2 text-right text-gray-900">{{ $currencySymbol }}{{ number_format($appointment->fee, 2) }}</td>
                                <td class="py-1 px-2 text-right text-purple-700 font-semibold">{{ $currencySymbol }}{{ number_format(($appointment->fee * $commissionRate) / 100, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-purple-100 font-bold">
                            <td colspan="2" class="py-1.5 px-2 text-purple-900">TOTAL ({{ $appointments->count() }} appointments)</td>
                            <td class="py-1.5 px-2 text-right text-purple-900">{{ $currencySymbol }}{{ number_format($appointments->sum('fee'), 2) }}</td>
                            <td class="py-1.5 px-2 text-right text-purple-900">{{ $currencySymbol }}{{ number_format(($appointments->sum('fee') * $commissionRate) / 100, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    @endif

    <!-- Salary Details -->
    <div class="mb-4 border border-emerald-200 rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-3 py-2 flex items-center gap-1">
            <i class='bx bx-money text-sm text-white'></i>
            <h3 class="text-xs font-bold text-white">Earnings</h3>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-emerald-50 border-b border-emerald-100">
                    <th class="py-2 px-3 text-left text-[10px] font-bold text-emerald-700 uppercase tracking-wider w-1/2">Description</th>
                    <th class="py-2 px-3 text-right text-[10px] font-bold text-emerald-700 uppercase tracking-wider w-1/2">Amount ({{ $currencySymbol }})</th>
                </tr>
            </thead>
            <tbody>
                <!-- Basic Salary -->
                <tr class="border-b border-gray-100">
                    <td class="py-1.5 px-3 text-gray-800 font-medium">Basic Salary</td>
                    <td class="py-1.5 px-3 text-right font-semibold text-gray-900" id="preview-basic-salary">
                        {{ number_format($payroll->basic_salary ?? 0, 2) }}
                    </td>
                </tr>

                <!-- Allowances -->
                @if(isset($payroll->allowances) && count($payroll->allowances) > 0)
                    @foreach($payroll->allowances as $name => $amount)
                        <tr class="border-b border-gray-100">
                            <td class="py-1.5 px-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $name)) }}</td>
                            <td class="py-1.5 px-3 text-right text-gray-900">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endif

                <!-- Overtime -->
                @if(($payroll->overtime_pay ?? 0) > 0)
                <tr class="border-b border-gray-100" id="preview-overtime-row">
                    <td class="py-1.5 px-3 text-gray-600">Overtime <span id="preview-overtime-hours" class="text-[10px] text-gray-500">({{ $payroll->overtime_hours ?? 0 }} hrs)</span></td>
                    <td class="py-1.5 px-3 text-right text-gray-900" id="preview-overtime-pay">
                        {{ number_format($payroll->overtime_pay ?? 0, 2) }}
                    </td>
                </tr>
                @endif

                <!-- Gross Salary -->
                <tr class="bg-emerald-50 font-bold">
                    <td class="py-2 px-3 text-emerald-800">GROSS SALARY</td>
                    <td class="py-2 px-3 text-right text-emerald-700" id="preview-gross-salary">
                        {{ number_format($payroll->gross_salary ?? 0, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Deductions -->
    <div class="mb-4 border border-red-200 rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-rose-500 px-3 py-2 flex items-center gap-1">
            <i class='bx bx-minus-circle text-sm text-white'></i>
            <h3 class="text-xs font-bold text-white">Deductions</h3>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-red-50 border-b border-red-100">
                    <th class="py-2 px-3 text-left text-[10px] font-bold text-red-700 uppercase tracking-wider w-1/2">Description</th>
                    <th class="py-2 px-3 text-right text-[10px] font-bold text-red-700 uppercase tracking-wider w-1/2">Amount ({{ $currencySymbol }})</th>
                </tr>
            </thead>
            <tbody id="preview-deductions-list">
                @if(isset($payroll->deductions) && count($payroll->deductions) > 0)
                    @foreach($payroll->deductions as $name => $amount)
                        <tr class="border-b border-gray-100">
                            <td class="py-1.5 px-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $name)) }}</td>
                            <td class="py-1.5 px-3 text-right text-red-600 font-medium">- {{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-b border-gray-100">
                        <td class="py-1.5 px-3 text-gray-500 italic">No deductions</td>
                        <td class="py-1.5 px-3 text-right text-gray-500">- 0.00</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="bg-red-50 font-bold">
                    <td class="py-2 px-3 text-red-800">TOTAL DEDUCTIONS</td>
                    <td class="py-2 px-3 text-right text-red-600" id="preview-total-deductions">
                        - {{ number_format($payroll->total_deductions ?? 0, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Net Salary -->
    <div class="flex justify-end mb-3">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-4 py-2 rounded-lg min-w-[180px]">
            <p class="text-[9px] text-emerald-100 uppercase tracking-wider">Net Salary</p>
            <div class="text-xl font-bold flex items-start gap-0.5">
                <span class="text-xs mt-0.5">{{ $currencySymbol }}</span>
                <span id="preview-net-salary">{{ number_format($payroll->net_salary ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="grid grid-cols-2 gap-4 text-gray-500 border-t border-gray-200 pt-3">
        <div>
            <p class="mb-2 text-[9px]">This is a computer-generated document. No signature is required.</p>
            <div class="border-t border-gray-300 pt-1 inline-block">
                <p class="font-bold text-gray-700 text-[10px]">{{ $clinicName }}</p>
                <p class="text-[9px]">Authorized Signatory</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-[9px]">Date Generated: <span id="preview-generated-date" class="font-medium text-gray-700">{{ now()->format('d M Y') }}</span></p>
            <p class="text-[9px]">Generated by: <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span></p>
        </div>
    </div>
    </div>
</div>