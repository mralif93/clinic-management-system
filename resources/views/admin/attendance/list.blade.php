@extends('layouts.admin')

@section('title', 'Attendance Management')
@section('page-title', 'Attendance Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-orange-600 to-amber-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
        <i class='hgi-stroke hgi-clock-02 text-2xl'></i>
    </div>
    <div>
        <h2 class="text-2xl font-bold">Attendance Management</h2>
        <p class="text-orange-100 text-sm mt-1">Records for {{ $monthName }}</p>
    </div>
</div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.attendance.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='hgi-stroke hgi-arrow-left-01 text-lg'></i>
                    All Months
                </a>
                <button onclick="openAddEntryModal()"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-orange-600 rounded-xl font-semibold hover:bg-orange-50 transition-all shadow-lg shadow-orange-900/20">
                    <i class='hgi-stroke hgi-plus-sign text-xl'></i>
                    Add Entry
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Total Records</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['present'] }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Present</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['late'] }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Late</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['absent'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Absent</p>
            </div>
        </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='hgi-stroke hgi-filter text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Records</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.attendance.by-month', ['year' => $year, 'month' => $month]) }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-600 mb-2">Date</label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-sm">
                    </div>
                    
                    <!-- User Filter -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-600 mb-2">Employee</label>
                        <select id="user_id" name="user_id" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-sm bg-white">
                            <option value="">All Employees</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ ucfirst($user->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl font-medium hover:bg-orange-700 transition-all text-sm">
                            <i class='hgi-stroke hgi-search-01'></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.attendance.by-month', ['year' => $year, 'month' => $month]) }}" 
                           class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='hgi-stroke hgi-rotate-left-01'></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $attendance->trashed() ? 'bg-red-50/30' : '' }}">
                            <!-- Employee -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($attendance->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $attendance->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($attendance->user->role ?? 'N/A') }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Date -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $attendance->date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $attendance->date->format('l') }}</p>
                            </td>
                            
                            <!-- Clock In -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                                        <i class='hgi-stroke hgi-login-01 text-green-600'></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $attendance->clock_in_time->format('h:i A') }}</span>
                                </div>
                            </td>
                            
                            <!-- Clock Out -->
                            <td class="px-6 py-4">
                                @if($attendance->clock_out_time)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                            <i class='hgi-stroke hgi-logout-01 text-red-600'></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $attendance->clock_out_time->format('h:i A') }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Still working</span>
                                @endif
                            </td>
                            
                            <!-- Hours -->
                            <td class="px-6 py-4">
                                @if($attendance->total_hours)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-sm font-semibold bg-green-50 text-green-700">
                                        <i class='hgi-stroke hgi-clock-02'></i>
                                        {{ $attendance->total_hours }}h
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'present' => 'bg-green-50 text-green-700 ring-green-500/20',
                                        'late' => 'bg-amber-50 text-amber-700 ring-amber-500/20',
                                        'absent' => 'bg-red-50 text-red-700 ring-red-500/20',
                                        'on_leave' => 'bg-purple-50 text-purple-700 ring-purple-500/20',
                                        'half_day' => 'bg-blue-50 text-blue-700 ring-blue-500/20',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$attendance->status] ?? $statusStyles['present'] }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.attendance.show', $attendance) }}"
                                       class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                        <i class='hgi-stroke hgi-eye text-lg'></i>
                                    </a>
                                    <a href="{{ route('admin.attendance.edit', $attendance) }}"
                                       class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                        <i class='hgi-stroke hgi-pencil-edit-01 text-lg'></i>
                                    </a>
                                    @if(!$attendance->is_approved)
                                        <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Approve">
                                                <i class='hgi-stroke hgi-checkmark-circle-02 text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="deleteAttendance({{ $attendance->id }}, '{{ addslashes($attendance->user->name ?? 'Unknown') }}')"
                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                        <i class='hgi-stroke hgi-delete-01 text-lg'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class='hgi-stroke hgi-calendar-03 text-4xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No attendance records found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new entry</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attendances->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function openAddEntryModal() {
        const userOptions = `@foreach($users as $user)<option value="{{ $user->id }}">{{ addslashes($user->name) }} ({{ ucfirst($user->role) }})</option>@endforeach`;
        const today = "{{ today()->format('Y-m-d') }}";
        const actionUrl = "{{ route('admin.attendance.store') }}";
        const csrfToken = "{{ csrf_token() }}";

        Swal.fire({
            title: '<span class="text-gray-900">Add Attendance Entry</span>',
            html: `
                <form id="addAttendanceForm" action="${actionUrl}" method="POST" class="text-left space-y-4 pt-2">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employee <span class="text-red-500">*</span></label>
                        <select name="user_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                            <option value="">Select Employee</option>
                            ${userOptions}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" required value="${today}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clock In <span class="text-red-500">*</span></label>
                            <input type="time" name="clock_in_time" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clock Out</label>
                            <input type="time" name="clock_out_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="absent">Absent</option>
                            <option value="on_leave">On Leave</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" placeholder="Optional notes..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="hgi-stroke hgi-floppy-disk mr-1"></i> Save Entry',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#6b7280',
            width: '480px',
            customClass: { popup: 'rounded-2xl' },
            preConfirm: () => {
                const form = document.getElementById('addAttendanceForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return false;
                }
                form.submit();
            }
        });
    }

    function deleteAttendance(id, userName) {
        Swal.fire({
            title: 'Delete Record?',
            html: `Are you sure you want to delete the attendance record for <strong>${userName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="hgi-stroke hgi-delete-01 mr-1"></i> Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/attendance/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection