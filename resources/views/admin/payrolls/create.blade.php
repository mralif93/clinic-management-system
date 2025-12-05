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
                    <select name="user_id" id="employee_select" required onchange="updateEmploymentInfo()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                    data-employment-type="{{ $employee->employment_type ?? 'full_time' }}"
                                    data-basic-salary="{{ $employee->basic_salary ?? 0 }}"
                                    data-hourly-rate="{{ $employee->hourly_rate ?? 0 }}"
                                    data-commission-rate="{{ $employee->doctor->commission_rate ?? 60 }}">
                                {{ $employee->name }} ({{ ucfirst($employee->role) }}) - {{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? 'full_time')) }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Employment Type Info -->
                    <div id="employment_info" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="flex items-start">
                            <i class='bx bx-info-circle text-blue-600 text-xl mr-2'></i>
                            <div>
                                <p class="text-sm font-semibold text-blue-900" id="employment_type_text"></p>
                                <p class="text-xs text-blue-700 mt-1" id="employment_details"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pay Period -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period Start <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_start" id="pay_period_start" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_start')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period End <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="pay_period_end" id="pay_period_end" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('pay_period_end')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Auto Calculate Button -->
                <div class="flex justify-end">
                    <button type="button" onclick="autoCalculateSalary()" id="auto_calculate_btn"
                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class='bx bx-calculator mr-2'></i>
                        Auto Calculate Salary
                    </button>
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

        // Update employment info when employee is selected
        function updateEmploymentInfo() {
            const select = document.getElementById('employee_select');
            const selectedOption = select.options[select.selectedIndex];
            const infoDiv = document.getElementById('employment_info');
            const typeText = document.getElementById('employment_type_text');
            const detailsText = document.getElementById('employment_details');

            if (select.value) {
                const employmentType = selectedOption.dataset.employmentType;
                const basicSalary = selectedOption.dataset.basicSalary;
                const hourlyRate = selectedOption.dataset.hourlyRate;
                const commissionRate = selectedOption.dataset.commissionRate;

                infoDiv.classList.remove('hidden');

                if (employmentType === 'full_time') {
                    typeText.textContent = 'Full Time Employee';
                    detailsText.textContent = `Basic Salary: RM ${parseFloat(basicSalary).toFixed(2)}/month`;
                } else if (employmentType === 'part_time') {
                    typeText.textContent = 'Part Time Employee';
                    detailsText.textContent = `Hourly Rate: RM ${parseFloat(hourlyRate).toFixed(2)}/hour - Salary will be calculated based on attendance hours`;
                } else if (employmentType === 'locum') {
                    typeText.textContent = 'Locum Doctor';
                    detailsText.textContent = `Commission Rate: ${parseFloat(commissionRate).toFixed(2)}% - Salary will be calculated based on appointment fees`;
                }
            } else {
                infoDiv.classList.add('hidden');
            }
        }

        // Auto calculate salary based on employment type
        async function autoCalculateSalary() {
            const userId = document.getElementById('employee_select').value;
            const payPeriodStart = document.getElementById('pay_period_start').value;
            const payPeriodEnd = document.getElementById('pay_period_end').value;
            const basicSalaryInput = document.getElementById('basic_salary');
            const button = document.getElementById('auto_calculate_btn');

            if (!userId) {
                alert('Please select an employee first');
                return;
            }

            if (!payPeriodStart || !payPeriodEnd) {
                alert('Please select pay period dates');
                return;
            }

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Calculating...';

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
                        pay_period_end: payPeriodEnd
                    })
                });

                const data = await response.json();

                if (data.success) {
                    basicSalaryInput.value = data.basic_salary;
                    calculateSalary();

                    // Show success message with details
                    const details = data.details;
                    let message = `✅ Salary calculated successfully!\n\n`;
                    message += `📋 Employment Type: ${details.type}\n`;
                    message += `💰 ${details.description}\n`;

                    // Add extra details for locum doctors
                    if (details.type === 'Locum' && details.appointments) {
                        message += `\n📊 Commission Breakdown:\n`;
                        message += `   • Appointments: ${details.appointments}\n`;
                        message += `   • Total Fees: RM ${parseFloat(details.total_fee).toFixed(2)}\n`;
                        message += `   • Commission Rate: ${details.commission_rate}%\n`;
                        message += `   • Your Commission: RM ${parseFloat(data.basic_salary).toFixed(2)}`;
                    } else if (details.type === 'Part Time' && details.hours) {
                        message += `\n📊 Hours Breakdown:\n`;
                        message += `   • Total Hours: ${details.hours}h\n`;
                        message += `   • Hourly Rate: RM ${parseFloat(details.rate).toFixed(2)}\n`;
                        message += `   • Total Salary: RM ${parseFloat(data.basic_salary).toFixed(2)}`;
                    } else {
                        message += `\n💵 Salary: RM ${parseFloat(data.basic_salary).toFixed(2)}`;
                    }

                    alert(message);
                } else {
                    alert('❌ Failed to calculate salary: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while calculating salary');
            } finally {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = '<i class="bx bx-calculator mr-2"></i>Auto Calculate Salary';
            }
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-MY', { year: 'numeric', month: 'short', day: 'numeric' });
        }
    </script>
@endsection