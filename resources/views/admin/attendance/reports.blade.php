@extends('layouts.admin')

@section('title', 'Attendance Reports')
@section('page-title', 'Attendance Reports')

@section('content')
    <div class="w-full space-y-6">
        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Date Range</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class='bx bx-search mr-2'></i>Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $summary->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class='bx bx-user text-3xl text-blue-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Days</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $summary->sum('total_days') }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class='bx bx-calendar text-3xl text-green-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Hours</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">
                            {{ number_format($summary->sum('total_hours'), 1) }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class='bx bx-time text-3xl text-purple-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Late Arrivals</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $summary->sum('late') }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class='bx bx-error-circle text-3xl text-yellow-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Attendance Summary Report</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                        {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.attendance.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class='bx bx-download mr-2'></i>Export CSV
                    </a>
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-printer mr-2'></i>Print Report
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Days</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Present</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Late</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Absent</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Hours</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Avg Hours/Day</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($summary as $userSummary)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx bx-user text-xl text-blue-600'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $userSummary['user']->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $userSummary['user']->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $userSummary['user']->role === 'staff' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($userSummary['user']->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900">
                                    {{ $userSummary['total_days'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-green-600">
                                    {{ $userSummary['present'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-yellow-600">
                                    {{ $userSummary['late'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-red-600">
                                    {{ $userSummary['absent'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-purple-600">
                                    {{ number_format($userSummary['total_hours'], 1) }}h
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $userSummary['total_days'] > 0 ? number_format($userSummary['total_hours'] / $userSummary['total_days'], 1) : 0 }}h
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <i class='bx bx-data text-4xl mb-2'></i>
                                    <p>No attendance data found for the selected date range</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($summary->count() > 0)
                        <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">TOTAL</td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">
                                    {{ $summary->sum('total_days') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-green-600">
                                    {{ $summary->sum('present') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-yellow-600">{{ $summary->sum('late') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-red-600">{{ $summary->sum('absent') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-purple-600">
                                    {{ number_format($summary->sum('total_hours'), 1) }}h
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">
                                    {{ $summary->sum('total_days') > 0 ? number_format($summary->sum('total_hours') / $summary->sum('total_days'), 1) : 0 }}h
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.attendance.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class='bx bx-arrow-back mr-2'></i>Back to Attendance
            </a>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
@endsection