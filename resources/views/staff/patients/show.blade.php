@extends('layouts.staff')

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')

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
                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-blue-600 font-bold text-2xl">{{ strtoupper(substr($patient->first_name, 0, 1)) }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $patient->full_name }}</h3>
                    <p class="text-yellow-600 font-semibold">{{ $patient->patient_id ?? 'N/A' }}</p>
                </div>
            </div>
            <a href="{{ route('staff.patients.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Back to List
            </a>
        </div>

        <!-- Patient Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 mb-4">Basic Information</h4>
                    <div>
                        <label class="text-sm font-medium text-gray-500">First Name</label>
                        <p class="mt-1 text-gray-900">{{ $patient->first_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Last Name</label>
                        <p class="mt-1 text-gray-900">{{ $patient->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="mt-1 text-gray-900">
                            {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') . ' (' . $patient->date_of_birth->age . ' years old)' : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Gender</label>
                        <p class="mt-1 text-gray-900">{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 mb-4">Contact Information</h4>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-gray-900">{{ $patient->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone</label>
                        <p class="mt-1 text-gray-900">{{ $patient->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="mt-1 text-gray-900">{{ $patient->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            @if($patient->medical_history || $patient->allergies)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($patient->medical_history)
                <div>
                    <label class="text-sm font-medium text-gray-500">Medical History</label>
                    <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $patient->medical_history }}</p>
                </div>
                @endif
                @if($patient->allergies)
                <div>
                    <label class="text-sm font-medium text-gray-500">Allergies</label>
                    <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $patient->allergies }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Emergency Contact -->
            @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Emergency Contact Name</label>
                    <p class="mt-1 text-gray-900">{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Emergency Contact Phone</label>
                    <p class="mt-1 text-gray-900">{{ $patient->emergency_contact_phone ?? 'N/A' }}</p>
                </div>
            </div>
            @endif

            <!-- Appointments History -->
            <div class="mt-8">
                <h4 class="font-semibold text-gray-900 mb-4">Appointment History</h4>
                @if($patient->appointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($patient->appointments as $appointment)
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
                                                <h5 class="font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</h5>
                                                @if($appointment->doctor)
                                                    <p class="text-sm text-gray-600 mt-1">Dr. {{ $appointment->doctor->full_name }}</p>
                                                @endif
                                                @if($appointment->diagnosis)
                                                    <p class="text-sm text-gray-600 mt-1">Diagnosis: {{ Str::limit($appointment->diagnosis, 100) }}</p>
                                                @endif
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
                                        <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                            <i class='bx bx-show text-xl'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No appointment history found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

