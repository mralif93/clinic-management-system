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
                <h1 class="text-3xl font-bold text-gray-800">Generate Payslip</h1>
                <p class="text-gray-600 mt-1">Create a new payroll record for an employee</p>
            </div>
        </div>

        <form id="payrollForm" action="{{ route('admin.payrolls.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-md p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 border-b pb-3">Employee Information</h2>

                <!-- Employee Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee <span
                            class="text-red-500">*</span></label>
                    <select name="user_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ ucfirst($employee->role) }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pay Period -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period Start <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_start" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_start')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period End <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_end" required
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary (RM) <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="basic_salary" step="0.01" min="0" required id="basic_salary"
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
                        <button type="button" onclick="addAllowance()"
                            class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class='bx bx-plus-circle'></i> Add Allowance
                        </button>
                    </div>
                    <div id="allowances-container" class="space-y-2"></div>
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
                    <div id="deductions-container" class="space-y-2"></div>
                </div>

                <!-- Overtime -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hours</label>
                        <input type="number" name="overtime_hours" step="0.01" min="0" value="0" id="overtime_hours"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            oninput="calculateSalary()">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Pay (RM)</label>
                        <input type="number" name="overtime_pay" step="0.01" min="0" value="0" id="overtime_pay"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            oninput="calculateSalary()">
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Gross Salary:</span>
                        <span class="font-semibold text-gray-900">RM <span id="gross_salary">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Deductions:</span>
                        <span class="font-semibold text-red-600">RM <span id="total_deductions">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span class="text-gray-800">Net Salary:</span>
                        <span class="text-green-600">RM <span id="net_salary">0.00</span></span>
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
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Additional notes or comments..."></textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.payrolls.index') }}"
                    class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="button" onclick="previewPayslip()"
                    class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors flex items-center gap-2">
                    <i class='bx bx-show'></i> Preview Payslip
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Generate Payslip
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-start justify-center z-50 overflow-y-auto py-10">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 relative">
            <button onclick="closePreviewModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
                <i class='bx bx-x text-3xl'></i>
            </button>

            <div class="p-8">
                @include('admin.payrolls.payslip_template', ['payroll' => new \App\Models\Payroll()])
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                <button type="button" onclick="closePreviewModal()"
                    class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Close
                </button>
                <button type="button" onclick="document.getElementById('payrollForm').submit()"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Confirm & Generate
                </button>
            </div>
        </div>
    </div>

    <script>
        let allowanceCount = 0;
        let deductionCount = 0;

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

        function previewPayslip() {
            // Get form values
            const employeeSelect = document.querySelector('select[name="user_id"]');
            const employeeName = employeeSelect.options[employeeSelect.selectedIndex].text.split(' (')[0];
            const startDate = document.querySelector('input[name="pay_period_start"]').value;
            const endDate = document.querySelector('input[name="pay_period_end"]').value;
            const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
            const overtimeHours = document.getElementById('overtime_hours').value || 0;
            const overtimePay = parseFloat(document.getElementById('overtime_pay').value) || 0;
            const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
            const paymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex].text;

            // Update Preview Modal
            document.getElementById('preview-name').textContent = employeeName;
            document.getElementById('preview-period').textContent = `Period: ${formatDate(startDate)} - ${formatDate(endDate)}`;
            document.getElementById('preview-payment-method').textContent = paymentMethod;
            document.getElementById('preview-basic-salary').textContent = basicSalary.toFixed(2);

            // Allowances
            const allowancesList = document.getElementById('preview-allowances-list');
            allowancesList.innerHTML = '';
            const allowanceInputs = document.querySelectorAll('.allowance-input');
            const allowanceNames = document.querySelectorAll('.allowance-name');

            allowanceInputs.forEach((input, index) => {
                const amount = parseFloat(input.value) || 0;
                if (amount > 0) {
                    const name = allowanceNames[index].value || 'Allowance';
                    allowancesList.innerHTML += `
                                                <tr class="border-b border-gray-100">
                                                    <td class="py-3 px-4 text-gray-600">${name}</td>
                                                    <td class="py-3 px-4 text-right text-gray-900">${amount.toFixed(2)}</td>
                                                </tr>
                                            `;
                }
            });

            // Overtime
            if (overtimePay > 0) {
                document.getElementById('preview-overtime-row').style.display = 'table-row';
                document.getElementById('preview-overtime-hours').textContent = `(${overtimeHours} hrs)`;
                document.getElementById('preview-overtime-pay').textContent = overtimePay.toFixed(2);
            } else {
                document.getElementById('preview-overtime-row').style.display = 'none';
            }

            // Gross Salary
            const grossSalary = parseFloat(document.getElementById('gross_salary').textContent);
            document.getElementById('preview-gross-salary').textContent = grossSalary.toFixed(2);

            // Deductions
            const deductionsList = document.getElementById('preview-deductions-list');
            deductionsList.innerHTML = '';
            const deductionInputs = document.querySelectorAll('.deduction-input');
            const deductionNames = document.querySelectorAll('.deduction-name');
            let hasDeductions = false;

            deductionInputs.forEach((input, index) => {
                const amount = parseFloat(input.value) || 0;
                if (amount > 0) {
                    hasDeductions = true;
                    const name = deductionNames[index].value || 'Deduction';
                    deductionsList.innerHTML += `
                                                <tr class="border-b border-gray-100">
                                                    <td class="py-3 px-4 text-gray-600">${name}</td>
                                                    <td class="py-3 px-4 text-right text-red-600">- ${amount.toFixed(2)}</td>
                                                </tr>
                                            `;
                }
            });

            if (!hasDeductions) {
                deductionsList.innerHTML = `
                                            <tr class="border-b border-gray-100">
                                                <td class="py-3 px-4 text-gray-500 italic">No deductions</td>
                                                <td class="py-3 px-4 text-right text-gray-500">- 0.00</td>
                                            </tr>
                                        `;
            }

            // Total Deductions & Net Salary
            const totalDeductions = parseFloat(document.getElementById('total_deductions').textContent);
            const netSalary = parseFloat(document.getElementById('net_salary').textContent);

            document.getElementById('preview-total-deductions').textContent = '- ' + totalDeductions.toFixed(2);
            document.getElementById('preview-net-salary').textContent = netSalary.toFixed(2);

            // Show Modal
            const modal = document.getElementById('previewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-MY', { year: 'numeric', month: 'short', day: 'numeric' });
        }
    </script>
@endsection