@extends('layouts.staff')

@section('title', 'Doctor Details')
@section('page-title', 'Doctor Details')

@section('content')
    <div class="w-full">
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $completedAppointments }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class='bx bx-check-circle text-3xl text-green-600'></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingAppointments }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class='bx bx-time text-3xl text-purple-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 rounded-full bg-yellow-100 flex items-center justify-center">
                        <span
                            class="text-yellow-600 font-bold text-2xl">{{ strtoupper(substr($doctor->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $doctor->user->name }}</h3>
                        <p class="text-yellow-600 font-semibold">{{ $doctor->specialization }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('staff.schedule.view-doctor', $doctor->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition shadow-sm">
                        <i class='bx bx-calendar mr-2'></i> View Schedule
                    </a>
                    <a href="{{ route('staff.schedule.doctors') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Doctor Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Basic Information</h4>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Doctor ID</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->doctor_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Qualification</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->qualification ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Specialization</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->specialization ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Contact Information</h4>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Bio</label>
                            <p class="mt-1 text-gray-900">{{ $doctor->bio ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="mt-8">
                    <h4 class="font-semibold text-gray-900 mb-4">Recent Appointments</h4>
                    @if($doctor->appointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($doctor->appointments->take(5) as $appointment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div>
                                                    <div class="text-lg font-bold text-yellow-600">
                                                        {{ $appointment->appointment_date->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-900">
                                                        {{ $appointment->patient->full_name ?? 'Unknown Patient' }}</h5>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $appointment->service->name ?? 'N/A' }}
                                                    </p>
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
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No recent appointments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection