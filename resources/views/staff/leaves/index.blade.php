@extends('layouts.staff')

@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar-exclamation text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Leave Requests</h1>
                        <p class="text-purple-100 text-sm mt-1">Manage your leave applications</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-calendar mr-1'></i>
                        {{ now()->format('Y') }}
                    </div>
                    <a href="{{ route('staff.leaves.create') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-white text-purple-600 font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg hover:shadow-xl">
                        <i class='bx bx-plus mr-2 text-lg'></i>
                        Apply for Leave
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-time-five text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Pending</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Approved</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-x-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Rejected</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-gradient-to-r from-purple-50 via-indigo-50 to-purple-50 rounded-2xl border border-purple-100/50 p-5">
            <form method="GET" action="{{ route('staff.leaves.index') }}">
                <!-- Filter Options -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-purple-700 flex items-center gap-1.5">
                        <i class='bx bx-filter-alt'></i> Filters:
                    </span>

                    <select name="status"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 text-sm text-gray-700 min-w-[140px]">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    </select>

                    <select name="leave_type"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 text-sm text-gray-700 min-w-[140px]">
                        <option value="">All Types</option>
                        @foreach(\App\Models\Leave::getLeaveTypes() as $key => $label)
                            <option value="{{ $key }}" {{ request('leave_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <div class="flex items-center gap-2 ml-auto">
                        @if(request()->hasAny(['status', 'leave_type']))
                            <a href="{{ route('staff.leaves.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                                <i class='bx bx-x mr-1'></i> Clear
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-purple-600 hover:to-indigo-600 transition-all">
                            <i class='bx bx-filter-alt mr-1.5'></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Leaves List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-purple-500'></i>
                        Leave Requests
                    </h3>
                    <span class="text-sm text-gray-500">{{ $leaves->total() }} total requests</span>
                </div>
            </div>
            @if($leaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Leave Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Days</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($leaves as $leave)
                                <tr class="hover:bg-purple-50/30 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        @php
                                            $typeConfig = [
                                                'annual' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-sun'],
                                                'sick' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-plus-medical'],
                                                'emergency' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'bx-error'],
                                                'unpaid' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-wallet'],
                                                'maternity' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'icon' => 'bx-child'],
                                                'paternity' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'icon' => 'bx-child'],
                                            ];
                                            $tConfig = $typeConfig[$leave->leave_type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-calendar'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $tConfig['bg'] }} {{ $tConfig['text'] }}">
                                            <i class='bx {{ $tConfig['icon'] }}'></i>
                                            {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] ?? ucfirst($leave->leave_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $leave->start_date->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-700 text-sm font-bold">
                                            {{ $leave->total_days }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'bx-time-five'],
                                                'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check-circle'],
                                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-x-circle'],
                                            ];
                                            $sConfig = $statusConfig[$leave->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-help-circle'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sConfig['bg'] }} {{ $sConfig['text'] }}">
                                            <i class='bx {{ $sConfig['icon'] }}'></i>
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">{{ $leave->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $leave->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center gap-1.5">
                                            <a href="{{ route('staff.leaves.show', $leave->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm hover:shadow" title="View">
                                                <i class='bx bx-show text-sm'></i>
                                            </a>
                                            @if($leave->status === 'pending')
                                                <a href="{{ route('staff.leaves.edit', $leave->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center bg-amber-500 text-white hover:bg-amber-600 rounded-full transition shadow-sm hover:shadow" title="Edit">
                                                    <i class='bx bx-edit text-sm'></i>
                                                </a>
                                                <form action="{{ route('staff.leaves.destroy', $leave->id) }}" method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm hover:shadow" title="Cancel">
                                                        <i class='bx bx-trash text-sm'></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($leaves->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $leaves->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-calendar-x text-3xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No leave requests found</p>
                    <p class="text-gray-400 text-sm mt-1">You haven't submitted any leave requests yet</p>
                    <a href="{{ route('staff.leaves.create') }}"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium">
                        <i class='bx bx-plus mr-1'></i> Apply for Leave
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Cancel Leave Request?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    iconColor: '#ef4444',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="bx bx-trash mr-1"></i> Yes, Cancel',
                    cancelButtonText: 'Keep it',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl',
                        cancelButton: 'rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush
@endsection