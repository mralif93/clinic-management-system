@extends('layouts.staff')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@php
    $statusColors = [
        'scheduled' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => 'hgi-calendar-03'],
        'confirmed' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'icon' => 'hgi-checkmark-circle-01'],
        'in_progress' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'hgi-loading-01'],
        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'hgi-tick-double-01'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'hgi-cancel-01'],
        'no_show' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => 'hgi-user-block-01'],
    ];
    $statusLabels = [
        'scheduled' => 'Scheduled',
        'confirmed' => 'Checked In',
        'in_progress' => 'In Consultation',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
    ];
    $currentStatus = $statusColors[$appointment->status] ?? $statusColors['scheduled'];
    $paymentStatusColors = [
        'unpaid' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
        'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'partial' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    ];
@endphp

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 rounded-2xl shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
 
            <div class="relative p-6 md:p-8">
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.appointments.index') }}"
                            class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                            <i class='hgi-stroke hgi-arrow-left-01 text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Appointment Details</h1>
                            <p class="text-amber-100 text-sm mt-1">{{ $appointment->appointment_date->format('l, F d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('staff.appointments.invoice', $appointment->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                            <i class='hgi-stroke hgi-invoice-01 mr-2'></i> Invoice
                        </a>
                        <a href="{{ route('staff.appointments.edit', $appointment->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition shadow-lg">
                            <i class='hgi-stroke hgi-pencil-edit-01 mr-2'></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $currentStatus['bg'] }} rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke {{ $currentStatus['icon'] }} {{ $currentStatus['text'] }} text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold {{ $currentStatus['text'] }}">{{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                @php $pStatus = $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? $paymentStatusColors['unpaid']; @endphp
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $pStatus['bg'] }} rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-wallet-01 {{ $pStatus['text'] }} text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold {{ $pStatus['text'] }}">{{ ucfirst($appointment->payment_status ?? 'Unpaid') }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Payment</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-money-bag-01 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ get_currency_symbol() }}{{ number_format($appointment->final_amount ?? $appointment->fee ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Amount</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-clock-02 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Time</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='hgi-stroke hgi-user text-amber-500'></i>
                            Patient Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-xl">{{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $appointment->patient->patient_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($appointment->patient->email)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <i class='hgi-stroke hgi-mail-01 text-gray-400'></i>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->email }}</p>
                                </div>
                            </div>
                            @endif
                            @if($appointment->patient->phone)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <i class='hgi-stroke hgi-call text-gray-400'></i>
                                <div>
                                    <p class="text-xs text-gray-500">Phone</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->phone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='hgi-stroke hgi-calendar-03 text-amber-500'></i>
                            Appointment Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $appointment->appointment_date->format('F d, Y') }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Time</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                            </div>
                        </div>

                        @if($appointment->fee)
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">Service Fee</span>
                                <span class="text-sm font-medium">{{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}</span>
                            </div>
                            @if($appointment->discount_type && $appointment->discount_value > 0)
                            <div class="flex justify-between items-center py-2 text-red-600">
                                <span class="text-sm">Discount ({{ $appointment->discount_display }})</span>
                                <span class="text-sm font-medium">-{{ get_currency_symbol() }}{{ number_format($appointment->discount_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2 pt-2">
                                <span class="text-sm font-bold text-gray-900">Total Amount</span>
                                <span class="text-lg font-bold text-amber-600">{{ get_currency_symbol() }}{{ number_format($appointment->final_amount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($appointment->payment_method)
                        <div class="p-3 bg-green-50 rounded-lg">
                            <p class="text-xs text-green-600 uppercase tracking-wide">Payment Method</p>
                            <p class="text-sm font-semibold text-green-700 mt-1">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Notes, Diagnosis, Prescription -->
                @if($appointment->notes || $appointment->diagnosis || $appointment->prescription)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='hgi-stroke hgi-note-01 text-amber-500'></i>
                            Clinical Notes
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        @if($appointment->notes)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class='hgi-stroke hgi-comment-01 text-gray-400'></i> Notes
                            </h4>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700 rich-content">{!! $appointment->notes !!}</div>
                        </div>
                        @endif
                        @if($appointment->diagnosis)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class='hgi-stroke hgi-plus-sign text-gray-400'></i> Diagnosis
                            </h4>
                            <div class="bg-blue-50 p-4 rounded-lg text-sm text-gray-700 rich-content">{!! $appointment->diagnosis !!}</div>
                        </div>
                        @endif
                        @if($appointment->prescription)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class='hgi-stroke hgi-medicine-01 text-gray-400'></i> Prescription
                            </h4>
                            <div class="bg-green-50 p-4 rounded-lg text-sm text-gray-700 rich-content">{!! $appointment->prescription !!}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Doctor Information -->
                @if($appointment->doctor)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='hgi-stroke hgi-user-circle text-amber-500'></i>
                            Doctor
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold">{{ strtoupper(substr($appointment->doctor->user->name ?? 'D', 0, 1)) }}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Dr. {{ $appointment->doctor->full_name }}</h4>
                                @if($appointment->doctor->specialization)
                                <p class="text-sm text-gray-500">{{ $appointment->doctor->specialization }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Service Information -->
                @if($appointment->service)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='hgi-stroke hgi-briefcase-01 text-amber-500'></i>
                            Service
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                                <i class='hgi-stroke hgi-plus-sign text-white text-xl'></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $appointment->service->name }}</h4>
                                <p class="text-sm text-gray-500">{{ ucfirst($appointment->service->type) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='hgi-stroke hgi-energy-ellipse text-amber-500'></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition">
                                <i class='hgi-stroke hgi-pencil-edit-01 text-amber-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Edit Appointment</p>
                                <p class="text-xs text-gray-500">Modify details</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.appointments.invoice', $appointment->id) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 transition group">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                                <i class='hgi-stroke hgi-invoice-01 text-green-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">View Invoice</p>
                                <p class="text-xs text-gray-500">Print or download</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.patients.show', $appointment->patient->id) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition group">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                                <i class='hgi-stroke hgi-user text-blue-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Patient Profile</p>
                                <p class="text-xs text-gray-500">View full history</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection