@extends('layouts.admin')

@section('title', 'View Doctor Schedule')
@section('page-title', 'View Doctor Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.schedules.index') }}"
                            class="rounded-xl bg-white/20 p-2 backdrop-blur-sm hover:bg-white/30 transition-colors text-white">
                            <i class='bx bx-arrow-back text-2xl'></i>
                        </a>
                        <h1 class="text-3xl font-bold text-white">View Schedule</h1>
                    </div>
                    <p class="text-indigo-100 max-w-xl">Weekly working schedule for <span
                            class="font-semibold text-white">{{ $doctor->user->name }}</span></p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                        class="flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 font-bold rounded-full hover:bg-indigo-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-edit text-xl'></i>
                        Edit Schedule
                    </a>
                </div>
            </div>

            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
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
                    class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 {{ $isActive ? 'ring-1 ring-indigo-500/20' : 'opacity-75' }}">
                    <!-- Card Header -->
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-calendar-event text-indigo-500'></i>
                            {{ $dayName }}
                        </h3>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $isActive ? 'Working' : 'Off Day' }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5">
                        @if($isActive)
                            <div class="space-y-4">
                                <!-- Working Hours -->
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                        <i class='bx bx-time-five'></i> Working Hours
                                    </label>
                                    <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                                        <p class="text-indigo-900 font-bold text-lg text-center">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                            <span class="text-indigo-400 mx-1">-</span>
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Break Time -->
                                    <div class="space-y-1">
                                        <label
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                            <i class='bx bx-coffee'></i> Break
                                        </label>
                                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-100 text-center">
                                            <p class="text-gray-700 font-semibold text-sm">
                                                {{ \Carbon\Carbon::parse($schedule->break_start)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($schedule->break_end)->format('h:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Slot Duration -->
                                    <div class="space-y-1">
                                        <label
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                            <i class='bx bx-timer'></i> Slot
                                        </label>
                                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-100 text-center">
                                            <p class="text-gray-700 font-semibold text-sm">
                                                {{ $schedule->slot_duration }} mins
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                    <i class='bx bx-calendar-x text-3xl text-gray-300'></i>
                                </div>
                                <span class="text-sm font-medium">No schedule configured</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection