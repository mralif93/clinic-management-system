@extends('layouts.public')

@section('title', 'Appointment Details')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Appointment Details</h1>
                    <p class="text-gray-600 mt-1">View your appointment information</p>
                </div>
                <a href="{{ route('patient.appointments.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class='bx bx-arrow-back mr-1'></i>
                    Back to Appointments
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <!-- Status Banner -->
                @php
                    $statusLabels = [
                        'scheduled' => 'Scheduled',
                        'confirmed' => 'Checked In',
                        'in_progress' => 'In Consultation',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show',
                    ];
                @endphp
                <div class="px-6 py-4 border-b border-gray-200
                                @if($appointment->status === 'completed') bg-green-50
                                @elseif($appointment->status === 'in_progress') bg-amber-50
                                @elseif($appointment->status === 'confirmed') bg-cyan-50
                                @elseif($appointment->status === 'scheduled') bg-blue-50
                                @elseif($appointment->status === 'cancelled') bg-red-50
                                @else bg-gray-50
                                @endif">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</h2>
                            <p class="text-sm text-gray-600 mt-1">Appointment ID: #{{ $appointment->id }}</p>
                        </div>
                        <span class="px-4 py-2 text-sm font-medium rounded-full
                                        @if($appointment->status === 'completed') bg-green-100 text-green-700
                                        @elseif($appointment->status === 'in_progress') bg-amber-100 text-amber-700
                                        @elseif($appointment->status === 'confirmed') bg-cyan-100 text-cyan-700
                                        @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-700
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                            {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="p-6 space-y-6">
                    <!-- Doctor Information -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class='bx bx-user-plus text-blue-600 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Doctor</p>
                            <p class="text-lg font-semibold text-gray-900">Dr.
                                {{ $appointment->doctor->user->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-calendar text-green-600 text-2xl'></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Date</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-time text-purple-600 text-2xl'></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Time</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointment->appointment_time }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Service & Price -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                            <i class='bx bx-list-ul text-orange-600 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Service</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">${{ number_format($appointment->service->price ?? 0, 2) }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($appointment->notes)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-note text-gray-600 text-2xl'></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Notes</p>
                                <div class="text-gray-900 rich-content">{!! $appointment->notes !!}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    @php
                        $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
                        $canCancel = $appointment->status !== 'cancelled' &&
                            $appointment->status !== 'completed' &&
                            now()->diffInHours($appointmentTime, false) > 24;
                    @endphp

                    @if($canCancel)
                        <div class="pt-6 border-t border-gray-200 flex justify-end">
                            <form action="{{ route('patient.appointments.cancel', $appointment->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.');">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-md shadow-sm text-sm font-medium transition-colors duration-150">
                                    Cancel Appointment
                                </button>
                            </form>
                        </div>
                    @endif
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Booked on {{ \Carbon\Carbon::parse($appointment->created_at)->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection