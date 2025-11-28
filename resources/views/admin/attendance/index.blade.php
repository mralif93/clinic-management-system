@extends('layouts.admin')

@section('title', 'Attendance Management')
@section('page-title', 'Attendance Management')

@section('content')
    <div class="w-full space-y-6">
        <!-- Today's Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Today</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayStats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class='bx bx-user-check text-3xl text-blue-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Clocked In</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $todayStats['clocked_in'] }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class='bx bx-time-five text-3xl text-green-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Late Arrivals</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $todayStats['late'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class='bx bx-error-circle text-3xl text-yellow-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class='bx bx-filter-alt mr-2'></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Attendance Records</h3>
                <div class="flex gap-2">
                    <button onclick="openAddEntryModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center">
                        <i class='bx bx-plus mr-2'></i>Add Entry
                    </button>
                    <a href="{{ route('admin.attendance.live') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-broadcast mr-2'></i>Live Status
                    </a>
                    <a href="{{ route('admin.attendance.reports') }}"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class='bx bx-bar-chart mr-2'></i>Reports
                    </a>
                    <a href="{{ route('admin.attendance.corrections') }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class='bx bx-edit mr-2'></i>Corrections
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst($attendance->user->role) }}</div>
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
                                    @if(!$attendance->is_approved)
                                        <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-700">
                                                <i class='bx bx-check-circle text-xl'></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Delete this attendance record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 ml-2">
                                            <i class='bx bx-trash text-xl'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No attendance records found
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