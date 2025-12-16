@extends('layouts.doctor')

@section('title', 'My Attendance')
@section('page-title', 'My Attendance')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        <div class="relative">
            <h1 class="text-2xl font-bold flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <i class='bx bx-time text-xl'></i>
                </div>
                My Attendance
            </h1>
            <p class="text-emerald-100 mt-2">Track your attendance and work hours</p>
        </div>
    </div>

    <!-- Today's Status Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i class='bx bx-calendar-check text-emerald-500'></i> Today's Attendance
            </h3>
        </div>
        <div class="p-6">
            @if($todayAttendance)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Status</p>
                        @if($todayAttendance->status === 'present')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-700">Present</span>
                        @elseif($todayAttendance->status === 'late')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-700">Late</span>
                        @elseif($todayAttendance->status === 'half_day')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-blue-100 text-blue-700">Half Day</span>
                        @else
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-700">{{ ucfirst(str_replace('_', ' ', $todayAttendance->status)) }}</span>
                        @endif
                    </div>
                    <div class="text-center p-4 bg-emerald-50 rounded-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 mb-2">Clock In</p>
                        <p class="text-xl font-bold text-emerald-700">{{ $todayAttendance->clock_in_time->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-red-600 mb-2">Clock Out</p>
                        <p class="text-xl font-bold text-red-700">
                            {{ $todayAttendance->clock_out_time ? $todayAttendance->clock_out_time->format('h:i A') : 'Not yet' }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-purple-600 mb-2">Total Hours</p>
                        <p class="text-xl font-bold text-purple-700">
                            {{ $todayAttendance->total_hours ?? $todayAttendance->getWorkDuration() }}
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-10">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No attendance record for today</p>
                </div>
            @endif
        </div>
    </div>

    <!-- This Month Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Days</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_days'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                    <i class='bx bx-calendar text-xl'></i>
                </div>
            </div>
        </div>

        <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Present</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['present_days'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    <i class='bx bx-check-circle text-xl'></i>
                </div>
            </div>
        </div>

        <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Late</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['late_days'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                    <i class='bx bx-time text-xl'></i>
                </div>
            </div>
        </div>

        <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Hours</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_hours'], 1) }}h</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                    <i class='bx bx-stopwatch text-xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i class='bx bx-history text-blue-500'></i> Attendance History
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $attendance->date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $attendance->date->format('l') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <i class='bx bx-log-in text-emerald-600 text-sm'></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $attendance->clock_in_time->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->clock_out_time)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                                            <i class='bx bx-log-out text-red-600 text-sm'></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $attendance->clock_out_time->format('h:i A') }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->total_hours)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm font-semibold">
                                        {{ $attendance->total_hours }}h
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->status === 'present')
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">Present</span>
                                @elseif($attendance->status === 'late')
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">Late</span>
                                @elseif($attendance->status === 'half_day')
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">Half Day</span>
                                @elseif($attendance->status === 'absent')
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-700">Absent</span>
                                @else
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                    <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                                </div>
                                <p class="text-gray-500 font-medium">No attendance records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
