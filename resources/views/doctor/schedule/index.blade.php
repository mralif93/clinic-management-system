@extends('layouts.doctor')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Schedule</h1>
            <p class="text-gray-600 mt-1">View and manage your appointment schedule</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayCount }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class='bx bx-calendar-check text-3xl text-green-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Week</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $weekCount }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl text-blue-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingCount }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class='bx bx-time text-3xl text-purple-600'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('doctor.schedule.index') }}" class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700">Select Date:</label>
            <input type="date" 
                   name="date" 
                   value="{{ $selectedDate->format('Y-m-d') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                <i class='bx bx-search mr-1'></i> View
            </button>
            <a href="{{ route('doctor.schedule.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                Today
            </a>
        </form>
    </div>

    <!-- Today's Appointments -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Appointments for {{ $selectedDate->format('l, F d, Y') }}
            </h3>
        </div>
        <div class="p-6">
            @if($appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($appointments as $appointment)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500 uppercase">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('A') }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $appointment->service->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">ID: {{ $appointment->patient->patient_id ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-blue-100 text-blue-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-purple-100 text-purple-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'no_show' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="text-green-600 hover:text-green-800">
                                        <i class='bx bx-show text-xl'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-calendar-x text-6xl text-gray-300 mb-4'></i>
                    <p class="text-gray-500">No appointments scheduled for this date.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Week View -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Week View ({{ $weekStart->format('M d') }} - {{ $weekEnd->format('M d, Y') }})
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @for($date = $weekStart->copy(); $date <= $weekEnd; $date->addDay())
                    @php
                        $dayAppointments = $weekAppointments->get($date->format('Y-m-d'), collect());
                        $isToday = $date->isToday();
                        $isSelected = $date->format('Y-m-d') == $selectedDate->format('Y-m-d');
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-3 {{ $isToday ? 'bg-green-50 border-green-300' : ($isSelected ? 'bg-blue-50 border-blue-300' : '') }}">
                        <div class="text-center mb-2">
                            <div class="text-xs font-medium text-gray-500 uppercase">{{ $date->format('D') }}</div>
                            <div class="text-lg font-bold text-gray-900">{{ $date->format('d') }}</div>
                        </div>
                        <div class="space-y-1">
                            @foreach($dayAppointments->take(3) as $appointment)
                                <div class="text-xs bg-green-100 text-green-800 rounded px-1 py-0.5 truncate" title="{{ $appointment->patient->full_name }}">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} - {{ $appointment->patient->first_name }}
                                </div>
                            @endforeach
                            @if($dayAppointments->count() > 3)
                                <div class="text-xs text-gray-500 text-center">
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

