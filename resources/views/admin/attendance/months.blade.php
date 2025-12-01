@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Attendance Management</h1>
                <p class="text-gray-600 mt-1">Select a month to view attendance records</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.attendance.trash') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-trash text-xl'></i>
                    Trash
                </a>
                <a href="{{ route('admin.attendance.live') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-broadcast text-xl'></i>
                    Live Dashboard
                </a>
                <a href="{{ route('admin.attendance.reports') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-bar-chart-alt-2 text-xl'></i>
                    Reports
                </a>
            </div>
        </div>

        <!-- Months Grid -->
        @if($months->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($months as $month)
                    @php
                        $date = \Carbon\Carbon::createFromDate($month->year, $month->month, 1);
                        $monthName = $date->format('F Y');
                        $isCurrentMonth = $month->year == now()->year && $month->month == now()->month;
                    @endphp
                    
                    <a href="{{ route('admin.attendance.by-month', ['year' => $month->year, 'month' => $month->month]) }}" 
                       class="block group">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-transparent hover:border-blue-200 {{ $isCurrentMonth ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-green-100 p-3 rounded-full group-hover:bg-green-600 transition-colors duration-300">
                                        <i class='bx bx-calendar text-2xl text-green-600 group-hover:text-white transition-colors duration-300'></i>
                                    </div>
                                    @if($isCurrentMonth)
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Current</span>
                                    @endif
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-green-600 transition-colors">{{ $monthName }}</h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $month->total_records }} Records</p>
                                
                                <div class="flex items-center justify-between text-sm border-t border-gray-100 pt-4">
                                    <div class="flex gap-2 w-full justify-between">
                                        <span class="flex items-center gap-1 text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full" title="Present">
                                            <i class='bx bx-check'></i> {{ $month->present_count }}
                                        </span>
                                        <span class="flex items-center gap-1 text-xs text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full" title="Late">
                                            <i class='bx bx-time'></i> {{ $month->late_count }}
                                        </span>
                                        <span class="flex items-center gap-1 text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded-full" title="Absent">
                                            <i class='bx bx-x'></i> {{ $month->absent_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md">
                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-calendar-x text-4xl text-gray-400'></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900">No Attendance Records</h3>
                <p class="text-gray-500 mt-2 mb-6">No attendance records found.</p>
            </div>
        @endif
    </div>
@endsection
