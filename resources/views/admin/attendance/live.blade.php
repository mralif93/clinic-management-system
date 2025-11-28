@extends('layouts.admin')

@section('title', 'Live Attendance Status')
@section('page-title', 'Live Attendance Status')

@section('content')
    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Who's In Now</h2>
                    <p class="text-sm text-gray-600 mt-1">Real-time attendance status for {{ today()->format('l, F d, Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Last Updated</p>
                    <p class="text-lg font-semibold text-gray-900">{{ now()->format('h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Currently Clocked In</p>
                        <p class="text-4xl font-bold text-green-600 mt-2">{{ $clockedIn->count() }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class='bx bx-user-check text-4xl text-green-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Late Arrivals</p>
                        <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $lateToday->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class='bx bx-error-circle text-4xl text-yellow-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">On Break</p>
                        <p class="text-4xl font-bold text-blue-600 mt-2">
                            {{ $clockedIn->filter(fn($a) => $a->isOnBreak())->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class='bx bx-coffee text-4xl text-blue-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currently Clocked In -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Currently Clocked In</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock In Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($clockedIn as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx bx-user text-xl text-green-600'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $attendance->user->role === 'staff' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($attendance->user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->clock_in_time->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    {{ $attendance->getWorkDuration() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->isOnBreak())
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 border border-blue-300">
                                            <i class='bx bx-coffee'></i> On Break
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class='bx bx-user-x text-4xl mb-2'></i>
                                    <p>No one is currently clocked in</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Late Arrivals Today -->
        @if($lateToday->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Late Arrivals Today</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock In Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minutes Late</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($lateToday as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                <i class='bx bx-user text-xl text-yellow-600'></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $attendance->user->role === 'staff' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ ucfirst($attendance->user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attendance->clock_in_time->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">
                                        {{ $attendance->clock_in_time->diffInMinutes(\Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' 09:15:00')) }}
                                        min
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.attendance.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class='bx bx-arrow-back mr-2'></i>Back to Attendance
            </a>
            <button onclick="location.reload()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class='bx bx-refresh mr-2'></i>Refresh
            </button>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function () {
            location.reload();
        }, 30000);
    </script>
@endsection