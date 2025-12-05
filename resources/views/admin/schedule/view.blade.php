@extends('layouts.admin')

@section('title', 'View Doctor Schedule')
@section('page-title', 'View Doctor Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-time text-primary-600 text-2xl'></i>
                    Schedule: {{ $doctor->user->name }}
                </h1>
                <p class="text-sm text-gray-600">Weekly working schedule for Dr. {{ $doctor->user->name }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-edit text-base'></i> Edit Schedule
                </a>
                <a href="{{ route('admin.schedules.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-arrow-back text-base'></i> Back to List
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
                    class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 hover:shadow transition {{ $isActive ? '' : 'opacity-75' }}">
                    <!-- Card Header -->
                    <div
                        class="{{ $isActive ? 'bg-primary-600' : 'bg-gray-500' }} px-4 py-3 flex items-center justify-between text-white">
                        <h3 class="text-lg font-bold">{{ $dayName }}</h3>
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full {{ $isActive ? 'bg-white/90 text-primary-700' : 'bg-white/70 text-gray-700' }}">
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