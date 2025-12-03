@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Payroll Management</h1>
                <p class="text-gray-600 mt-1">Payslips for {{ $monthName }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.payrolls.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-arrow-back text-xl'></i>
                    Back to Months
                </a>
                <a href="{{ route('admin.payrolls.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-plus-circle text-xl'></i>
                    Generate Payslip
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Payrolls -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Payrolls</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['total_payrolls'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-receipt text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Pending Approval</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['pending_approval'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-time-five text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Total Paid This Month -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Paid</p>
                        <h3 class="text-3xl font-bold mt-2">RM {{ number_format($stats['total_paid'], 2) }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-money text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <form method="GET" action="{{ route('admin.payrolls.by-month', ['year' => $year, 'month' => $month]) }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                    <select name="user_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Employees</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('user_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ ucfirst($employee->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        @foreach(\App\Models\Payroll::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('admin.payrolls.by-month', ['year' => $year, 'month' => $month]) }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Payrolls List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($payrolls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Employee</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Pay Period</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Gross Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Net Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payrolls as $payroll)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-blue-100 p-2 rounded-full">
                                                <i class='bx bx-user text-xl text-blue-600'></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $payroll->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ ucfirst($payroll->user->role) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $payroll->pay_period }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        RM {{ number_format($payroll->gross_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                                        RM {{ number_format($payroll->net_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium border {{ $payroll->status_color }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.payrolls.show', $payroll->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm"
                                                title="View">
                                                <i class='bx bx-info-circle text-base'></i>
                                            </a>

                                            @if($payroll->status === 'draft')
                                                <button onclick="approvePayroll({{ $payroll->id }})"
                                                    class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                    title="Approve">
                                                    <i class='bx bx-check text-base'></i>
                                                </button>

                                                <a href="{{ route('admin.payrolls.edit', $payroll->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm"
                                                    title="Edit">
                                                    <i class='bx bx-pencil text-base'></i>
                                                </a>

                                                <form action="{{ route('admin.payrolls.destroy', $payroll->id) }}" method="POST"
                                                    class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                        title="Delete">
                                                        <i class='bx bx-trash text-base'></i>
                                                    </button>
                                                </form>
                                            @elseif($payroll->status === 'approved')
                                                <button onclick="markAsPaid({{ $payroll->id }})"
                                                    class="w-8 h-8 flex items-center justify-center bg-purple-500 text-white hover:bg-purple-600 rounded-full transition shadow-sm"
                                                    title="Mark as Paid">
                                                    <i class='bx bx-dollar text-base'></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $payrolls->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-receipt text-6xl text-gray-300'></i>
                    <p class="text-gray-500 mt-4 text-lg">No payroll records found for this month</p>
                    <a href="{{ route('admin.payrolls.create') }}"
                        class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Generate Payslip
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Approve Payroll
        function approvePayroll(payrollId) {
            Swal.fire({
                title: 'Approve Payroll?',
                text: "This will approve the payslip for payment.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/payrolls/${payrollId}/approve`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Mark as Paid
        function markAsPaid(payrollId) {
            Swal.fire({
                title: 'Mark as Paid',
                text: "Enter the payment reference number (optional):",
                input: 'text',
                inputPlaceholder: 'Payment Reference (e.g., TRX12345)',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, mark as paid!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/payrolls/${payrollId}/mark-as-paid`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const referenceInput = document.createElement('input');
                    referenceInput.type = 'hidden';
                    referenceInput.name = 'payment_reference';
                    referenceInput.value = result.value;
                    form.appendChild(referenceInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Delete Confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection