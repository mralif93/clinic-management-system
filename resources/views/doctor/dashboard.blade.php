@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Welcome, Dr. {{ Auth::user()->name }}!</h3>
                    <p class="text-green-100">Manage your appointments and patients from here.</p>
                    @if(Auth::user()->doctor)
                        <p class="text-green-100 mt-2">ID: {{ Auth::user()->doctor->doctor_id }}</p>
                    @endif
                </div>
                <div class="text-6xl opacity-20">
                    <i class='bx bx-plus-medical'></i>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Appointments -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAppointments }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class='bx bx-calendar text-3xl text-blue-600'></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayAppointments }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class='bx bx-time text-3xl text-green-600'></i>
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
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class='bx bx-calendar-check text-3xl text-purple-600'></i>
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
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class='bx bx-check-circle text-3xl text-yellow-600'></i>
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
                    <a href="{{ route('doctor.schedule.index') }}"
                        class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                        <i class='bx bx-calendar text-3xl text-green-600 mb-2'></i>
                        <span class="text-sm font-medium text-gray-700">View Schedule</span>
                    </a>
                    <a href="{{ route('doctor.patients.index') }}"
                        class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                        <i class='bx bx-user text-3xl text-blue-600 mb-2'></i>
                        <span class="text-sm font-medium text-gray-700">My Patients</span>
                    </a>
                    <a href="{{ route('doctor.profile.show') }}"
                        class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                        <i class='bx bx-user-circle text-3xl text-purple-600 mb-2'></i>
                        <span class="text-sm font-medium text-gray-700">My Profile</span>
                    </a>
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
                        <i class='bx bx-calendar-check text-3xl text-orange-600 mb-2'></i>
                        <span class="text-sm font-medium text-gray-700">Appointments</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Today's Appointments</h3>
            </div>
            <div class="p-6">
                @if($todayAppointmentsList->count() > 0)
                    <div class="space-y-4">
                        @foreach($todayAppointmentsList as $appointment)
                            <div
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class='bx bx-user text-green-600 text-xl'></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->patient->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $appointment->service->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class='bx bx-time mr-1'></i>{{ $appointment->appointment_time }}
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
                                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                        class="text-green-600 hover:text-green-700">
                                        <i class='bx bx-chevron-right text-2xl'></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No appointments scheduled for today.</p>
                @endif
            </div>
        </div>
    </div>
@endsection