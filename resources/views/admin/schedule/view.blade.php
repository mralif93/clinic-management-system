@extends('layouts.admin')

@section('title', 'View Doctor Schedule')
@section('page-title', 'View Doctor Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Schedule: {{ $doctor->user->name }}</h1>
                <p class="text-gray-600 mt-1">Weekly working schedule for Dr. {{ $doctor->user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg">
                    <i class='bx bx-edit mr-2'></i> Edit Schedule
                </a>
                <a href="{{ route('admin.schedules.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition">
                    <i class='bx bx-arrow-back mr-2'></i> Back to List
                </a>
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($scheduleData as $dayIndex => $schedule)
                @php
                    $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $dayName = $dayNames[$dayIndex];
                    $isActive = $schedule->is_active;
                @endphp

                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition {{ $isActive ? '' : 'opacity-75' }}">
                    <!-- Card Header -->
                    <div
                        class="{{ $isActive ? 'bg-gradient-to-r from-blue-600 to-blue-700' : 'bg-gray-500' }} px-4 py-3 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">{{ $dayName }}</h3>
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                            {{ $isActive ? 'Working' : 'Off Day' }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        @if($isActive)
                            <div class="space-y-3">
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                    <span class="text-sm text-gray-500">Working Hours</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                    <span class="text-sm text-gray-500">Break Time</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($schedule->break_start)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($schedule->break_end)->format('h:i A') }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Slot Duration</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $schedule->slot_duration }} mins</span>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                <i class='bx bx-calendar-x text-4xl mb-2'></i>
                                <span class="text-sm">No schedule configured</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection