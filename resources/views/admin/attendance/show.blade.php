@extends('layouts.admin')

@section('title', 'Attendance Details')
@section('page-title', 'Attendance Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.attendance.by-month', ['year' => $attendance->date->year, 'month' => $attendance->date->month]) }}"
                class="bg-white p-2 rounded-full shadow-sm hover:shadow-md transition-shadow text-gray-600">
                <i class='bx bx-arrow-back text-2xl'></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Attendance Details</h1>
                <p class="text-gray-600 mt-1">{{ $attendance->date->format('l, F d, Y') }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            @if(!$attendance->is_approved)
                <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class='bx bx-check-circle'></i> Approve
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.attendance.edit', $attendance) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class='bx bx-edit'></i> Edit
            </a>
            <button onclick="deleteAttendance({{ $attendance->id }}, '{{ addslashes($attendance->user->name) }}')"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class='bx bx-trash'></i> Delete
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class='bx bx-user text-3xl text-blue-600'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Employee</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $attendance->user->name }}</h3>
                        <p class="text-gray-600">{{ ucfirst($attendance->user->role) }} • {{ $attendance->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Time Details Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Time Details</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <i class='bx bx-log-in-circle text-3xl text-green-600 mb-2'></i>
                        <p class="text-sm font-medium text-gray-500">Clock In</p>
                        <p class="text-xl font-bold text-gray-800">{{ $attendance->clock_in_time ? $attendance->clock_in_time->format('h:i A') : '-' }}</p>
                        @if($attendance->clock_in_location)
                            <p class="text-xs text-gray-500 mt-1">{{ $attendance->clock_in_location }}</p>
                        @endif
                    </div>
                    
                    <div class="text-center p-4 bg-red-50 rounded-xl">
                        <i class='bx bx-log-out-circle text-3xl text-red-600 mb-2'></i>
                        <p class="text-sm font-medium text-gray-500">Clock Out</p>
                        <p class="text-xl font-bold text-gray-800">{{ $attendance->clock_out_time ? $attendance->clock_out_time->format('h:i A') : '-' }}</p>
                        @if($attendance->clock_out_location)
                            <p class="text-xs text-gray-500 mt-1">{{ $attendance->clock_out_location }}</p>
                        @endif
                    </div>
                    
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <i class='bx bx-time-five text-3xl text-blue-600 mb-2'></i>
                        <p class="text-sm font-medium text-gray-500">Total Hours</p>
                        <p class="text-xl font-bold text-gray-800">{{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . 'h' : '-' }}</p>
                    </div>
                    
                    <div class="text-center p-4 bg-yellow-50 rounded-xl">
                        <i class='bx bx-coffee text-3xl text-yellow-600 mb-2'></i>
                        <p class="text-sm font-medium text-gray-500">Break Duration</p>
                        <p class="text-xl font-bold text-gray-800">{{ $attendance->break_duration ? $attendance->break_duration . ' min' : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            @if($attendance->notes)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Notes</h3>
                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 leading-relaxed">
                    {{ $attendance->notes }}
                </div>
            </div>
            @endif

            <!-- Breaks Card -->
            @if($attendance->breaks && $attendance->breaks->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Break History</h3>
                <div class="space-y-3">
                    @foreach($attendance->breaks as $break)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-coffee text-xl text-yellow-600'></i>
                            <div>
                                <p class="font-medium text-gray-800">{{ $break->start_time->format('h:i A') }} - {{ $break->end_time ? $break->end_time->format('h:i A') : 'Ongoing' }}</p>
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
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Status</h3>
                <div class="text-center py-4">
                    @php
                        $statusConfig = [
                            'present' => ['color' => 'green', 'icon' => 'bx-check-circle'],
                            'late' => ['color' => 'yellow', 'icon' => 'bx-time'],
                            'half_day' => ['color' => 'orange', 'icon' => 'bx-time-five'],
                            'absent' => ['color' => 'red', 'icon' => 'bx-x-circle'],
                            'on_leave' => ['color' => 'purple', 'icon' => 'bx-calendar-exclamation'],
                        ];
                        $config = $statusConfig[$attendance->status] ?? ['color' => 'gray', 'icon' => 'bx-question-mark'];
                    @endphp
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-{{ $config['color'] }}-100 rounded-full mb-3">
                        <i class='bx {{ $config['icon'] }} text-4xl text-{{ $config['color'] }}-600'></i>
                    </div>
                    <p class="text-2xl font-bold text-{{ $config['color'] }}-600">{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</p>
                </div>
            </div>

            <!-- Approval Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Approval</h3>
                @if($attendance->is_approved)
                    <div class="text-center py-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                            <i class='bx bx-check text-3xl text-green-600'></i>
                        </div>
                        <p class="font-semibold text-green-600">Approved</p>
                        @if($attendance->approver)
                            <p class="text-sm text-gray-500 mt-2">By {{ $attendance->approver->name }}</p>
                            <p class="text-xs text-gray-400">{{ $attendance->approved_at->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3">
                            <i class='bx bx-time text-3xl text-yellow-600'></i>
                        </div>
                        <p class="font-semibold text-yellow-600">Pending Approval</p>
                    </div>
                @endif
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Timeline</h3>
                <div class="relative pl-4 border-l-2 border-gray-200 space-y-6">
                    <!-- Clock In -->
                    <div class="relative">
                        <div class="absolute -left-[21px] bg-green-500 h-4 w-4 rounded-full border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-800">Clocked In</p>
                        <p class="text-xs text-gray-500">{{ $attendance->clock_in_time ? $attendance->clock_in_time->format('h:i A') : '-' }}</p>
                    </div>

                    <!-- Clock Out -->
                    @if($attendance->clock_out_time)
                        <div class="relative">
                            <div class="absolute -left-[21px] bg-red-500 h-4 w-4 rounded-full border-2 border-white"></div>
                            <p class="text-sm font-bold text-gray-800">Clocked Out</p>
                            <p class="text-xs text-gray-500">{{ $attendance->clock_out_time->format('h:i A') }}</p>
                        </div>
                    @else
                        <div class="relative">
                            <div class="absolute -left-[21px] bg-gray-300 h-4 w-4 rounded-full border-2 border-white"></div>
                            <p class="text-sm font-medium text-gray-400">Not Yet Clocked Out</p>
                        </div>
                    @endif

                    <!-- Approved -->
                    @if($attendance->is_approved)
                        <div class="relative">
                            <div class="absolute -left-[21px] bg-blue-500 h-4 w-4 rounded-full border-2 border-white"></div>
                            <p class="text-sm font-bold text-gray-800">Approved</p>
                            <p class="text-xs text-gray-500">{{ $attendance->approved_at ? $attendance->approved_at->format('h:i A') : '-' }}</p>
                        </div>
                    @endif
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

