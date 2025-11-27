@extends('layouts.staff')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Appointment Information</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('staff.appointments.edit', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class='bx bx-edit mr-2'></i> Edit
                    </a>
                    <a href="{{ route('staff.appointments.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Name</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->patient->full_name }}</p>
                            </div>
                            @if($appointment->patient->email)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email</label>
                                    <p class="mt-1 text-gray-900">{{ $appointment->patient->email }}</p>
                                </div>
                            @endif
                            @if($appointment->patient->phone)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Phone</label>
                                    <p class="mt-1 text-gray-900">{{ $appointment->patient->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date</label>
                                <p class="mt-1 text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Time</label>
                                <p class="mt-1 text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p class="mt-1">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-blue-100 text-blue-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-purple-100 text-purple-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'no_show' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </p>
                            </div>
                            @if($appointment->fee)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Fee</label>
                                    <p class="mt-1 text-gray-900 font-semibold">
                                        {{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Doctor Info -->
                    @if($appointment->doctor)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Doctor Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Name</label>
                                    <p class="mt-1 text-gray-900">{{ $appointment->doctor->full_name }}</p>
                                </div>
                                @if($appointment->doctor->specialization)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Specialization</label>
                                        <p class="mt-1 text-gray-900">{{ $appointment->doctor->specialization }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Service Info -->
                    @if($appointment->service)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Service</label>
                                    <p class="mt-1 text-gray-900">{{ $appointment->service->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Type</label>
                                    <p class="mt-1 text-gray-900">{{ ucfirst($appointment->service->type) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Notes -->
                @if($appointment->notes)
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->notes }}</p>
                    </div>
                @endif

                <!-- Diagnosis -->
                @if($appointment->diagnosis)
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Diagnosis</h3>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->diagnosis }}</p>
                    </div>
                @endif

                <!-- Prescription -->
                @if($appointment->prescription)
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Prescription</h3>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->prescription }}</p>
                    </div>
                @endif

                <!-- Quick Status Update -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Quick Status Update</h4>
                    <form action="{{ route('staff.appointments.update-status', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center space-x-4">
                            <select name="status"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                required>
                                <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>
                                    Scheduled</option>
                                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed</option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="no_show" {{ $appointment->status == 'no_show' ? 'selected' : '' }}>No Show
                                </option>
                            </select>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
                                <i class='bx bx-save mr-2'></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection