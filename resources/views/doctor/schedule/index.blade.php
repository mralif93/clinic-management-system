@extends('layouts.doctor')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-calendar text-xl'></i>
                        </div>
                        My Schedule
                    </h1>
                    <p class="text-emerald-100 mt-2">View and manage your appointment schedule</p>
                </div>
                <a href="{{ route('doctor.schedule.settings') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition">
                    <i class='bx bx-cog mr-2'></i> Schedule Settings
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $todayCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-calendar-check text-xl'></i>
                    </div>
                </div>
            </div>

            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">This Week</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $weekCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-calendar text-xl'></i>
                    </div>
                </div>
            </div>

            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $upcomingCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-time text-xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Selector -->
        <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 rounded-2xl border border-emerald-100/50 p-5">
            <form method="GET" action="{{ route('doctor.schedule.index') }}">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-emerald-700 flex items-center gap-1.5">
                        <i class='bx bx-calendar-event'></i> Select Date:
                    </span>

                    <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 text-sm text-gray-700">

                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ route('doctor.schedule.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                            <i class='bx bx-calendar-check mr-1'></i> Today
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-teal-600 transition-all">
                            <i class='bx bx-search mr-1.5'></i> View
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-list-ul text-white text-lg'></i>
                        </div>
                        Appointments for {{ $selectedDate->format('l, F d, Y') }}
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full">
                        <i class='bx bx-calendar-check mr-1'></i>
                        {{ $appointments->count() }} {{ Str::plural('appointment', $appointments->count()) }}
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
                                    'confirmed' => ['bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-100', 'badge_bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'bx-check', 'time_bg' => 'bg-emerald-500', 'time_text' => 'text-emerald-600'],
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
                                    <div class="flex-shrink-0 text-center w-16">
                                        <div class="text-2xl font-bold {{ $sConfig['time_text'] }}">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i') }}</div>
                                        <div class="text-xs font-semibold {{ $sConfig['time_text'] }} opacity-70 uppercase">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('A') }}</div>
                                    </div>

                                    <!-- Divider -->
                                    <div class="w-px h-12 {{ $sConfig['time_bg'] }} opacity-30"></div>

                                    <!-- Patient Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-200">
                                            <span class="text-sm font-bold text-gray-600">{{ strtoupper(substr($appointment->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($appointment->patient->last_name ?? '', 0, 1)) }}</span>
                                        </div>
                                    </div>

                                    <!-- Main Content -->
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-gray-500">
                                            <span class="inline-flex items-center gap-1">
                                                <i class='bx bx-briefcase-alt-2 text-emerald-500'></i>
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </span>
                                            <span class="text-gray-300">•</span>
                                            <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md font-medium">
                                                <i class='bx bx-id-card'></i>
                                                {{ $appointment->patient->patient_id ?? 'N/A' }}
                                            </span>
                                            @if($appointment->fee)
                                                <span class="text-gray-300">•</span>
                                                <span class="inline-flex items-center gap-1 text-xs">
                                                    <i class='bx bx-money text-green-500'></i>
                                                    <span class="font-medium text-gray-600">RM{{ number_format($appointment->fee, 2) }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status & Action - Right Aligned, Vertically Centered -->
                                    <div class="flex-shrink-0 flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $sConfig['badge_bg'] }} {{ $sConfig['text'] }}">
                                            <i class='bx {{ $sConfig['icon'] }}'></i>
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                            class="w-9 h-9 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-full hover:from-emerald-600 hover:to-teal-600 transition-all shadow-sm hover:shadow-md group-hover:scale-105" title="View Details">
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
                            <i class='bx bx-calendar-x text-4xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-600 font-semibold text-lg">No appointments scheduled</p>
                        <p class="text-gray-400 text-sm mt-1">Select another date to view your appointments</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Week View -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar-week text-white text-lg'></i>
                    </div>
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
                        <a href="{{ route('doctor.schedule.index', ['date' => $date->format('Y-m-d')]) }}"
                            class="block border rounded-xl p-3 transition-all duration-150 hover:shadow-md {{ $isToday ? 'bg-gradient-to-br from-emerald-50 to-teal-50 border-emerald-300 ring-2 ring-emerald-200' : ($isSelected ? 'bg-blue-50 border-blue-300' : 'border-gray-200 hover:border-emerald-200 hover:bg-emerald-50/30') }}">
                            <div class="text-center mb-2">
                                <div class="text-xs font-semibold {{ $isToday ? 'text-emerald-600' : 'text-gray-500' }} uppercase">{{ $date->format('D') }}</div>
                                <div class="text-xl font-bold {{ $isToday ? 'text-emerald-700' : 'text-gray-900' }}">{{ $date->format('d') }}</div>
                            </div>
                            <div class="space-y-1">
                                @foreach($dayAppointments->take(2) as $appointment)
                                    <div class="text-xs bg-emerald-100 text-emerald-800 rounded-lg px-2 py-1 truncate font-medium"
                                        title="{{ $appointment->patient->full_name }}">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                @endforeach
                                @if($dayAppointments->count() > 2)
                                    <div class="text-xs text-emerald-600 text-center font-semibold">
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