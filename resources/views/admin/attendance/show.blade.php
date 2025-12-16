@extends('layouts.admin')

@section('title', 'Attendance Details')
@section('page-title', 'Attendance Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-time text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Attendance Record</h1>
                        <p class="text-teal-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-calendar'></i>
                            {{ $attendance->date->format('l, F d, Y') }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @php
                                $statusConfig = [
                                    'present' => ['color' => 'bg-green-400/30', 'icon' => 'bx-check-circle'],
                                    'late' => ['color' => 'bg-yellow-400/30', 'icon' => 'bx-time'],
                                    'half_day' => ['color' => 'bg-orange-400/30', 'icon' => 'bx-time'],
                                    'absent' => ['color' => 'bg-red-400/30', 'icon' => 'bx-x-circle'],
                                    'on_leave' => ['color' => 'bg-purple-400/30', 'icon' => 'bx-calendar-exclamation'],
                                ];
                                $config = $statusConfig[$attendance->status] ?? ['color' => 'bg-gray-400/30', 'icon' => 'bx-question-mark'];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $config['color'] }}">
                                <i class='bx {{ $config['icon'] }} mr-1'></i>
                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                            </span>
                            @if($attendance->is_approved)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check mr-1'></i> Approved
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-400/30">
                                    <i class='bx bx-time mr-1'></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if(!$attendance->is_approved)
                        <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-green-600 rounded-full font-semibold hover:bg-green-50 hover:scale-105 transition-all shadow-lg">
                                <i class='bx bx-check-circle text-lg'></i>
                                Approve
                            </button>
                        </form>
                        
                        <div class="w-px h-8 bg-white/30 mx-1"></div>
                    @endif
                    
                    <a href="{{ route('admin.attendance.edit', $attendance) }}" title="Edit Attendance"
                        class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-teal-600 hover:bg-teal-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-edit text-xl'></i>
                    </a>
                    <button onclick="deleteAttendance({{ $attendance->id }}, '{{ addslashes($attendance->user->name) }}')" title="Delete"
                        class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-red-600 hover:bg-red-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-trash text-xl'></i>
                    </button>
                    <a href="{{ route('admin.attendance.by-month', ['year' => $attendance->date->year, 'month' => $attendance->date->month]) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur text-white rounded-full font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <i class='bx bx-log-in-circle text-2xl text-green-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Clock In</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $attendance->clock_in_time ? $attendance->clock_in_time->format('h:i A') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                        <i class='bx bx-log-out text-2xl text-red-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Clock Out</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $attendance->clock_out_time ? $attendance->clock_out_time->format('h:i A') : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-time text-2xl text-blue-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Hours</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . 'h' : '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                        <i class='bx bx-coffee text-2xl text-yellow-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Break Duration</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $attendance->break_duration ? $attendance->break_duration . ' min' : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Employee Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-user text-teal-600'></i>
                            Employee Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-teal-50 flex items-center justify-center">
                                <span
                                    class="text-2xl font-bold text-teal-600">{{ strtoupper(substr($attendance->user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $attendance->user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ ucfirst($attendance->user->role) }} •
                                    {{ $attendance->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-time text-teal-600'></i>
                            Time Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <i class='bx bx-log-in-circle text-2xl text-green-600 mb-2'></i>
                                <p class="text-xs text-gray-500">Clock In</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $attendance->clock_in_time ? $attendance->clock_in_time->format('h:i A') : '-' }}</p>
                                @if($attendance->clock_in_location)
                                    <p class="text-xs text-gray-400 mt-1">{{ $attendance->clock_in_location }}</p>
                                @endif
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-xl">
                                <i class='bx bx-log-out text-2xl text-red-600 mb-2'></i>
                                <p class="text-xs text-gray-500">Clock Out</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $attendance->clock_out_time ? $attendance->clock_out_time->format('h:i A') : '-' }}
                                </p>
                                @if($attendance->clock_out_location)
                                    <p class="text-xs text-gray-400 mt-1">{{ $attendance->clock_out_location }}</p>
                                @endif
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <i class='bx bx-time text-2xl text-blue-600 mb-2'></i>
                                <p class="text-xs text-gray-500">Total Hours</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . 'h' : '-' }}
                                </p>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-xl">
                                <i class='bx bx-coffee text-2xl text-yellow-600 mb-2'></i>
                                <p class="text-xs text-gray-500">Break</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $attendance->break_duration ? $attendance->break_duration . ' min' : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($attendance->notes)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class='bx bx-note text-teal-600'></i>
                                Notes
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-xl">{{ $attendance->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Breaks History -->
                @if($attendance->breaks && $attendance->breaks->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class='bx bx-coffee text-teal-600'></i>
                                Break History
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($attendance->breaks as $break)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <i class='bx bx-coffee text-xl text-yellow-600'></i>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $break->start_time->format('h:i A') }} -
                                                    {{ $break->end_time ? $break->end_time->format('h:i A') : 'Ongoing' }}</p>
                                                @if($break->reason)
                                                    <p class="text-sm text-gray-500">{{ $break->reason }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($break->duration)
                                            <span class="text-sm font-medium text-gray-600">{{ $break->duration }} min</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-badge-check text-teal-600'></i>
                            Status
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        @php
                            $statusStyle = [
                                'present' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => 'bx-check-circle'],
                                'late' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => 'bx-time'],
                                'half_day' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'icon' => 'bx-time'],
                                'absent' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => 'bx-x-circle'],
                                'on_leave' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'bx-calendar-exclamation'],
                            ];
                            $style = $statusStyle[$attendance->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'bx-question-mark'];
                        @endphp
                        <div class="inline-flex items-center justify-center w-16 h-16 {{ $style['bg'] }} rounded-full mb-3">
                            <i class='bx {{ $style['icon'] }} text-4xl {{ $style['text'] }}'></i>
                        </div>
                        <p class="text-2xl font-bold {{ $style['text'] }}">
                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</p>
                    </div>
                </div>

                <!-- Approval Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-check-shield text-teal-600'></i>
                            Approval
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        @if($attendance->is_approved)
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                <i class='bx bx-check text-3xl text-green-600'></i>
                            </div>
                            <p class="font-semibold text-green-600">Approved</p>
                            @if($attendance->approver)
                                <p class="text-sm text-gray-500 mt-2">By {{ $attendance->approver->name }}</p>
                                <p class="text-xs text-gray-400">{{ $attendance->approved_at->format('M d, Y h:i A') }}</p>
                            @endif
                        @else
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3">
                                <i class='bx bx-time text-3xl text-yellow-600'></i>
                            </div>
                            <p class="font-semibold text-yellow-600">Pending Approval</p>
                        @endif
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-git-branch text-teal-600'></i>
                            Timeline
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="relative pl-4 border-l-2 border-gray-200 space-y-6">
                            <div class="relative">
                                <div class="absolute -left-[21px] bg-green-500 h-4 w-4 rounded-full border-2 border-white">
                                </div>
                                <p class="text-sm font-bold text-gray-800">Clocked In</p>
                                <p class="text-xs text-gray-500">
                                    {{ $attendance->clock_in_time ? $attendance->clock_in_time->format('h:i A') : '-' }}</p>
                            </div>
                            @if($attendance->clock_out_time)
                                <div class="relative">
                                    <div class="absolute -left-[21px] bg-red-500 h-4 w-4 rounded-full border-2 border-white">
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">Clocked Out</p>
                                    <p class="text-xs text-gray-500">{{ $attendance->clock_out_time->format('h:i A') }}</p>
                                </div>
                            @else
                                <div class="relative">
                                    <div class="absolute -left-[21px] bg-gray-300 h-4 w-4 rounded-full border-2 border-white">
                                    </div>
                                    <p class="text-sm font-medium text-gray-400">Not Yet Clocked Out</p>
                                </div>
                            @endif
                            @if($attendance->is_approved)
                                <div class="relative">
                                    <div class="absolute -left-[21px] bg-blue-500 h-4 w-4 rounded-full border-2 border-white">
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">Approved</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $attendance->approved_at ? $attendance->approved_at->format('h:i A') : '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function deleteAttendance(id, userName) {
                Swal.fire({
                    title: 'Delete Attendance?',
                    html: `Are you sure you want to delete the attendance record for <strong>${userName}</strong>?<br><br>This action will soft delete the record.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
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