@extends('layouts.admin')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Appointment Information</h3>
            <div class="flex space-x-2">
                @if(!$appointment->trashed())
                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-edit mr-2 text-base'></i> Edit
                </a>
                @else
                <form action="{{ route('admin.appointments.restore', $appointment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-refresh mr-2 text-base'></i> Restore
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.appointments.index') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
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
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Name</label>
                            <p class="text-gray-900">{{ $appointment->patient->full_name }}</p>
                        </div>
                        @if($appointment->patient->email)
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $appointment->patient->email }}</p>
                        </div>
                        @endif
                        @if($appointment->patient->phone)
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-900">{{ $appointment->patient->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Appointment Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h3>
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Date</label>
                            <p class="text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Time</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Status</label>
                            <div>
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                        'confirmed' => 'bg-green-50 text-green-700 border border-green-200',
                                        'completed' => 'bg-gray-50 text-gray-700 border border-gray-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
                                        'no_show' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-50 text-gray-700 border border-gray-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </div>
                        </div>
                        @if($appointment->fee)
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Fee</label>
                            <p class="text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($appointment->fee, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Doctor Info -->
                @if($appointment->doctor)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Doctor Information</h3>
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Name</label>
                            <p class="text-gray-900">{{ $appointment->doctor->full_name }}</p>
                        </div>
                        @if($appointment->doctor->specialization)
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Specialization</label>
                            <p class="text-gray-900">{{ $appointment->doctor->specialization }}</p>
                        </div>
                        @endif
                        @if($appointment->doctor->email)
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $appointment->doctor->email }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Service Info -->
                @if($appointment->service)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Information</h3>
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Service</label>
                            <p class="text-gray-900">{{ $appointment->service->name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Type</label>
                            <p class="text-gray-900">{{ ucfirst($appointment->service->type) }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-600">Price</label>
                            <p class="text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($appointment->service->price, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Notes -->
            @if($appointment->notes)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-gray-900">{{ $appointment->notes }}</p>
            </div>
            @endif

            <!-- Diagnosis -->
            @if($appointment->diagnosis)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Diagnosis</h3>
                <p class="text-gray-900">{{ $appointment->diagnosis }}</p>
            </div>
            @endif

            <!-- Prescription -->
            @if($appointment->prescription)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Prescription</h3>
                <p class="text-gray-900">{{ $appointment->prescription }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-8 border-t pt-6 space-y-0 divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Created At</label>
                    <p class="text-gray-900">{{ $appointment->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Last Updated</label>
                    <p class="text-gray-900">{{ $appointment->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($appointment->trashed())
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Deleted At</label>
                    <p class="text-gray-900">{{ $appointment->deleted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

