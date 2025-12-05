@extends('layouts.admin')

@section('title', 'Payroll Management')
@section('page-title', 'Payroll Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-wallet text-2xl'></i>
                    </div>
                    Payroll Management
                </h1>
                <p class="mt-2 text-indigo-100">Payslips for {{ $monthName }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.payrolls.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='bx bx-arrow-back'></i>
                    All Months
                </a>
                <a href="{{ route('admin.payrolls.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Generate Payslip
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['total_payrolls'] }}</p>
                <p class="text-sm text-indigo-200">Total Payslips</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['pending_approval'] }}</p>
                <p class="text-sm text-indigo-200">Pending</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['paid'] ?? 0 }}</p>
                <p class="text-sm text-indigo-200">Paid</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">RM {{ number_format($stats['total_paid'], 0) }}</p>
                <p class="text-sm text-indigo-200">Total Amount</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Payslips</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.payrolls.by-month', ['year' => $year, 'month' => $month]) }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by employee name..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm bg-white">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\Payroll::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm">
                            <i class='bx bx-search'></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pay Period</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gross Salary</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deductions</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Net Salary</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $payroll->trashed() ? 'bg-red-50/30' : '' }}">
                            <!-- Employee -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($payroll->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $payroll->user->name ?? 'Unknown' }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-gray-500">{{ ucfirst($payroll->user->role ?? 'N/A') }}</span>
                                            @if($payroll->user->employment_type)
                                                @php
                                                    $empTypeStyles = [
                                                        'full_time' => 'bg-green-50 text-green-700',
                                                        'part_time' => 'bg-amber-50 text-amber-700',
                                                        'locum' => 'bg-blue-50 text-blue-700',
                                                    ];
                                                @endphp
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium {{ $empTypeStyles[$payroll->user->employment_type] ?? 'bg-gray-50 text-gray-700' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $payroll->user->employment_type)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Pay Period -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $payroll->pay_period }}</p>
                            </td>
                            
                            <!-- Gross Salary -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900">RM {{ number_format($payroll->gross_salary, 2) }}</p>
                            </td>
                            
                            <!-- Deductions -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-red-600">- RM {{ number_format($payroll->gross_salary - $payroll->net_salary, 2) }}</p>
                            </td>
                            
                            <!-- Net Salary -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-bold bg-green-50 text-green-700">
                                        <i class='bx bx-money'></i>
                                        RM {{ number_format($payroll->net_salary, 2) }}
                                    </span>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'draft' => 'bg-gray-50 text-gray-700 ring-gray-500/20',
                                        'approved' => 'bg-blue-50 text-blue-700 ring-blue-500/20',
                                        'paid' => 'bg-green-50 text-green-700 ring-green-500/20',
                                        'cancelled' => 'bg-red-50 text-red-700 ring-red-500/20',
                                    ];
                                    $statusIcons = [
                                        'draft' => 'bx-file',
                                        'approved' => 'bx-check-circle',
                                        'paid' => 'bx-badge-check',
                                        'cancelled' => 'bx-x-circle',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$payroll->status] ?? $statusStyles['draft'] }}">
                                    <i class='bx {{ $statusIcons[$payroll->status] ?? 'bx-file' }}'></i>
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.payrolls.show', $payroll->id) }}"
                                       class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    
                                    @if($payroll->status === 'draft')
                                        <button onclick="approvePayroll({{ $payroll->id }})"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Approve">
                                            <i class='bx bx-check text-lg'></i>
                                        </button>
                                        <a href="{{ route('admin.payrolls.edit', $payroll->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        <button onclick="deletePayroll({{ $payroll->id }})"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    @elseif($payroll->status === 'approved')
                                        <button onclick="markAsPaid({{ $payroll->id }})"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-purple-100 text-purple-600 hover:bg-purple-200 hover:scale-110 transition-all" title="Mark as Paid">
                                            <i class='bx bx-dollar text-lg'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class='bx bx-receipt text-4xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No payroll records found</p>
                                    <p class="text-gray-400 text-sm mt-1">Generate a new payslip to get started</p>
                                    <a href="{{ route('admin.payrolls.create') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm mt-4">
                                        <i class='bx bx-plus'></i>
                                        Generate Payslip
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payrolls->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function approvePayroll(payrollId) {
        Swal.fire({
            title: 'Approve Payslip?',
            text: "This will approve the payslip for payment.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-check mr-1"></i> Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Approving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/payrolls/${payrollId}/approve`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function markAsPaid(payrollId) {
        Swal.fire({
            title: 'Mark as Paid',
            html: '<p class="text-gray-600 mb-3">Enter payment reference (optional):</p>',
            input: 'text',
            inputPlaceholder: 'e.g., TRX12345',
            showCancelButton: true,
            confirmButtonColor: '#8b5cf6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-dollar mr-1"></i> Mark as Paid',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/payrolls/${payrollId}/mark-as-paid`;
                form.innerHTML = `@csrf<input type="hidden" name="payment_reference" value="${result.value || ''}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deletePayroll(payrollId) {
        Swal.fire({
            title: 'Delete Payslip?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-trash mr-1"></i> Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/payrolls/${payrollId}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'error', title: 'Error!', text: '{{ session('error') }}', timer: 3000, showConfirmButton: false });
    });
</script>
@endif
@endsection