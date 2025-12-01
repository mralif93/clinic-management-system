@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.payrolls.index') }}"
                class="bg-white p-2 rounded-full shadow-sm hover:shadow-md transition-shadow text-gray-600">
                <i class='bx bx-arrow-back text-2xl'></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Payslip</h1>
                <p class="text-gray-600 mt-1">Edit payroll record for {{ $payroll->user->name }}</p>
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
                    <select name="user_id" required
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period Start <span class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_start" required value="{{ old('pay_period_start', $payroll->pay_period_start->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_start')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period End <span class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_end" required value="{{ old('pay_period_end', $payroll->pay_period_end->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_end')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
                            <i class='bx bx-plus-circle'></i> Add Allowance
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
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Deductions -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Deductions</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="autoCalculateDeductions()"
                                class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center gap-1">
                                <i class='bx bx-calculator'></i> Auto Calculate
                            </button>
                            <button type="button" onclick="addDeduction()"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class='bx bx-plus-circle'></i> Add Deduction
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
                                        <i class='bx bx-trash'></i>
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
                    <i class='bx bx-trash'></i>
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
                    <i class='bx bx-trash'></i>
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

        function autoCalculateDeductions() {
            const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
            
            // Calculate total allowances for base calculation
            const allowanceInputs = document.querySelectorAll('.allowance-input');
            let totalAllowances = 0;
            allowanceInputs.forEach(input => {
                totalAllowances += parseFloat(input.value) || 0;
            });

            const baseAmount = basicSalary + totalAllowances;

            // Get rates from server-side settings
            const rates = @json($payrollSettings);

            // Calculate amounts
            const epf = baseAmount * (rates.epf_employee / 100);
            const socso = baseAmount * (rates.socso_employee / 100);
            const eis = baseAmount * (rates.eis_employee / 100);
            const tax = baseAmount * (rates.tax / 100);

            // Clear existing deductions
            document.getElementById('deductions-container').innerHTML = '';
            deductionCount = 0;

            // Add new deductions
            addDeductionItem('EPF (Employee)', epf);
            addDeductionItem('SOCSO (Employee)', socso);
            addDeductionItem('EIS (Employee)', eis);
            if (tax > 0) {
                addDeductionItem('Tax (PCB)', tax);
            }

            calculateSalary();
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
                    <i class='bx bx-trash'></i>
                </button>
            `;
            container.appendChild(div);
        }
    </script>
@endsection