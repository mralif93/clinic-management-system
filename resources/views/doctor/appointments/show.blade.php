@extends('layouts.doctor')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <a href="{{ route('doctor.appointments.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                        <i class='bx bx-arrow-back'></i> Back to Appointments
                    </a>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-calendar-check text-xl'></i>
                        </div>
                        Appointment Details
                    </h1>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('doctor.appointments.invoice', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur text-white font-semibold rounded-xl hover:bg-white/30 transition">
                        <i class='bx bx-receipt mr-2'></i> Invoice
                    </a>
                    <a href="{{ route('doctor.appointments.edit', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition shadow-lg">
                        <i class='bx bx-edit mr-2'></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Patient Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-blue-500'></i> Patient Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-gray-900 font-medium">{{ $appointment->patient->full_name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Patient ID</label>
                            <p class="text-gray-900">{{ $appointment->patient->patient_id ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $appointment->patient->email ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-calendar text-emerald-500'></i> Appointment Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Date</label>
                            <p class="text-gray-900 font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Time</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Service</label>
                            <p class="text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-100 text-blue-700',
                                    'confirmed' => 'bg-emerald-100 text-emerald-700',
                                    'completed' => 'bg-purple-100 text-purple-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'no_show' => 'bg-amber-100 text-amber-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-credit-card text-purple-500'></i> Payment Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if($appointment->fee)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Fee</p>
                            <p class="text-xl font-bold text-gray-900">{{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}</p>
                        </div>
                    @endif
                    @if($appointment->discount_type && $appointment->discount_value > 0)
                        <div class="bg-red-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Discount</p>
                            <p class="text-xl font-bold text-red-600">-{{ get_currency_symbol() }}{{ number_format($appointment->discount_amount, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $appointment->discount_display }}</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Final Amount</p>
                            <p class="text-xl font-bold text-emerald-600">{{ get_currency_symbol() }}{{ number_format($appointment->final_amount, 2) }}</p>
                        </div>
                    @endif
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                        @php
                            $paymentStatusColors = [
                                'unpaid' => 'bg-red-100 text-red-700',
                                'paid' => 'bg-emerald-100 text-emerald-700',
                                'partial' => 'bg-amber-100 text-amber-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg {{ $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}
                        </span>
                    </div>
                    @if($appointment->payment_method)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                            <p class="text-lg font-semibold text-gray-900">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        @if($appointment->diagnosis || $appointment->prescription || $appointment->notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-first-aid text-red-500'></i> Medical Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($appointment->diagnosis)
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                <i class='bx bx-search-alt text-gray-400'></i> Diagnosis
                            </label>
                            <div class="text-gray-900 bg-gray-50 p-4 rounded-xl prose max-w-none">{!! $appointment->diagnosis !!}</div>
                        </div>
                    @endif
                    @if($appointment->prescription)
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                <i class='bx bx-capsule text-gray-400'></i> Prescription
                            </label>
                            <div class="text-gray-900 bg-gray-50 p-4 rounded-xl prose max-w-none">{!! $appointment->prescription !!}</div>
                        </div>
                    @endif
                    @if($appointment->notes)
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                <i class='bx bx-note text-gray-400'></i> Notes
                            </label>
                            <div class="text-gray-900 bg-gray-50 p-4 rounded-xl prose max-w-none">{!! $appointment->notes !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection