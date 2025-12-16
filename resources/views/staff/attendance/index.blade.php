@extends('layouts.staff')

@section('title', 'My Attendance')
@section('page-title', 'My Attendance')

@section('content')
<div class="w-full space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <i class='bx bx-time text-3xl'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">My Attendance</h1>
                    <p class="text-blue-100 text-sm mt-1">Track your daily attendance records</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                    <i class='bx bx-calendar mr-1'></i>
                    {{ now()->format('F Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Status Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class='bx bx-calendar-check text-blue-500 text-xl'></i>
            <h3 class="font-semibold text-gray-800">Today's Attendance</h3>
            <span class="ml-auto text-sm text-gray-500">{{ now()->format('l, M d, Y') }}</span>
        </div>

        @if($todayAttendance)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Status</p>
                    @php
                        $statusConfig = [
                            'present' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                            'late' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                            'absent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                            'half_day' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                        ];
                        $sConfig = $statusConfig[$todayAttendance->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $sConfig['bg'] }} {{ $sConfig['text'] }}">
                        {{ ucfirst(str_replace('_', ' ', $todayAttendance->status)) }}
                    </span>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-green-600 uppercase tracking-wide mb-2">Clock In</p>
                    <p class="text-xl font-bold text-green-700">{{ $todayAttendance->clock_in_time->format('h:i A') }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-red-600 uppercase tracking-wide mb-2">Clock Out</p>
                    <p class="text-xl font-bold text-red-700">
                        {{ $todayAttendance->clock_out_time ? $todayAttendance->clock_out_time->format('h:i A') : '—' }}
                    </p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-blue-600 uppercase tracking-wide mb-2">Total Hours</p>
                    <p class="text-xl font-bold text-blue-700">
                        {{ $todayAttendance->total_hours ?? $todayAttendance->getWorkDuration() }}
                    </p>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class='bx bx-calendar-minus text-2xl text-gray-400'></i>
                </div>
                <p class="text-gray-500">No attendance record for today</p>
            </div>
        @endif
    </div>

    <!-- This Month Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-calendar text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_days'] }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Days</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-check-circle text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['present_days'] }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Present</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-time text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['late_days'] }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Late</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-timer text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_hours'], 1) }}h</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Hours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-list-ul text-blue-500'></i>
                    Attendance History
                </h3>
                <span class="text-sm text-gray-500">{{ now()->format('F Y') }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ $attendance->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $attendance->date->format('l') }}</div>
                                        <div class="text-xs text-gray-500">{{ $attendance->date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 text-sm text-green-700 font-medium">
                                    <i class='bx bx-log-in-circle'></i>
                                    {{ $attendance->clock_in_time->format('h:i A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($attendance->clock_out_time)
                                    <span class="inline-flex items-center gap-1 text-sm text-red-700 font-medium">
                                        <i class='bx bx-log-out'></i>
                                        {{ $attendance->clock_out_time->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($attendance->total_hours)
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 text-sm font-bold">
                                        {{ $attendance->total_hours }}h
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'present' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check-circle'],
                                        'late' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'bx-time'],
                                        'absent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-x-circle'],
                                        'half_day' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'bx-adjust'],
                                    ];
                                    $sConfig = $statusConfig[$attendance->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-help-circle'];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sConfig['bg'] }} {{ $sConfig['text'] }}">
                                    <i class='bx {{ $sConfig['icon'] }}'></i>
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end">
                                    @php
                                        $pendingCorrection = \App\Models\AttendanceCorrection::where('attendance_id', $attendance->id)
                                            ->where('status', 'pending')
                                            ->first();
                                    @endphp

                                    @if($pendingCorrection)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                            <i class='bx bx-time'></i> Pending
                                        </span>
                                    @else
                                        <button onclick="openCorrectionModal('{{ $attendance->id }}', '{{ $attendance->date->format('Y-m-d') }}', '{{ $attendance->clock_in_time->format('H:i') }}', '{{ $attendance->clock_out_time ? $attendance->clock_out_time->format('H:i') : '' }}')"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm hover:shadow" title="Request Correction">
                                            <i class='bx bx-edit text-sm'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No attendance records found</p>
                                    <p class="text-gray-400 text-sm mt-1">Your attendance history will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCorrectionModal(id, date, clockIn, clockOut) {
    const actionUrl = "{{ route('staff.attendance.correction') }}";
    const csrfToken = "{{ csrf_token() }}";

    Swal.fire({
        title: 'Request Correction',
        html: `
            <form id="correctionForm" action="${actionUrl}" method="POST" class="text-left space-y-4 pt-2">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="attendance_id" value="${id}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" value="${date}" disabled 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Clock In <span class="text-red-500">*</span></label>
                        <input type="time" name="clock_in_time" required value="${clockIn}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Clock Out</label>
                        <input type="time" name="clock_out_time" value="${clockOut}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason <span class="text-red-500">*</span></label>
                    <textarea name="reason" required rows="3" placeholder="Why are you requesting this change?"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bx bx-send mr-1"></i> Submit Request',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        width: '500px',
        customClass: {
            container: 'z-[100]',
            popup: 'rounded-xl',
            confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
            cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
        },
        preConfirm: () => {
            const form = document.getElementById('correctionForm');
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
