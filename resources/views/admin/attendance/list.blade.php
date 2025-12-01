@extends('layouts.admin')

@section('title', 'Attendance Management')
@section('page-title', 'Attendance Management')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Attendance Management</h1>
                <p class="text-gray-600 mt-1">Records for {{ $monthName }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.attendance.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-arrow-back text-xl'></i>
                    Back to Months
                </a>
                <button onclick="openAddEntryModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-plus-circle text-xl'></i>
                    Add Entry
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Records</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-calendar text-3xl'></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Present</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['present'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-check-circle text-3xl'></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Late</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['late'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-time text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <form method="GET" action="{{ route('admin.attendance.by-month', ['year' => $year, 'month' => $month]) }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('admin.attendance.by-month', ['year' => $year, 'month' => $month]) }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Attendance List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                                            <i class='bx bx-user text-blue-600'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($attendance->user->role) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->clock_in_time->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->clock_out_time ? $attendance->clock_out_time->format('h:i A') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    {{ $attendance->total_hours ? $attendance->total_hours . 'h' : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full border {{ $attendance->status_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        @if(!$attendance->is_approved)
                                            <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-600 hover:bg-green-200 rounded-full transition" title="Approve">
                                                    <i class='bx bx-check text-xl'></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Delete this attendance record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 hover:bg-red-200 rounded-full transition" title="Delete">
                                                <i class='bx bx-trash text-xl'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class='bx bx-calendar-x text-4xl mb-2'></i>
                                    <p>No attendance records found for this month</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function openAddEntryModal() {
                const userOptions = `
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ addslashes($user->name) }} ({{ ucfirst($user->role) }})</option>
                                        @endforeach
                                    `;

                const today = "{{ today()->format('Y-m-d') }}";
                const actionUrl = "{{ route('admin.attendance.store') }}";
                const csrfToken = "{{ csrf_token() }}";

                Swal.fire({
                    title: 'Add Attendance Entry',
                    html: `
                                            <form id="addAttendanceForm" action="${actionUrl}" method="POST" class="text-left space-y-3 pt-2">
                                                <input type="hidden" name="_token" value="${csrfToken}">

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">User <span class="text-red-500">*</span></label>
                                                    <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                        <option value="">Select User</option>
                                                        ${userOptions}
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                                                    <input type="date" name="date" required value="${today}" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Clock In <span class="text-red-500">*</span></label>
                                                        <input type="time" name="clock_in_time" required 
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Clock Out</label>
                                                        <input type="time" name="clock_out_time" 
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    </div>
                                                </div>

                                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="present">Present</option>
                                        <option value="late">Late</option>
                                        <option value="half_day">Half Day</option>
                                        <option value="absent">Absent</option>
                                        <option value="on_leave">On Leave</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" rows="3" placeholder="Optional"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                </div>
                                            </form>
                                        `,
                    showCancelButton: true,
                    confirmButtonText: '<i class="bx bx-save mr-1"></i> Save Entry',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    width: '500px',
                    customClass: {
                        container: 'z-[100]', // Ensure it's above everything
                        popup: 'rounded-xl',
                        confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                        cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                    },
                    didOpen: () => {
                        // Optional: Add any initialization logic here
                    },
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
        </script>
    @endpush
@endsection