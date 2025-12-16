@extends('layouts.staff')

@section('title', 'Schedule')
@section('page-title', 'Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Clinic Schedule</h1>
                        <p class="text-amber-100 text-sm mt-1">View and manage clinic appointments</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-calendar-check mr-1'></i>
                        {{ $selectedDate->format('l, M d') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar-check text-white text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $todayCount }}</p>
                        <p class="text-sm text-gray-500">Today's Appointments</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar text-white text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $weekCount }}</p>
                        <p class="text-sm text-gray-500">This Week</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class='bx bx-time text-white text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $upcomingCount }}</p>
                        <p class="text-sm text-gray-500">Upcoming</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Selector -->
        <div class="bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 rounded-2xl border border-amber-100/50 p-5">
            <form method="GET" action="{{ route('staff.schedule.index') }}">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-amber-700 flex items-center gap-1.5">
                        <i class='bx bx-calendar-event'></i> Select Date:
                    </span>

                    <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 text-sm text-gray-700">

                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ route('staff.schedule.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                            <i class='bx bx-calendar-check mr-1'></i> Today
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-amber-600 hover:to-orange-600 transition-all">
                            <i class='bx bx-search mr-1.5'></i> View
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-list-ul text-white text-lg'></i>
                        </div>
                        Appointments for {{ $selectedDate->format('l, F d, Y') }}
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-sm font-semibold rounded-full">
                        <i class='bx bx-calendar-check mr-1'></i>
                        {{ $appointments->count() }} appointments
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($appointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($appointments as $appointment)
                            @php
                                $statusConfig = [
                                    'scheduled' => ['bg' => 'bg-blue-50/50', 'border' => 'border-blue-100', 'badge_bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-calendar', 'time_bg' => 'bg-blue-500', 'time_text' => 'text-blue-600'],
                                    'confirmed' => ['bg' => 'bg-green-50/50', 'border' => 'border-green-100', 'badge_bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check', 'time_bg' => 'bg-green-500', 'time_text' => 'text-green-600'],
                                    'in_progress' => ['bg' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'badge_bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'bx-loader-alt', 'time_bg' => 'bg-amber-500', 'time_text' => 'text-amber-600'],
                                    'completed' => ['bg' => 'bg-purple-50/50', 'border' => 'border-purple-100', 'badge_bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'bx-check-double', 'time_bg' => 'bg-purple-500', 'time_text' => 'text-purple-600'],
                                    'cancelled' => ['bg' => 'bg-red-50/50', 'border' => 'border-red-100', 'badge_bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-x', 'time_bg' => 'bg-red-500', 'time_text' => 'text-red-600'],
                                    'no_show' => ['bg' => 'bg-gray-50/50', 'border' => 'border-gray-100', 'badge_bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-error', 'time_bg' => 'bg-gray-500', 'time_text' => 'text-gray-600'],
                                ];
                                $sConfig = $statusConfig[$appointment->status] ?? ['bg' => 'bg-gray-50/50', 'border' => 'border-gray-100', 'badge_bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-help-circle', 'time_bg' => 'bg-gray-500', 'time_text' => 'text-gray-600'];
                            @endphp
                            <div class="group {{ $sConfig['bg'] }} border {{ $sConfig['border'] }} rounded-xl p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center gap-4">
                                    <!-- Time Display -->
                                    <div class="flex-shrink-0 text-center">
                                        <div class="text-2xl font-bold {{ $sConfig['time_text'] }}">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i') }}</div>
                                        <div class="text-xs font-semibold {{ $sConfig['time_text'] }} opacity-70 uppercase">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('A') }}</div>
                                    </div>

                                    <!-- Divider -->
                                    <div class="w-px h-12 {{ $sConfig['time_bg'] }} opacity-30"></div>

                                    <!-- Patient Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-200">
                                            <span class="text-sm font-bold text-gray-600">{{ strtoupper(substr($appointment->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($appointment->patient->last_name ?? '', 0, 1)) }}</span>
                                        </div>
                                    </div>

                                    <!-- Main Content -->
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-gray-500">
                                            <span class="inline-flex items-center gap-1">
                                                <i class='bx bx-briefcase-alt-2 text-amber-500'></i>
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </span>
                                            <span class="text-gray-300">•</span>
                                            <span class="inline-flex items-center gap-1">
                                                <i class='bx bx-user text-blue-500'></i>
                                                @if($appointment->doctor)
                                                    Dr. {{ $appointment->doctor->full_name }}
                                                @else
                                                    <span class="text-gray-400">No doctor</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Status & Action - Right Aligned, Vertically Centered -->
                                    <div class="flex-shrink-0 flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $sConfig['badge_bg'] }} {{ $sConfig['text'] }}">
                                            <i class='bx {{ $sConfig['icon'] }}'></i>
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                        <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                                            class="w-9 h-9 flex items-center justify-center bg-gradient-to-br from-amber-500 to-orange-500 text-white rounded-full hover:from-amber-600 hover:to-orange-600 transition-all shadow-sm hover:shadow-md" title="View Details">
                                            <i class='bx bx-right-arrow-alt text-lg'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-calendar-minus text-4xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-600 font-semibold text-lg">No appointments scheduled</p>
                        <p class="text-gray-400 text-sm mt-1">Select another date to view appointments</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Week View -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-calendar-week text-amber-500'></i>
                    Week View
                    <span class="text-sm font-normal text-gray-500 ml-2">{{ $weekStart->format('M d') }} - {{ $weekEnd->format('M d, Y') }}</span>
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-7 gap-3">
                    @for($date = $weekStart->copy(); $date <= $weekEnd; $date->addDay())
                        @php
                            $dayAppointments = $weekAppointments->get($date->format('Y-m-d'), collect());
                            $isToday = $date->isToday();
                            $isSelected = $date->format('Y-m-d') == $selectedDate->format('Y-m-d');
                        @endphp
                        <a href="{{ route('staff.schedule.index', ['date' => $date->format('Y-m-d')]) }}"
                            class="block border rounded-xl p-3 transition-all duration-150 hover:shadow-md {{ $isToday ? 'bg-gradient-to-br from-amber-50 to-orange-50 border-amber-300 ring-2 ring-amber-200' : ($isSelected ? 'bg-blue-50 border-blue-300' : 'border-gray-200 hover:border-amber-200 hover:bg-amber-50/30') }}">
                            <div class="text-center mb-2">
                                <div class="text-xs font-semibold {{ $isToday ? 'text-amber-600' : 'text-gray-500' }} uppercase">{{ $date->format('D') }}</div>
                                <div class="text-xl font-bold {{ $isToday ? 'text-amber-700' : 'text-gray-900' }}">{{ $date->format('d') }}</div>
                            </div>
                            <div class="space-y-1">
                                @foreach($dayAppointments->take(2) as $appointment)
                                    <div class="text-xs bg-amber-100 text-amber-800 rounded-lg px-2 py-1 truncate font-medium"
                                        title="{{ $appointment->patient->full_name }}">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                @endforeach
                                @if($dayAppointments->count() > 2)
                                    <div class="text-xs text-amber-600 text-center font-semibold">
                                        +{{ $dayAppointments->count() - 2 }} more
                                    </div>
                                @endif
                                @if($dayAppointments->count() == 0)
                                    <div class="text-xs text-gray-400 text-center py-1">—</div>
                                @endif
                            </div>
                        </a>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection