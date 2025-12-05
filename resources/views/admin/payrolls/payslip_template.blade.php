@php
    $clinicLogo = get_setting('clinic_logo');
    $clinicName = get_setting('clinic_name', 'Clinic Management System');
    $clinicAddress = get_setting('clinic_address', '123 Medical Street, Health City');
    $clinicPhone = get_setting('clinic_phone', '+1 (555) 123-4567');
    $clinicEmail = get_setting('clinic_email', 'info@clinic.com');
    $currencySymbol = get_currency_symbol();
@endphp

<div id="payslip-content" class="bg-white p-8 max-w-4xl mx-auto border border-gray-200">
    <!-- Header -->
    <div class="border-b-2 border-gray-800 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-4">
                <!-- Logo -->
                @if($clinicLogo)
                    @if(str_starts_with($clinicLogo, 'data:'))
                        <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}" class="w-16 h-16 object-contain rounded-lg">
                    @else
                        <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}" class="w-16 h-16 object-contain rounded-lg">
                    @endif
                @else
                    <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($clinicName, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ strtoupper($clinicName) }}</h1>
                    <p class="text-sm text-gray-600">{{ $clinicAddress }}</p>
                    <p class="text-sm text-gray-600">Tel: {{ $clinicPhone }} | Email: {{ $clinicEmail }}</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-bold text-gray-800 tracking-wide">PAYSLIP</h2>
                <p class="text-gray-600 mt-1 font-medium" id="preview-period">Period:
                    {{ $payroll->pay_period ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Employee Details</h3>
            <table class="w-full text-sm">
                <tr>
                    <td class="py-1 text-gray-600 w-32">Name:</td>
                    <td class="py-1 font-semibold text-gray-900" id="preview-name">{{ $payroll->user->name ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Employee ID:</td>
                    <td class="py-1 font-medium text-gray-900">
                        EMP-{{ str_pad($payroll->user->id ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Position:</td>
                    <td class="py-1 text-gray-900">{{ ucfirst($payroll->user->role ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Employment Type:</td>
                    <td class="py-1 text-gray-900">
                        @if($payroll->user->employment_type)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                {{ $payroll->user->employment_type === 'locum' ? 'bg-purple-100 text-purple-800' :
                                   ($payroll->user->employment_type === 'part_time' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $payroll->user->employment_type)) }}
                            </span>
                        @else
                            Full Time
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Department:</td>
                    <td class="py-1 text-gray-900">Medical</td>
                </tr>
            </table>
        </div>
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Bank Details</h3>
            <table class="w-full text-sm">
                <tr>
                    <td class="py-1 text-gray-600 w-32">Bank Name:</td>
                    <td class="py-1 text-gray-900">Maybank</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Account No:</td>
                    <td class="py-1 text-gray-900">123456789012</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Payment Date:</td>
                    <td class="py-1 text-gray-900" id="preview-payment-date">
                        {{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : now()->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Payment Method:</td>
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
            <div class="mb-8 bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-orange-200 rounded-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class='bx bx-time text-2xl text-orange-600'></i>
                    <h3 class="text-lg font-bold text-orange-900">Hours Breakdown</h3>
                </div>

                <div class="bg-white rounded-lg p-4 mb-4">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-200">
                                <th class="py-2 px-3 text-left text-xs font-bold text-orange-900 uppercase">Date</th>
                                <th class="py-2 px-3 text-center text-xs font-bold text-orange-900 uppercase">Hours Worked</th>
                                <th class="py-2 px-3 text-right text-xs font-bold text-orange-900 uppercase">Rate ({{ $currencySymbol }}/hr)</th>
                                <th class="py-2 px-3 text-right text-xs font-bold text-orange-900 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr class="border-b border-orange-100">
                                    <td class="py-2 px-3 text-gray-700">{{ $attendance->date->format('d M Y') }}</td>
                                    <td class="py-2 px-3 text-center text-gray-900 font-semibold">{{ number_format($attendance->total_hours, 2) }}h</td>
                                    <td class="py-2 px-3 text-right text-gray-700">{{ number_format($hourlyRate, 2) }}</td>
                                    <td class="py-2 px-3 text-right text-orange-900 font-semibold">{{ $currencySymbol }}{{ number_format($attendance->total_hours * $hourlyRate, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-orange-100 font-bold">
                                <td class="py-3 px-3 text-orange-900">TOTAL ({{ $attendances->count() }} days)</td>
                                <td class="py-3 px-3 text-center text-orange-900">{{ number_format($attendances->sum('total_hours'), 2) }}h</td>
                                <td class="py-3 px-3 text-right text-orange-900">{{ number_format($hourlyRate, 2) }}</td>
                                <td class="py-3 px-3 text-right text-orange-900">{{ $currencySymbol }}{{ number_format($attendances->sum('total_hours') * $hourlyRate, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-xs text-orange-700 bg-orange-200 rounded p-3 flex items-start gap-2">
                    <i class='bx bx-info-circle text-base mt-0.5'></i>
                    <p>Hourly rate: <strong>{{ $currencySymbol }}{{ number_format($hourlyRate, 2) }}/hour</strong>. Only approved attendance records are included in the calculation. Total hours are calculated from clock-in to clock-out time minus break duration.</p>
                </div>
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
            <div class="mb-8 bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class='bx bx-wallet text-2xl text-purple-600'></i>
                    <h3 class="text-lg font-bold text-purple-900">Commission Breakdown</h3>
                </div>

                <div class="bg-white rounded-lg p-4 mb-4">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-purple-200">
                                <th class="py-2 px-3 text-left text-xs font-bold text-purple-900 uppercase">Date</th>
                                <th class="py-2 px-3 text-left text-xs font-bold text-purple-900 uppercase">Patient</th>
                                <th class="py-2 px-3 text-right text-xs font-bold text-purple-900 uppercase">Fee</th>
                                <th class="py-2 px-3 text-right text-xs font-bold text-purple-900 uppercase">Commission ({{ number_format($commissionRate, 0) }}%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr class="border-b border-purple-100">
                                    <td class="py-2 px-3 text-gray-700">{{ $appointment->appointment_date->format('d M Y') }}</td>
                                    <td class="py-2 px-3 text-gray-700">{{ $appointment->patient->full_name }}</td>
                                    <td class="py-2 px-3 text-right text-gray-900">{{ $currencySymbol }}{{ number_format($appointment->fee, 2) }}</td>
                                    <td class="py-2 px-3 text-right text-purple-900 font-semibold">{{ $currencySymbol }}{{ number_format(($appointment->fee * $commissionRate) / 100, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-purple-100 font-bold">
                                <td colspan="2" class="py-3 px-3 text-purple-900">TOTAL ({{ $appointments->count() }} appointments)</td>
                                <td class="py-3 px-3 text-right text-purple-900">{{ $currencySymbol }}{{ number_format($appointments->sum('fee'), 2) }}</td>
                                <td class="py-3 px-3 text-right text-purple-900">{{ $currencySymbol }}{{ number_format(($appointments->sum('fee') * $commissionRate) / 100, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-xs text-purple-700 bg-purple-200 rounded p-3 flex items-start gap-2">
                    <i class='bx bx-info-circle text-base mt-0.5'></i>
                    <p>Commission rate: <strong>{{ number_format($commissionRate, 0) }}%</strong> of appointment fees. Only completed and confirmed appointments are included in the calculation.</p>
                </div>
            </div>
        @endif
    @endif

    <!-- Salary Details -->
    <div class="mb-8">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="py-3 px-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                        Earnings</th>
                    <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                        Amount ({{ $currencySymbol }})</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <!-- Basic Salary -->
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 text-gray-800">Basic Salary</td>
                    <td class="py-3 px-4 text-right font-medium text-gray-900" id="preview-basic-salary">
                        {{ number_format($payroll->basic_salary ?? 0, 2) }}
                    </td>
                </tr>

                <!-- Allowances -->
            <tbody id="preview-allowances-list">
                @if(isset($payroll->allowances) && count($payroll->allowances) > 0)
                    @foreach($payroll->allowances as $name => $amount)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-gray-600">{{ ucfirst(str_replace('_', ' ', $name)) }}</td>
                            <td class="py-3 px-4 text-right text-gray-900">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>

            <!-- Overtime -->
            <tr class="border-b border-gray-100" id="preview-overtime-row"
                style="{{ ($payroll->overtime_pay ?? 0) > 0 ? '' : 'display: none;' }}">
                <td class="py-3 px-4 text-gray-600">Overtime <span id="preview-overtime-hours"
                        class="text-xs text-gray-500">({{ $payroll->overtime_hours ?? 0 }} hrs)</span></td>
                <td class="py-3 px-4 text-right text-gray-900" id="preview-overtime-pay">
                    {{ number_format($payroll->overtime_pay ?? 0, 2) }}
                </td>
            </tr>

            <!-- Gross Salary -->
            <tr class="bg-gray-50 font-bold">
                <td class="py-3 px-4 text-gray-800">GROSS SALARY</td>
                <td class="py-3 px-4 text-right text-gray-900" id="preview-gross-salary">
                    {{ number_format($payroll->gross_salary ?? 0, 2) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Deductions -->
    <div class="mb-8">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="py-3 px-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                        Deductions</th>
                    <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                        Amount ({{ $currencySymbol }})</th>
                </tr>
            </thead>
            <tbody class="text-sm" id="preview-deductions-list">
                @if(isset($payroll->deductions) && count($payroll->deductions) > 0)
                    @foreach($payroll->deductions as $name => $amount)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-gray-600">{{ ucfirst(str_replace('_', ' ', $name)) }}</td>
                            <td class="py-3 px-4 text-right text-red-600">- {{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 text-gray-500 italic">No deductions</td>
                        <td class="py-3 px-4 text-right text-gray-500">- 0.00</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-bold">
                    <td class="py-3 px-4 text-gray-800">TOTAL DEDUCTIONS</td>
                    <td class="py-3 px-4 text-right text-red-600" id="preview-total-deductions">
                        - {{ number_format($payroll->total_deductions ?? 0, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Net Salary -->
    <div class="flex justify-end mb-12">
        <div class="bg-gray-900 text-white p-6 rounded-lg min-w-[300px]">
            <p class="text-sm text-gray-400 uppercase tracking-wider mb-1">Net Salary</p>
            <div class="text-4xl font-bold flex items-start gap-1">
                <span class="text-lg mt-1">{{ $currencySymbol }}</span>
                <span id="preview-net-salary">{{ number_format($payroll->net_salary ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="grid grid-cols-2 gap-12 text-sm text-gray-600 border-t border-gray-200 pt-8">
        <div>
            <p class="mb-12">This is a computer-generated document. No signature is required.</p>
            <p class="font-bold text-gray-900">{{ $clinicName }}</p>
            <p>Authorized Signatory</p>
        </div>
        <div class="text-right">
            <p class="mb-1">Date Generated: <span id="preview-generated-date">{{ now()->format('d M Y') }}</span></p>
            <p>Generated by: {{ Auth::user()->name }}</p>
        </div>
    </div>
</div>