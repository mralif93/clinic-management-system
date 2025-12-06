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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <form method="GET" action="{{ route('doctor.schedule.index') }}" class="flex flex-wrap items-center gap-4">
                <label class="text-sm font-semibold text-gray-700">Select Date:</label>
                <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                    class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <i class='bx bx-search mr-2'></i> View
                </button>
                <a href="{{ route('doctor.schedule.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                    Today
                </a>
            </form>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-list-ul text-emerald-500'></i>
                    Appointments for {{ $selectedDate->format('l, F d, Y') }}
                </h3>
            </div>
            <div class="p-6">
                @if($appointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($appointments as $appointment)
                            <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50/50 hover:shadow-sm transition-all duration-200">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex flex-col items-center justify-center text-white flex-shrink-0">
                                            <span class="text-lg font-bold leading-none">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i') }}</span>
                                            <span class="text-xs font-medium uppercase">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('A') }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $appointment->service->name ?? 'N/A' }}</p>
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-medium">
                                                ID: {{ $appointment->patient->patient_id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-700',
                                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                                'completed' => 'bg-purple-100 text-purple-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'no_show' => 'bg-amber-100 text-amber-700',
                                            ];
                                            $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition"
                                            title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <i class='bx bx-calendar-x text-3xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-500 font-medium">No appointments scheduled for this date</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Week View -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-calendar-week text-blue-500'></i>
                    Week View ({{ $weekStart->format('M d') }} - {{ $weekEnd->format('M d, Y') }})
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                    @for($date = $weekStart->copy(); $date <= $weekEnd; $date->addDay())
                        @php
                            $dayAppointments = $weekAppointments->get($date->format('Y-m-d'), collect());
                            $isToday = $date->isToday();
                            $isSelected = $date->format('Y-m-d') == $selectedDate->format('Y-m-d');
                        @endphp
                        <div class="border rounded-xl p-3 transition-all duration-200 hover:shadow-sm {{ $isToday ? 'bg-emerald-50 border-emerald-300 ring-2 ring-emerald-200' : ($isSelected ? 'bg-blue-50 border-blue-300' : 'border-gray-100 hover:border-gray-200') }}">
                            <div class="text-center mb-3">
                                <div class="text-xs font-semibold uppercase {{ $isToday ? 'text-emerald-600' : 'text-gray-500' }}">{{ $date->format('D') }}</div>
                                <div class="text-xl font-bold {{ $isToday ? 'text-emerald-700' : 'text-gray-900' }}">{{ $date->format('d') }}</div>
                            </div>
                            <div class="space-y-1.5">
                                @foreach($dayAppointments->take(3) as $appointment)
                                    <div class="text-xs bg-emerald-100 text-emerald-700 rounded-lg px-2 py-1 truncate font-medium"
                                        title="{{ $appointment->patient->full_name }}">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} -
                                        {{ $appointment->patient->first_name }}
                                    </div>
                                @endforeach
                                @if($dayAppointments->count() > 3)
                                    <div class="text-xs text-gray-500 text-center font-medium">
                                        +{{ $dayAppointments->count() - 3 }} more
                                    </div>
                                @endif
                            </div>
                            @if($dayAppointments->count() == 0)
                                <div class="text-xs text-gray-400 text-center mt-2">No appointments</div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection