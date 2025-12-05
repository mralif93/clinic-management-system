@extends('layouts.admin')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-calendar-check text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Appointment #{{ $appointment->id }}</h1>
                        <p class="text-indigo-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-calendar'></i>
                            {{ $appointment->appointment_date->format('l, M d, Y') }} at
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-400/30',
                                    'confirmed' => 'bg-green-400/30',
                                    'completed' => 'bg-gray-400/30',
                                    'cancelled' => 'bg-red-400/30',
                                    'no_show' => 'bg-yellow-400/30',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-400/30' }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                            @if($appointment->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if(!$appointment->trashed())
                        <a href="{{ route('admin.appointments.invoice', $appointment->id) }}" title="View Invoice"
                            class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-green-600 hover:bg-green-50 hover:scale-105 transition-all shadow-lg">
                            <i class='bx bx-receipt text-xl'></i>
                        </a>
                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}" title="Edit Appointment"
                            class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-indigo-600 hover:bg-indigo-50 hover:scale-105 transition-all shadow-lg">
                            <i class='bx bx-edit text-xl'></i>
                        </a>
                    @else
                        <form action="{{ route('admin.appointments.restore', $appointment->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-green-600 rounded-full font-semibold hover:bg-green-50 hover:scale-105 transition-all shadow-lg">
                                <i class='bx bx-refresh text-lg'></i>
                                Restore
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.appointments.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur text-white rounded-full font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <i class='bx bx-money text-2xl text-indigo-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fee</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ get_currency_symbol() }}{{ number_format($appointment->fee ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            @if($appointment->discount_amount > 0)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                            <i class='bx bx-purchase-tag text-2xl text-red-600'></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Discount</p>
                            <p class="text-xl font-bold text-red-600">
                                -{{ get_currency_symbol() }}{{ number_format($appointment->discount_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <i class='bx bx-badge-check text-2xl text-green-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Final Amount</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ get_currency_symbol() }}{{ number_format($appointment->final_amount ?? $appointment->fee ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    @php
                        $paymentStatusColors = [
                            'paid' => ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
                            'unpaid' => ['bg' => 'bg-red-50', 'text' => 'text-red-600'],
                            'partial' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                        ];
                        $paymentColor = $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? $paymentStatusColors['unpaid'];
                    @endphp
                    <div class="w-12 h-12 rounded-xl {{ $paymentColor['bg'] }} flex items-center justify-center">
                        <i class='bx bx-credit-card text-2xl {{ $paymentColor['text'] }}'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment</p>
                        <p class="text-xl font-bold {{ $paymentColor['text'] }}">
                            {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Patient Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-indigo-600'></i>
                        Patient Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Name</span>
                            <span class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</span>
                        </div>
                        @if($appointment->patient->email)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Email</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appointment->patient->email }}</span>
                            </div>
                        @endif
                        @if($appointment->patient->phone)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appointment->patient->phone }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-calendar text-indigo-600'></i>
                        Appointment Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Date</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Time</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Status</span>
                            @php
                                $statusBadge = [
                                    'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'confirmed' => 'bg-green-50 text-green-700 border-green-200',
                                    'completed' => 'bg-gray-50 text-gray-700 border-gray-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    'no_show' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium border {{ $statusBadge[$appointment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>
                        @if($appointment->payment_method)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">Payment Method</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Doctor Information -->
            @if($appointment->doctor)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-plus-medical text-indigo-600'></i>
                            Doctor Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Name</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appointment->doctor->full_name }}</span>
                            </div>
                            @if($appointment->doctor->specialization)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Specialization</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $appointment->doctor->specialization }}</span>
                                </div>
                            @endif
                            @if($appointment->doctor->user && $appointment->doctor->user->employment_type)
                                <div class="flex items-center justify-between py-3">
                                    <span class="text-sm text-gray-500">Employment Type</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $appointment->doctor->user->employment_type === 'locum' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->doctor->user->employment_type)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Service Information -->
            @if($appointment->service)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-cube text-indigo-600'></i>
                            Service Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Service</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appointment->service->name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Type</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ ucfirst($appointment->service->type) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">Price</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($appointment->service->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Commission Breakdown (for Locum Doctors) -->
        @if($appointment->is_locum_doctor && $appointment->doctor_commission > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-purple-200 overflow-hidden">
                <div class="p-6 border-b border-purple-100 bg-purple-50/50">
                    <h3 class="text-lg font-semibold text-purple-900 flex items-center gap-2">
                        <i class='bx bx-wallet text-purple-600'></i>
                        Commission Breakdown
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center p-4 bg-purple-50 rounded-xl">
                            <p class="text-xs text-purple-600 mb-1">Doctor Type</p>
                            <p class="text-sm font-semibold text-purple-900">Locum Doctor</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-xl">
                            <p class="text-xs text-purple-600 mb-1">Commission Rate</p>
                            <p class="text-lg font-bold text-purple-900">
                                {{ number_format($appointment->doctor_commission_rate, 0) }}%</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-xl">
                            <p class="text-xs text-purple-600 mb-1">Appointment Fee</p>
                            <p class="text-lg font-bold text-purple-900">
                                {{ get_currency_symbol() }}{{ number_format($appointment->final_amount ?? $appointment->fee, 2) }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-purple-100 rounded-xl">
                            <p class="text-xs text-purple-700 mb-1">Doctor's Commission</p>
                            <p class="text-xl font-bold text-purple-900">
                                {{ get_currency_symbol() }}{{ number_format($appointment->doctor_commission, 2) }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-purple-700 mt-4 flex items-center gap-1">
                        <i class='bx bx-info-circle'></i>
                        This commission will be included in the doctor's payroll calculation.
                    </p>
                </div>
            </div>
        @endif

        <!-- Notes, Diagnosis, Prescription -->
        @if($appointment->notes || $appointment->diagnosis || $appointment->prescription)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @if($appointment->notes)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class='bx bx-note text-indigo-600'></i>
                                Notes
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-gray-700 prose max-w-none">{!! $appointment->notes !!}</div>
                        </div>
                    </div>
                @endif

                @if($appointment->diagnosis)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class='bx bx-search-alt text-indigo-600'></i>
                                Diagnosis
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-gray-700 prose max-w-none">{!! $appointment->diagnosis !!}</div>
                        </div>
                    </div>
                @endif

                @if($appointment->prescription)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class='bx bx-capsule text-indigo-600'></i>
                                Prescription
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-gray-700 prose max-w-none">{!! $appointment->prescription !!}</div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Timestamps -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-time text-indigo-600'></i>
                    Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $appointment->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $appointment->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $appointment->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $appointment->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($appointment->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $appointment->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $appointment->deleted_at->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection