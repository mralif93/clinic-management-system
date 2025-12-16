@extends('layouts.staff')

@section('title', 'Doctor Details')
@section('page-title', 'Doctor Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-cyan-500 via-teal-500 to-emerald-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.schedule.doctors') }}"
                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition">
                            <i class='bx bx-arrow-back text-white text-xl'></i>
                        </a>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">{{ strtoupper(substr($doctor->user->name ?? 'D', 0, 1)) }}</span>
                        </div>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Dr. {{ $doctor->user->name }}</h1>
                            <p class="text-cyan-100 text-sm mt-1 flex items-center gap-2">
                                <i class='bx bx-briefcase-alt'></i>
                                {{ $doctor->specialization ?? 'General Practitioner' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium border border-white/30">
                            {{ $doctor->doctor_id ?? 'N/A' }}
                        </span>
                        <a href="{{ route('staff.schedule.view-doctor', $doctor->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-cyan-600 font-semibold rounded-xl hover:bg-cyan-50 transition shadow-lg">
                            <i class='bx bx-calendar mr-2'></i> View Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $totalAppointments }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $completedAppointments }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Completed</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-time text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $upcomingAppointments }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Upcoming</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-star text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $doctor->qualification ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Qualification</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Appointments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-calendar-check text-cyan-500'></i>
                            Recent Appointments
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($doctor->appointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($doctor->appointments->take(5) as $appointment)
                                    @php
                                        $statusColors = [
                                            'scheduled' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                                            'confirmed' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700'],
                                            'in_progress' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                            'no_show' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
                                        ];
                                        $statusColor = $statusColors[$appointment->status] ?? $statusColors['scheduled'];
                                    @endphp
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                        <div class="w-14 h-14 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl flex flex-col items-center justify-center text-white">
                                            <span class="text-xs font-medium">{{ $appointment->appointment_date->format('M') }}</span>
                                            <span class="text-lg font-bold leading-none">{{ $appointment->appointment_date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate">{{ $appointment->patient->full_name ?? 'Unknown Patient' }}</h4>
                                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                                <i class='bx bx-time'></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                <span class="text-gray-300">•</span>
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class='bx bx-calendar-minus text-gray-400 text-3xl'></i>
                                </div>
                                <p class="text-gray-500">No recent appointments found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-user text-cyan-500'></i>
                            Basic Information
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-id-card text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Full Name</p>
                                <p class="text-sm font-medium text-gray-900">Dr. {{ $doctor->user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-hash text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Doctor ID</p>
                                <p class="text-sm font-medium text-gray-900">{{ $doctor->doctor_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-briefcase-alt text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Specialization</p>
                                <p class="text-sm font-medium text-gray-900">{{ $doctor->specialization ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-award text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Qualification</p>
                                <p class="text-sm font-medium text-gray-900">{{ $doctor->qualification ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-phone text-cyan-500'></i>
                            Contact Information
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-envelope text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ $doctor->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-phone text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Phone</p>
                                <p class="text-sm font-medium text-gray-900">{{ $doctor->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                @if($doctor->bio)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-info-circle text-cyan-500'></i>
                            About
                        </h3>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-gray-700">{{ $doctor->bio }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection