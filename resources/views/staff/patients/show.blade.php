@extends('layouts.staff')

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

        <div class="relative">
                <div class="flex items-center gap-4">
                    <a href="{{ route('staff.patients.index') }}"
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-arrow-left-01 text-white text-xl'></i>
                    </a>
                    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($patient->first_name, 0, 1)) }}</span>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">{{ $patient->full_name }}</h1>
                        <p class="text-blue-100 text-sm mt-1 flex items-center gap-2">
                            <i class='hgi-stroke hgi-identity-card'></i>
                            {{ $patient->patient_id ?? 'N/A' }}
                            @if($patient->gender)
                                <span class="text-white/50">•</span>
                                <i class='hgi-stroke {{ $patient->gender == 'male' ? 'hgi-graduate-male' : 'hgi-graduate-female' }}'></i>
                                {{ ucfirst($patient->gender) }}
                            @endif
                            @if($patient->date_of_birth)
                                <span class="text-white/50">•</span>
                                {{ $patient->date_of_birth->age }} years old
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class='hgi-stroke hgi-calendar-03 text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $totalAppointments }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <i class='hgi-stroke hgi-checkmark-circle-02 text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $completedAppointments }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Completed</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center">
                    <i class='hgi-stroke hgi-clock-02 text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $upcomingAppointments }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Upcoming</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                    <i class='hgi-stroke hgi-birthday-cake text-white text-xl'></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d') : 'N/A' }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Birthday</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='hgi-stroke hgi-calendar-03 text-blue-500'></i>
                        Appointment History
                    </h3>
                </div>
                <div class="p-6">
                    @if($patient->appointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($patient->appointments as $appointment)
                                @php
                                    $statusColors = [
                                        'scheduled' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                                        'confirmed' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700'],
                                        'in_progress' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                        'no_show' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
                                    ];
                                    $statusColor = $statusColors[$appointment->status] ?? $statusColors['scheduled'];
                                @endphp
                                <div class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition space-y-3">
                                    <div class="flex items-start gap-4">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex flex-col items-center justify-center text-white">
                                            <span class="text-xs font-medium">{{ $appointment->appointment_date->format('M') }}</span>
                                            <span class="text-lg font-bold leading-none">{{ $appointment->appointment_date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate">{{ $appointment->service->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-500 flex items-center gap-2 flex-wrap">
                                                <i class='hgi-stroke hgi-clock-02'></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                @if($appointment->doctor)
                                                    <span class="text-gray-300">•</span>
                                                    Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}
                                                @endif
                                            </p>
                                        </div>
                                        <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transition">
                                            <i class='hgi-stroke hgi-eye'></i>
                                        </a>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                        @if($appointment->status === 'completed')
                                            @if($appointment->record_approved_at)
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 flex items-center gap-1">
                                                        <i class='hgi-stroke hgi-checkmark-circle-02'></i> Doctor Approved
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        Dr. {{ $appointment->recordApprovedBy->name ?? '' }} &bull; {{ $appointment->record_approved_at->format('M d, Y h:i A') }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 flex items-center gap-1">
                                                    <i class='hgi-stroke hgi-clock-02'></i> Waiting Doctor Approval
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    @if($appointment->diagnosis || $appointment->prescription || $appointment->notes)
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            @if($appointment->diagnosis)
                                                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r-lg p-3">
                                                    <p class="text-xs uppercase tracking-wide text-blue-600 font-semibold mb-1">Diagnosis</p>
                                                    <div class="text-sm text-gray-700">{!! $appointment->diagnosis !!}</div>
                                                </div>
                                            @endif
                                            @if($appointment->prescription)
                                                <div class="bg-emerald-50 border-l-4 border-emerald-400 rounded-r-lg p-3">
                                                    <p class="text-xs uppercase tracking-wide text-emerald-600 font-semibold mb-1">Prescription</p>
                                                    <div class="text-sm text-gray-700">{!! $appointment->prescription !!}</div>
                                                </div>
                                            @endif
                                            @if($appointment->notes)
                                                <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-lg p-3">
                                                    <p class="text-xs uppercase tracking-wide text-amber-600 font-semibold mb-1">Notes</p>
                                                    <div class="text-sm text-gray-700">{!! $appointment->notes !!}</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='hgi-stroke hgi-calendar-03 text-gray-400 text-3xl'></i>
                            </div>
                            <p class="text-gray-500">No appointment history found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Medical Information -->
            @if($patient->medical_history || $patient->allergies)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='hgi-stroke hgi-plus-sign text-blue-500'></i>
                        Medical Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($patient->medical_history)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class='hgi-stroke hgi-time-schedule text-gray-400'></i> Medical History
                        </h4>
                        <div class="bg-blue-50 p-4 rounded-xl text-sm text-gray-700">{{ $patient->medical_history }}</div>
                    </div>
                    @endif
                    @if($patient->allergies)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class='hgi-stroke hgi-alert-circle text-red-400'></i> Allergies
                        </h4>
                        <div class="bg-red-50 p-4 rounded-xl text-sm text-red-700">{{ $patient->allergies }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='hgi-stroke hgi-user text-blue-500'></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <i class='hgi-stroke hgi-user text-gray-400'></i>
                        <div>
                            <p class="text-xs text-gray-500">Full Name</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <i class='hgi-stroke hgi-calendar-03 text-gray-400'></i>
                        <div>
                            <p class="text-xs text-gray-500">Date of Birth</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <i class='hgi-stroke {{ $patient->gender == 'male' ? 'hgi-graduate-male' : 'hgi-graduate-female' }} text-gray-400'></i>
                        <div>
                            <p class="text-xs text-gray-500">Gender</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='hgi-stroke hgi-call text-blue-500'></i>
                        Contact Information
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <i class='hgi-stroke hgi-mail-01 text-gray-400'></i>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <i class='hgi-stroke hgi-call text-gray-400'></i>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <i class='hgi-stroke hgi-maps-location-01 text-gray-400'></i>
                        <div>
                            <p class="text-xs text-gray-500">Address</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='hgi-stroke hgi-alert-circle text-red-500'></i>
                        Emergency Contact
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl">
                        <i class='hgi-stroke hgi-user text-red-400'></i>
                        <div>
                            <p class="text-xs text-red-500">Contact Name</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl">
                        <i class='hgi-stroke hgi-call text-red-400'></i>
                        <div>
                            <p class="text-xs text-red-500">Contact Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ $patient->emergency_contact_phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

