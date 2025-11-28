@extends('layouts.doctor')

@section('title', 'My Attendance')
@section('page-title', 'My Attendance')

@section('content')
<div class="w-full space-y-6">
    <!-- Today's Status Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Attendance</h3>
        
        @if($todayAttendance)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-lg font-bold mt-1">
                        <span class="px-3 py-1 rounded-full text-sm {{ $todayAttendance->status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $todayAttendance->status)) }}
                        </span>
                    </p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Clock In</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $todayAttendance->clock_in_time->format('h:i A') }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Clock Out</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        {{ $todayAttendance->clock_out_time ? $todayAttendance->clock_out_time->format('h:i A') : 'Not yet' }}
                    </p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Total Hours</p>
                    <p class="text-lg font-bold text-green-600 mt-1">
                        {{ $todayAttendance->total_hours ?? $todayAttendance->getWorkDuration() }}
                    </p>
                </div>
            </div>
        @else
            <p class="text-center text-gray-500 py-8">No attendance record for today</p>
        @endif
    </div>

    <!-- This Month Stats -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">This Month Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-600">Total Days</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['total_days'] }}</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-green-600">Present</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['present_days'] }}</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-yellow-600">Late</p>
                <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $stats['late_days'] }}</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-purple-600">Total Hours</p>
                <p class="text-2xl font-bold text-purple-700 mt-1">{{ number_format($stats['total_hours'], 1) }}h</p>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Attendance History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
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
                                <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $attendance->status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No attendance records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
