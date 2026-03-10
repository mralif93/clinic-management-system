@extends('layouts.staff')

@section('title', 'View Doctor Schedule')
@section('page-title', 'View Doctor Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-cyan-500 via-cyan-600 to-teal-600 rounded-2xl  p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <span class="text-2xl font-bold">{{ strtoupper(substr($doctor->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Dr. {{ $doctor->user->name }}</h1>
                        <p class="text-cyan-100 text-sm mt-1">{{ $doctor->specialization }} • {{ $doctor->qualification }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='hgi-stroke hgi-identity-card mr-1'></i>
                        {{ $doctor->doctor_id ?? 'N/A' }}
                    </div>
                    <a href="{{ route('staff.schedule.doctors') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition">
                        <i class='hgi-stroke hgi-arrow-left-01 mr-2'></i> Back to Doctors
                    </a>
                </div>
            </div>
        </div>

        <!-- Schedule Summary Stats -->
        @php
            $workingDays = collect($scheduleData)->where('is_active', true)->count();
            $offDays = 7 - $workingDays;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-calendar-03 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $workingDays }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Working Days</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-calendar-03 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $offDays }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Off Days</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-mail-01 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $doctor->user->email ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-call text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $doctor->phone ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Phone</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Section Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class='hgi-stroke hgi-calendar-03 text-cyan-500'></i>
                    Weekly Schedule
                </h3>
            </div>
            <div class="p-6">
                <!-- Schedule Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($scheduleData as $dayIndex => $schedule)
                        @php
                            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            $dayName = $dayNames[$dayIndex];
                            $isActive = $schedule->is_active;
                            $isToday = $dayIndex == now()->dayOfWeek;
                        @endphp

                        <div class="bg-white rounded-xl border-2 overflow-hidden hover:shadow-lg transition-all duration-200 {{ $isActive ? 'border-cyan-200' : 'border-gray-100' }} {{ $isToday ? 'ring-2 ring-cyan-500 ring-offset-2' : '' }}">
                            <!-- Card Header -->
                            <div class="{{ $isActive ? 'bg-gradient-to-r from-cyan-500 to-teal-500' : 'bg-gradient-to-r from-gray-400 to-gray-500' }} px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-bold text-white">{{ $dayName }}</h3>
                                        @if($isToday)
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-white text-cyan-600 uppercase">Today</span>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-white/20 text-white' }}">
                                        {{ $isActive ? 'Working' : 'Off' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4">
                                @if($isActive)
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3 p-2.5 bg-green-50 rounded-lg">
                                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class='hgi-stroke hgi-clock-02 text-white'></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Working Hours</p>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 p-2.5 bg-amber-50 rounded-lg">
                                            <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class='hgi-stroke hgi-coffee-01 text-white'></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Break Time</p>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ \Carbon\Carbon::parse($schedule->break_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->break_end)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 p-2.5 bg-blue-50 rounded-lg">
                                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class='hgi-stroke hgi-clock-02 text-white'></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Slot Duration</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ $schedule->slot_duration }} minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <i class='hgi-stroke hgi-calendar-03 text-2xl'></i>
                                        </div>
                                        <span class="text-sm font-medium">Day Off</span>
                                        <span class="text-xs text-gray-400 mt-1">No schedule</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection