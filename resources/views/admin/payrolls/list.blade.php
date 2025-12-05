@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-receipt text-primary-600 text-2xl'></i>
                    Payroll Management
                </h1>
                <p class="text-sm text-gray-600">Payslips for {{ $monthName }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.payrolls.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-arrow-back text-base'></i>
                    Back to Months
                </a>
                <a href="{{ route('admin.payrolls.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-plus-circle text-base'></i>
                    Generate Payslip
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Payrolls -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-600">Total Payrolls</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_payrolls'] }}</h3>
                    </div>
                    <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                        <i class='bx bx-receipt text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['pending_approval'] }}</h3>
                    </div>
                    <div class="bg-yellow-50 text-yellow-700 p-4 rounded-full">
                        <i class='bx bx-time-five text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Total Paid This Month -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-600">Total Paid</p>
                        <h3 class="text-3xl font-bold text-gray-900">RM {{ number_format($stats['total_paid'], 2) }}</h3>
                    </div>
                    <div class="bg-success-50 text-success-600 p-4 rounded-full">
                        <i class='bx bx-money text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.payrolls.by-month', ['year' => $year, 'month' => $month]) }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-search'></i> Search
                        </label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by patient name, doctor, or service..."
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-info-circle'></i> Status
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\Payroll::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="md:col-span-3">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-calendar'></i> Date
                        </label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                    </div>

                    <!-- Apply Filters Button -->
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                            <i class='bx bx-filter-alt text-base'></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Payrolls List -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
            @if($payrolls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left tracking-wide">Employee</th>
                                <th class="px-6 py-3 text-left tracking-wide">Pay Period</th>
                                <th class="px-6 py-3 text-left tracking-wide">Gross Salary</th>
                                <th class="px-6 py-3 text-left tracking-wide">Net Salary</th>
                                <th class="px-6 py-3 text-left tracking-wide">Status</th>
                                <th class="px-6 py-3 text-left tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($payrolls as $payroll)
                                <tr class="hover:bg-gray-50 transition-colors {{ $payroll->trashed() ? 'opacity-60' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-primary-50 text-primary-700 p-2 rounded-full">
                                                <i class='bx bx-user text-xl'></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $payroll->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ ucfirst($payroll->user->role) }}</p>
                                                @if($payroll->user->employment_type)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mt-1
                                                        {{ $payroll->user->employment_type === 'locum' ? 'bg-primary-50 text-primary-700' :
                                                           ($payroll->user->employment_type === 'part_time' ? 'bg-yellow-50 text-yellow-700' : 'bg-success-50 text-success-600') }}">
                                                        @if($payroll->user->employment_type === 'locum')
                                                            <i class='bx bx-briefcase-alt text-xs mr-1'></i>
                                                        @endif
                                                        {{ ucfirst(str_replace('_', ' ', $payroll->user->employment_type)) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $payroll->pay_period }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        RM {{ number_format($payroll->gross_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-success-600">
                                        RM {{ number_format($payroll->net_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $payroll->status_color }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.payrolls.show', $payroll->id) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 text-xs"
                                                title="View">
                                                <i class='bx bx-info-circle text-base'></i>
                                            </a>

                                            @if($payroll->status === 'draft')
                                                <button onclick="approvePayroll({{ $payroll->id }})"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 text-xs"
                                                    title="Approve">
                                                    <i class='bx bx-check text-base'></i>
                                                </button>

                                                <a href="{{ route('admin.payrolls.edit', $payroll->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-xs"
                                                    title="Edit">
                                                    <i class='bx bx-pencil text-base'></i>
                                                </a>

                                                <form action="{{ route('admin.payrolls.destroy', $payroll->id) }}" method="POST"
                                                    class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                        title="Delete">
                                                        <i class='bx bx-trash text-base'></i>
                                                    </button>
                                                </form>
                                            @elseif($payroll->status === 'approved')
                                                <button onclick="markAsPaid({{ $payroll->id }})"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 text-xs"
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