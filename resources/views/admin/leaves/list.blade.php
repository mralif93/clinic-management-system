@extends('layouts.admin')

@section('title', 'Leave Management')
@section('page-title', 'Leave Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-calendar-check text-2xl'></i>
                    </div>
                    Leave Management
                </h1>
                <p class="mt-2 text-emerald-100">Requests for {{ $monthName }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.leaves.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='bx bx-arrow-back'></i>
                    All Months
                </a>
                <a href="{{ route('admin.leaves.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-emerald-600 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg shadow-emerald-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Apply Leave
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                <p class="text-sm text-emerald-200">Total Requests</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                <p class="text-sm text-emerald-200">Pending</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['approved'] }}</p>
                <p class="text-sm text-emerald-200">Approved</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['rejected'] ?? 0 }}</p>
                <p class="text-sm text-emerald-200">Rejected</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Requests</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.leaves.by-month', ['year' => $year, 'month' => $month]) }}">
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
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm bg-white">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-medium hover:bg-emerald-700 transition-all text-sm">
                            <i class='bx bx-search'></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leaves as $leave)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all {{ $leave->trashed() ? 'opacity-60' : '' }}">
                <!-- Card Header -->
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($leave->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $leave->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($leave->user->role ?? 'N/A') }}</p>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        @php
                            $statusStyles = [
                                'pending' => 'bg-amber-50 text-amber-700 ring-amber-500/20',
                                'approved' => 'bg-green-50 text-green-700 ring-green-500/20',
                                'rejected' => 'bg-red-50 text-red-700 ring-red-500/20',
                            ];
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$leave->status] ?? $statusStyles['pending'] }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 space-y-4">
                    <!-- Leave Type -->
                    <div class="flex items-center gap-3">
                        @php
                            $typeColors = [
                                'annual' => 'from-blue-500 to-indigo-600',
                                'sick' => 'from-red-500 to-rose-600',
                                'emergency' => 'from-orange-500 to-amber-600',
                                'maternity' => 'from-pink-500 to-rose-600',
                                'paternity' => 'from-cyan-500 to-blue-600',
                                'unpaid' => 'from-gray-500 to-slate-600',
                            ];
                            $typeIcons = [
                                'annual' => 'bx-sun',
                                'sick' => 'bx-plus-medical',
                                'emergency' => 'bx-error',
                                'maternity' => 'bx-heart',
                                'paternity' => 'bx-heart',
                                'unpaid' => 'bx-wallet',
                            ];
                        @endphp
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $typeColors[$leave->leave_type] ?? 'from-gray-500 to-slate-600' }} flex items-center justify-center">
                            <i class='bx {{ $typeIcons[$leave->leave_type] ?? 'bx-calendar' }} text-white text-lg'></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] ?? ucfirst($leave->leave_type) }}</p>
                            <p class="text-xs text-gray-500">Leave Type</p>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">Duration</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class='bx bx-calendar text-gray-400'></i>
                            <span class="text-gray-700">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Reason (if any) -->
                    @if($leave->reason)
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $leave->reason }}</p>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">Submitted {{ $leave->created_at->format('M d, Y') }}</p>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.leaves.show', $leave->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                            <i class='bx bx-show text-lg'></i>
                        </a>
                        
                        @if($leave->status === 'pending')
                            <button onclick="approveLeave({{ $leave->id }})"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Approve">
                                <i class='bx bx-check text-lg'></i>
                            </button>
                            <button onclick="rejectLeave({{ $leave->id }})"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-orange-100 text-orange-600 hover:bg-orange-200 hover:scale-110 transition-all" title="Reject">
                                <i class='bx bx-x text-lg'></i>
                            </button>
                        @endif
                        
                        <a href="{{ route('admin.leaves.edit', $leave->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                            <i class='bx bx-edit text-lg'></i>
                        </a>
                        
                        <button onclick="deleteLeave({{ $leave->id }})"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                            <i class='bx bx-trash text-lg'></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-calendar-x text-4xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No leave requests found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or apply for a new leave</p>
                    <a href="{{ route('admin.leaves.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-medium hover:bg-emerald-700 transition-all text-sm mt-4">
                        <i class='bx bx-plus'></i>
                        Apply Leave
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($leaves->hasPages())
        <div class="flex justify-center">
            {{ $leaves->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function approveLeave(leaveId) {
        Swal.fire({
            title: 'Approve Leave?',
            text: "This will approve the leave request.",
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
                form.action = `/admin/leaves/${leaveId}/approve`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function rejectLeave(leaveId) {
        Swal.fire({
            title: 'Reject Leave Request',
            html: '<p class="text-gray-600 mb-3">Please provide a reason for rejection:</p>',
            input: 'textarea',
            inputPlaceholder: 'Enter rejection reason...',
            inputAttributes: { rows: 4 },
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-x mr-1"></i> Reject',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) return 'Please provide a reason for rejection';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Rejecting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/leaves/${leaveId}/reject`;
                form.innerHTML = `@csrf<input type="hidden" name="admin_notes" value="${result.value}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deleteLeave(leaveId) {
        Swal.fire({
            title: 'Delete Request?',
            text: "This will delete the leave request.",
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
                form.action = `/admin/leaves/${leaveId}`;
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
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif
@endsection