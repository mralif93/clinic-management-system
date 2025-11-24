@extends('layouts.public')

@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Patient Dashboard</h1>
            </div>

            <div class="space-y-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="text-blue-100">Manage your appointments and profile from here.</p>
                        </div>
                        <div class="text-6xl opacity-20">
                            <i class='bx bx-user-circle'></i>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- My Appointments -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">My Appointments</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAppointments }}</p>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-full">
                                <i class='bx bx-calendar text-3xl text-blue-600'></i>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Upcoming</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingAppointments }}</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full">
                                <i class='bx bx-time text-3xl text-green-600'></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $completedAppointments }}</p>
                            </div>
                            <div class="bg-purple-100 p-4 rounded-full">
                                <i class='bx bx-check-circle text-3xl text-purple-600'></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('patient.appointments.create') }}"
                                class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                                <i class='bx bx-calendar-plus text-3xl text-blue-600 mb-2'></i>
                                <span class="text-sm font-medium text-gray-700">Book Appointment</span>
                            </a>
                            <a href="{{ route('patient.appointments.index') }}"
                                class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                                <i class='bx bx-history text-3xl text-green-600 mb-2'></i>
                                <span class="text-sm font-medium text-gray-700">View History</span>
                            </a>
                            <a href="{{ route('patient.profile.show') }}"
                                class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                                <i class='bx bx-user text-3xl text-purple-600 mb-2'></i>
                                <span class="text-sm font-medium text-gray-700">My Profile</span>
                            </a>
                            <a href="{{ route('patient.records.index') }}"
                                class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
                                <i class='bx bx-file text-3xl text-orange-600 mb-2'></i>
                                <span class="text-sm font-medium text-gray-700">My Records</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
                    </div>
                    <div class="p-6">
                        @if($recentAppointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentAppointments as $appointment)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class='bx bx-calendar text-blue-600 text-xl'></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</h4>
                                                    <p class="text-sm text-gray-600">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} at {{ $appointment->appointment_time }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($appointment->status === 'completed') bg-green-100 text-green-700
                                                @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-700
                                                @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                            <a href="{{ route('patient.appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-700">
                                                <i class='bx bx-chevron-right text-2xl'></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('patient.appointments.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    View All Appointments →
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No appointments found. <a href="{{ route('patient.appointments.create') }}"
                                    class="text-blue-600 hover:underline">Book your first appointment</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection