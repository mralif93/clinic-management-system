@extends('layouts.doctor')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Appointment Information</h3>
                <a href="{{ route('doctor.appointments.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>

            <!-- Appointment Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Patient Information</h4>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Patient Name</label>
                            <p class="mt-1 text-gray-900">{{ $appointment->patient->full_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Patient ID</label>
                            <p class="mt-1 text-gray-900">{{ $appointment->patient->patient_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $appointment->patient->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="mt-1 text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Appointment Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Appointment Details</h4>
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
                            <label class="text-sm font-medium text-gray-500">Service</label>
                            <p class="mt-1 text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>
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
                                    $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusColor }}">
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

                <!-- Update Status Form -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Update Appointment</h4>
                    <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
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
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis</label>
                                <textarea name="diagnosis" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Enter diagnosis...">{{ $appointment->diagnosis }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                                <textarea name="prescription" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Enter prescription...">{{ $appointment->prescription }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Enter notes...">{{ $appointment->notes }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                <i class='bx bx-save mr-2'></i> Update Appointment
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Medical Information -->
                @if($appointment->diagnosis || $appointment->prescription || $appointment->notes)
                    <div class="mt-8 space-y-4">
                        @if($appointment->diagnosis)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Diagnosis</label>
                                <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->diagnosis }}</p>
                            </div>
                        @endif
                        @if($appointment->prescription)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Prescription</label>
                                <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->prescription }}</p>
                            </div>
                        @endif
                        @if($appointment->notes)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Notes</label>
                                <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection