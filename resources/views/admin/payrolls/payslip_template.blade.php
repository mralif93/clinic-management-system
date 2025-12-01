<div id="payslip-content" class="bg-white p-8 max-w-4xl mx-auto border border-gray-200">
    <!-- Header -->
    <div class="border-b-2 border-gray-800 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-4">
                <!-- Logo Placeholder -->
                <div
                    class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                    CMS
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">CLINIC MANAGEMENT SYSTEM</h1>
                    <p class="text-sm text-gray-600">123 Healthcare Avenue, Medical District</p>
                    <p class="text-sm text-gray-600">50450 Kuala Lumpur, Malaysia</p>
                    <p class="text-sm text-gray-600">Tel: +603-1234 5678 | Email: hr@cms.com</p>
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

    <!-- Salary Details -->
    <div class="mb-8">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="py-3 px-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                        Earnings</th>
                    <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                        Amount (RM)</th>
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
                        Amount (RM)</th>
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
                <span class="text-lg mt-1">RM</span>
                <span id="preview-net-salary">{{ number_format($payroll->net_salary ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="grid grid-cols-2 gap-12 text-sm text-gray-600 border-t border-gray-200 pt-8">
        <div>
            <p class="mb-12">This is a computer-generated document. No signature is required.</p>
            <p class="font-bold text-gray-900">CMS Admin</p>
            <p>Authorized Signatory</p>
        </div>
        <div class="text-right">
            <p class="mb-1">Date Generated: <span id="preview-generated-date">{{ now()->format('d M Y') }}</span></p>
            <p>Generated by: {{ Auth::user()->name }}</p>
        </div>
    </div>
</div>