@extends('layouts.doctor')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Appointment Information</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('doctor.appointments.invoice', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class='bx bx-receipt mr-2'></i> Invoice
                    </a>
                    <a href="{{ route('doctor.appointments.edit', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class='bx bx-edit mr-2'></i> Edit
                    </a>
                    <a href="{{ route('doctor.appointments.index') }}"
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
                        <div class="space-y-0 divide-y divide-gray-100">
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-600">Name</label>
                                <p class="text-gray-900">{{ $appointment->patient->full_name }}</p>
                            </div>
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-600">Patient ID</label>
                                <p class="text-gray-900">{{ $appointment->patient->patient_id ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900">{{ $appointment->patient->email ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-600">Phone</label>
                                <p class="text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Info -->
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
                                <label class="text-sm font-medium text-gray-600">Service</label>
                                <p class="text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-600">Status</label>
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
                            @if($appointment->fee)
                                <div class="flex items-center justify-between py-3.5">
                                    <label class="text-sm font-medium text-gray-600">Fee</label>
                                    <p class="text-gray-900">{{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}</p>
                                </div>
                            @endif
                            @if($appointment->discount_type && $appointment->discount_value > 0)
                                <div class="flex items-center justify-between py-3.5">
                                    <label class="text-sm font-medium text-gray-600">Discount</label>
                                    <p class="text-red-600">
                                        -{{ get_currency_symbol() }}{{ number_format($appointment->discount_amount, 2) }}
                                        <span class="text-xs text-gray-500">({{ $appointment->discount_display }})</span>
                                    </p>
                                </div>
                                <div class="flex items-center justify-between py-3.5 bg-gray-50 -mx-2 px-2 rounded">
                                    <label class="text-sm font-bold text-gray-700">Final Amount</label>
                                    <p class="text-gray-900 font-bold">{{ get_currency_symbol() }}{{ number_format($appointment->final_amount, 2) }}</p>
                                </div>
                            @endif
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-600">Payment Status</label>
                                @php
                                    $paymentStatusColors = [
                                        'unpaid' => 'bg-red-50 text-red-700 border border-red-200',
                                        'paid' => 'bg-green-50 text-green-700 border border-green-200',
                                        'partial' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? 'bg-gray-50 text-gray-700 border border-gray-200' }}">
                                    {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}
                                </span>
                            </div>
                            @if($appointment->payment_method)
                                <div class="flex items-center justify-between py-3.5">
                                    <label class="text-sm font-medium text-gray-600">Payment Method</label>
                                    <p class="text-gray-900">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                @if($appointment->diagnosis || $appointment->prescription || $appointment->notes)
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Medical Information</h3>
                        @if($appointment->diagnosis)
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600">Diagnosis</label>
                                <div class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg prose max-w-none">{!! $appointment->diagnosis !!}</div>
                            </div>
                        @endif
                        @if($appointment->prescription)
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600">Prescription</label>
                                <div class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg prose max-w-none">{!! $appointment->prescription !!}</div>
                            </div>
                        @endif
                        @if($appointment->notes)
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600">Notes</label>
                                <div class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg prose max-w-none">{!! $appointment->notes !!}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection