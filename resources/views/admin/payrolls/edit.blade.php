@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-8">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                    <i class='hgi-stroke hgi-pencil-edit-01 text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Edit Payslip</h2>
                    <p class="text-blue-100 text-sm mt-1">Edit payroll record for {{ $payroll->user->name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.payrolls.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                <i class='hgi-stroke hgi-arrow-left-01'></i>
                Back to List
            </a>
        </div>
    </div>

        <form action="{{ route('admin.payrolls.update', $payroll->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Employee Information</h2>

                <!-- Employee Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee <span class="text-red-500">*</span></label>
                    <select name="user_id" id="employee_select" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('user_id', $payroll->user_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ ucfirst($employee->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pay Period -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period Start <span class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_start" id="pay_period_start" required value="{{ old('pay_period_start', $payroll->pay_period_start->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_start')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period End <span class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_end" id="pay_period_end" required value="{{ old('pay_period_end', $payroll->pay_period_end->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_end')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Release Date</label>
                        <input type="date" name="payment_release_date" id="payment_release_date" value="{{ old('payment_release_date', $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('payment_release_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Auto Calculate Button -->
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="autoCalculateSalary()" id="auto_calculate_btn"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <i class='hgi-stroke hgi-calendar-03'></i>
                        Get Prorated Salary
                    </button>
                </div>
            </div>

            <!-- Salary Details -->
            <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Salary Details</h2>

                <!-- Basic Salary -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary (RM) <span class="text-red-500">*</span></label>
                    <input type="number" name="basic_salary" step="0.01" min="0" required id="basic_salary" value="{{ old('basic_salary', $payroll->basic_salary) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        oninput="calculateSalary()">
                    @error('basic_salary')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Allowances -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Allowances</label>
                        <button type="button" onclick="addAllowance()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class='hgi-stroke hgi-plus-sign'></i> Add Allowance
                        </button>
                    </div>
                    <div id="allowances-container" class="space-y-2">
                        @if($payroll->allowances)
                            @foreach($payroll->allowances as $name => $amount)
                                <div class="flex gap-2">
                                    <input type="text" name="allowance_names[]" value="{{ str_replace('_', ' ', $name) }}" placeholder="Allowance name"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent allowance-name">
                                    <input type="number" name="allowance_amounts[]" value="{{ $amount }}" step="0.01" min="0" placeholder="Amount"
                                        class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent allowance-input"
                                        oninput="calculateSalary()">
                                    <button type="button" onclick="this.parentElement.remove(); calculateSalary();"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        <i class='hgi-stroke hgi-delete-01'></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Deductions -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Deductions <span class="text-xs text-gray-500 font-normal ml-2">(EPF, SOCSO, EIS, Tax)</span></label>
                        <div class="flex gap-2">
                            <button type="button" id="auto_calculate_statutory_btn" onclick="autoCalculateStatutory()"
                                class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center gap-1">
                                <i class='hgi-stroke hgi-calculator'></i> Auto Calculate Statutory
                            </button>
                            <button type="button" onclick="addDeduction()"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class='hgi-stroke hgi-plus-sign'></i> Add Deduction
                            </button>
                        </div>
                    </div>
                    <div id="deductions-container" class="space-y-2">
                        @if($payroll->deductions)
                            @foreach($payroll->deductions as $name => $amount)
                                <div class="flex gap-2">
                                    <input type="text" name="deduction_names[]" value="{{ str_replace('_', ' ', $name) }}" placeholder="Deduction name"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent deduction-name">
                                    <input type="number" name="deduction_amounts[]" value="{{ $amount }}" step="0.01" min="0" placeholder="Amount"
                                        class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent deduction-input"
                                        oninput="calculateSalary()">
                                    <button type="button" onclick="this.parentElement.remove(); calculateSalary();"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        <i class='hgi-stroke hgi-delete-01'></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Employer Contributions -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Employer Contributions <span class="text-xs text-gray-500 font-normal ml-2">(EPF, SOCSO, EIS)</span></label>
                        <button type="button" onclick="addEmployerContribution()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class='hgi-stroke hgi-plus-sign'></i> Add Contribution
                        </button>
                    </div>
                    <div id="employer-contributions-container" class="space-y-2">
                        @if($payroll->employer_deductions)
                            @foreach($payroll->employer_deductions as $name => $amount)
                                <div class="flex gap-2">
                                    <input type="text" name="employer_deduction_names[]" value="{{ str_replace('_', ' ', $name) }}" placeholder="Contribution name"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent employer-deduction-name">
                                    <input type="number" name="employer_deduction_amounts[]" value="{{ $amount }}" step="0.01" min="0" placeholder="Amount"
                                        class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent employer-deduction-input">
                                    <button type="button" onclick="this.parentElement.remove();"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        <i class='hgi-stroke hgi-delete-01'></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Overtime -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hours</label>
                        <input type="number" name="overtime_hours" step="0.01" min="0" value="{{ old('overtime_hours', $payroll->overtime_hours) }}" id="overtime_hours"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            oninput="calculateSalary()">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Pay (RM)</label>
                        <input type="number" name="overtime_pay" step="0.01" min="0" value="{{ old('overtime_pay', $payroll->overtime_pay) }}" id="overtime_pay"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            oninput="calculateSalary()">
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Gross Salary:</span>
                        <span class="font-semibold text-gray-900">RM <span id="gross_salary">{{ number_format($payroll->gross_salary, 2) }}</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Deductions:</span>
                        <span class="font-semibold text-red-600">RM <span id="total_deductions">{{ number_format($payroll->total_deductions, 2) }}</span></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span class="text-gray-800">Net Salary:</span>
                        <span class="text-green-600">RM <span id="net_salary">{{ number_format($payroll->net_salary, 2) }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Additional Information</h2>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Payment Method</option>
                        @foreach(\App\Models\Payroll::getPaymentMethods() as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_method', $payroll->payment_method) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Additional notes or comments...">{{ old('notes', $payroll->notes) }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.payrolls.index') }}"
                    class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Payslip
                </button>
            </div>
        </form>
    </div>

    <!-- Custom Alert Modal -->
    <div id="customAlertModal" class="fixed inset-0 bg-black/40 backdrop-blur-md hidden items-center justify-center z-[100] transition-opacity duration-300 opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md transform scale-95 transition-transform duration-300">
            <div id="alertIcon" class="mb-4"></div>
            <h3 id="alertTitle" class="text-xl font-bold text-gray-900 mb-2"></h3>
            <div id="alertMessage" class="text-gray-600 text-sm whitespace-pre-line mb-6"></div>
            <div class="flex justify-end">
                <button type="button" onclick="closeCustomAlert()" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-200">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialize counts based on existing items
        let allowanceCount = {{ $payroll->allowances ? count($payroll->allowances) : 0 }};
        let deductionCount = {{ $payroll->deductions ? count($payroll->deductions) : 0 }};

        // Calculate initial values on load
        document.addEventListener('DOMContentLoaded', function() {
            calculateSalary();
        });

        function addAllowance() {
            const container = document.getElementById('allowances-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="allowance_names[]" placeholder="Allowance name (e.g., Housing)"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent allowance-name">
                <input type="number" name="allowance_amounts[]" step="0.01" min="0" placeholder="Amount"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent allowance-input"
                    oninput="calculateSalary()">
                <button type="button" onclick="this.parentElement.remove(); calculateSalary();"
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class='hgi-stroke hgi-delete-01'></i>
                </button>
            `;
            container.appendChild(div);
        }

        function addDeduction() {
            const container = document.getElementById('deductions-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="deduction_names[]" placeholder="Deduction name (e.g., Tax)"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent deduction-name">
                <input type="number" name="deduction_amounts[]" step="0.01" min="0" placeholder="Amount"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent deduction-input"
                    oninput="calculateSalary()">
                <button type="button" onclick="this.parentElement.remove(); calculateSalary();"
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class='hgi-stroke hgi-delete-01'></i>
                </button>
            `;
            container.appendChild(div);
        }

        function addEmployerContribution() {
            const container = document.getElementById('employer-contributions-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="employer_deduction_names[]" placeholder="Contribution name (e.g., EPF)"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent employer-deduction-name">
                <input type="number" name="employer_deduction_amounts[]" step="0.01" min="0" placeholder="Amount"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent employer-deduction-input">
                <button type="button" onclick="this.parentElement.remove();"
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class='hgi-stroke hgi-delete-01'></i>
                </button>
            `;
            container.appendChild(div);
        }

        function calculateSalary() {
            const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
            const overtimePay = parseFloat(document.getElementById('overtime_pay').value) || 0;

            // Calculate total allowances
            const allowanceInputs = document.querySelectorAll('.allowance-input');
            let totalAllowances = 0;
            allowanceInputs.forEach(input => {
                totalAllowances += parseFloat(input.value) || 0;
            });

            // Calculate total deductions
            const deductionInputs = document.querySelectorAll('.deduction-input');
            let totalDeductions = 0;
            deductionInputs.forEach(input => {
                totalDeductions += parseFloat(input.value) || 0;
            });

            const grossSalary = basicSalary + totalAllowances + overtimePay;
            const netSalary = grossSalary - totalDeductions;

            document.getElementById('gross_salary').textContent = grossSalary.toFixed(2);
            document.getElementById('total_deductions').textContent = totalDeductions.toFixed(2);
            document.getElementById('net_salary').textContent = netSalary.toFixed(2);
        }

        async function autoCalculateSalary() {
            const userId = document.querySelector('select[name="user_id"]').value;
            const payPeriodStart = document.getElementById('pay_period_start').value;
            const payPeriodEnd = document.getElementById('pay_period_end').value;
            const basicSalaryInput = document.getElementById('basic_salary');
            const button = document.getElementById('auto_calculate_btn');

            if (!userId) {
                showCustomAlert('Validation Error', 'Please select an employee first', false);
                return;
            }

            if (!payPeriodStart || !payPeriodEnd) {
                showCustomAlert('Validation Error', 'Please select pay period dates', false);
                return;
            }
            
            const manualBasicSalary = basicSalaryInput.value ? parseFloat(basicSalaryInput.value) : null;

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="hgi-stroke hgi-loading-02 animate-spin"></i> Calculating...';

            try {
                const response = await fetch('{{ route("admin.payrolls.calculate-salary") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        pay_period_start: payPeriodStart,
                        pay_period_end: payPeriodEnd,
                        basic_salary: manualBasicSalary
                    })
                });

                const data = await response.json();

                if (data.success) {
                    basicSalaryInput.value = data.basic_salary;
                    calculateSalary();

                    // Show success message with details
                    const details = data.base_details;
                    let message = `✅ Prorated base salary calculated successfully!\n\n`;
                    message += `📋 Employment Type: ${details.type}\n`;
                    
                    if (details.description) {
                        message += `💰 ${details.description}\n`;
                    }

                    if (details.type === 'Locum' && details.appointments) {
                        message += `\n📊 Commission Breakdown:\n`;
                        message += `   • Appointments: ${details.appointments}\n`;
                        message += `   • Total Fees: RM ${parseFloat(details.total_fee).toFixed(2)}\n`;
                        message += `   • Commission Rate: ${details.commission_rate}%\n`;
                    } else if (details.type === 'Part Time' && details.hours) {
                        message += `\n📊 Hours Breakdown:\n`;
                        message += `   • Total Hours: ${details.hours}h\n`;
                        message += `   • Hourly Rate: RM ${parseFloat(details.rate).toFixed(2)}\n`;
                    }
                    
                    message += `\n💵 Calculated Basic Salary: RM ${parseFloat(data.basic_salary).toFixed(2)}`;
                    
                    showCustomAlert('Salary Prorated', message, true);
                } else {
                    showCustomAlert('Calculation Failed', data.message || 'Unknown error', false);
                }
            } catch (error) {
                console.error('Error:', error);
                showCustomAlert('Error', 'An error occurred while getting prorated salary', false);
            } finally {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = '<i class="hgi-stroke hgi-calendar-03"></i> Get Prorated Salary';
            }
        }

        async function autoCalculateStatutory() {
            const basicSalaryInput = document.getElementById('basic_salary');
            const button = document.getElementById('auto_calculate_statutory_btn');
            
            if (!basicSalaryInput.value || parseFloat(basicSalaryInput.value) <= 0) {
                showCustomAlert('Validation Error', 'Basic salary must be greater than 0 to calculate deductions', false);
                return;
            }

            // Gather extra inputs for calculation
            const allowanceInputs = document.querySelectorAll('.allowance-input');
            let allowanceAmounts = [];
            allowanceInputs.forEach(input => {
                allowanceAmounts.push(parseFloat(input.value) || 0);
            });
            const overtimePay = parseFloat(document.getElementById('overtime_pay').value) || 0;
            const currentBasicSalary = parseFloat(basicSalaryInput.value);

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="hgi-stroke hgi-loading-02 animate-spin"></i> Calculating...';

            try {
                const response = await fetch('{{ route("admin.payrolls.calculate-statutory") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: document.getElementById('employee_select').value,
                        basic_salary: currentBasicSalary,
                        allowance_amounts: allowanceAmounts,
                        overtime_pay: overtimePay
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Auto-fill statutory deductions
                    document.getElementById('deductions-container').innerHTML = '';
                    
                    // Clear previous employer deductions hidden inputs
                    document.querySelectorAll('.employer-deduction-hidden').forEach(el => el.remove());
                    
                    const formContainer = document.getElementById('deductions-container').parentElement;
                    
                    if(data.statutory_deductions) {
                        const stat = data.statutory_deductions;
                        if(stat.epf_employee > 0) addDeductionItem('EPF (Employee)', stat.epf_employee);
                        if(stat.socso_employee > 0) addDeductionItem('SOCSO (Employee)', stat.socso_employee);
                        if(stat.eis_employee > 0) addDeductionItem('EIS (Employee)', stat.eis_employee);
                        if(stat.pcb > 0) addDeductionItem('Tax (PCB)', stat.pcb);
                        
                        // Auto-fill employer deductions visibly instead of hidden
                        document.getElementById('employer-contributions-container').innerHTML = '';
                        if(stat.epf_employer > 0) addEmployerContributionItem('EPF (Employer)', stat.epf_employer);
                        if(stat.socso_employer > 0) addEmployerContributionItem('SOCSO (Employer)', stat.socso_employer);
                        if(stat.eis_employer > 0) addEmployerContributionItem('EIS (Employer)', stat.eis_employer);
                    }
                    
                    calculateSalary();
                    showCustomAlert('Calculation Successful', '🛡️ Statutory Deductions (EPF, SOCSO, EIS, PCB) successfully applied to the form based on subjective earnings.', true);
                } else {
                    showCustomAlert('Calculation Failed', data.message || 'Unknown error', false);
                }
            } catch (error) {
                console.error('Error:', error);
                showCustomAlert('Error', 'An error occurred while calculating deductions', false);
            } finally {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = '<i class="hgi-stroke hgi-calculator"></i> Auto Calculate Statutory';
            }
        }

        function addDeductionItem(name, amount) {
            const container = document.getElementById('deductions-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="deduction_names[]" value="${name}"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent deduction-name">
                <input type="number" name="deduction_amounts[]" value="${amount.toFixed(2)}" step="0.01" min="0"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent deduction-input"
                    oninput="calculateSalary()">
                <button type="button" onclick="this.parentElement.remove(); calculateSalary();"
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class='hgi-stroke hgi-delete-01'></i>
                </button>
            `;
            container.appendChild(div);
        }

        function addEmployerContributionItem(name, amount) {
            const container = document.getElementById('employer-contributions-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="employer_deduction_names[]" value="${name}"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent employer-deduction-name">
                <input type="number" name="employer_deduction_amounts[]" value="${amount.toFixed(2)}" step="0.01" min="0"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent employer-deduction-input">
                <button type="button" onclick="this.parentElement.remove();"
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class='hgi-stroke hgi-delete-01'></i>
                </button>
            `;
            container.appendChild(div);
        }
        
        // Custom Modal Logic
        function showCustomAlert(title, message, isSuccess = true) {
            const modal = document.getElementById('customAlertModal');
            const modalInner = modal.querySelector('div.bg-white');
            const titleEl = document.getElementById('alertTitle');
            const messageEl = document.getElementById('alertMessage');
            const iconEl = document.getElementById('alertIcon');

            titleEl.textContent = title;
            messageEl.textContent = message;

            if (isSuccess) {
                iconEl.innerHTML = `<div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center"><i class="hgi-stroke hgi-tick-02 text-2xl text-green-600"></i></div>`;
            } else {
                iconEl.innerHTML = `<div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center"><i class="hgi-stroke hgi-alert-02 text-2xl text-red-600"></i></div>`;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // slight delay for animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalInner.classList.remove('scale-95');
            }, 10);
        }

        function closeCustomAlert() {
            const modal = document.getElementById('customAlertModal');
            const modalInner = modal.querySelector('div.bg-white');
            
            modal.classList.add('opacity-0');
            modalInner.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
@endsection