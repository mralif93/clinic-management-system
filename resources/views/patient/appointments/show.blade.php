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
                    <i class='hgi-stroke hgi-arrow-left-01 mr-1'></i>
                    Back to Appointments
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Appointment Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <!-- Status Banner -->
                        @php
                            $statusLabels = [
                                'pending' => 'Pending Confirmation',
                                'scheduled' => 'Scheduled',
                                'arrived' => 'Arrived - Waiting for Doctor',
                                'confirmed' => 'With Doctor',
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
                                        @elseif($appointment->status === 'arrived') bg-orange-50
                                        @elseif($appointment->status === 'scheduled') bg-blue-50
                                        @elseif($appointment->status === 'pending') bg-yellow-50
                                        @elseif($appointment->status === 'cancelled') bg-red-50
                                        @else bg-gray-50
                                        @endif">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</h2>
                                    <p class="text-sm text-gray-600 mt-1">Ref: #{{ $appointment->confirmation_token ?? $appointment->id }}</p>
                                </div>
                                <span class="px-4 py-2 text-sm font-medium rounded-full
                                                @if($appointment->status === 'completed') bg-green-100 text-green-700
                                                @elseif($appointment->status === 'in_progress') bg-amber-100 text-amber-700
                                                @elseif($appointment->status === 'confirmed') bg-cyan-100 text-cyan-700
                                                @elseif($appointment->status === 'arrived') bg-orange-100 text-orange-700
                                                @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-700
                                                @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-700
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
                                    <i class='hgi-stroke hgi-user-add-01 text-blue-600 text-2xl'></i>
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
                                        <i class='hgi-stroke hgi-calendar-03 text-green-600 text-2xl'></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Date</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                        <i class='hgi-stroke hgi-clock-02 text-purple-600 text-2xl'></i>
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
                                    <i class='hgi-stroke hgi-list-view text-orange-600 text-2xl'></i>
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
                                        <i class='hgi-stroke hgi-note-01 text-gray-600 text-2xl'></i>
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
                                    $appointment->status !== 'pending' &&
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

                <!-- QR Code Section -->
                <div class="lg:col-span-1">
                    @if($appointment->status === 'arrived')
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-4">
                            <div class="px-6 py-4 bg-orange-50 border-b border-orange-100">
                                <div class="flex items-center">
                                    <i class='hgi-stroke hgi-user-check-01 text-orange-600 text-xl mr-2'></i>
                                    <h3 class="font-semibold text-orange-800">Checked In!</h3>
                                </div>
                                <p class="text-sm text-orange-600 mt-1">Waiting for doctor to accept</p>
                            </div>
                            <div class="p-6 text-center">
                                <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-orange-100 flex items-center justify-center animate-pulse">
                                    <i class='hgi-stroke hgi-clock-02 text-orange-500 text-5xl'></i>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">
                                    Please wait in the waiting area.
                                </p>
                                <p class="text-gray-500 text-xs">
                                    You will be called when the doctor is ready.
                                </p>
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="font-mono font-semibold text-gray-900">{{ $appointment->confirmation_token }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Your Reference Number</p>
                                </div>
                            </div>
                        </div>
                    @elseif($appointment->status === 'confirmed')
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-4">
                            <div class="px-6 py-4 bg-cyan-50 border-b border-cyan-100">
                                <div class="flex items-center">
                                    <i class='hgi-stroke hgi-door-01 text-cyan-600 text-xl mr-2'></i>
                                    <h3 class="font-semibold text-cyan-800">With Doctor</h3>
                                </div>
                                @if($appointment->room_number)
                                    <p class="text-sm text-cyan-600 mt-1 font-bold text-lg">Room: {{ $appointment->room_number }}</p>
                                @endif
                            </div>
                            <div class="p-6 text-center">
                                <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-cyan-100 flex items-center justify-center">
                                    <i class='hgi-stroke hgi-door-01 text-cyan-500 text-5xl'></i>
                                </div>
                                <p class="text-gray-600 text-sm">
                                    You are now with the doctor.
                                </p>
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="font-mono font-semibold text-gray-900">{{ $appointment->confirmation_token }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Your Reference Number</p>
                                </div>
                            </div>
                        </div>
                    @elseif($appointment->isConfirmed())
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-4">
                            <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                                <div class="flex items-center">
                                    <i class='hgi-stroke hgi-qr-code text-green-600 text-xl mr-2'></i>
                                    <h3 class="font-semibold text-green-800">Appointment Confirmed</h3>
                                </div>
                                <p class="text-sm text-green-600 mt-1">Show this QR code at the clinic</p>
                            </div>
                            <div class="p-6 text-center">
                                <div id="qrcode" class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg"></div>
                                <div class="mt-4 text-sm text-gray-600">
                                    <p class="font-mono font-semibold text-gray-900">{{ $appointment->confirmation_token }}</p>
                                    <p class="mt-2 text-xs text-gray-500">Reference Number</p>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Important Information</h4>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li><i class='hgi-stroke hgi-checkmark-circle-02 text-green-500 mr-1'></i> Arrive 10 minutes early</li>
                                    <li><i class='hgi-stroke hgi-checkmark-circle-02 text-green-500 mr-1'></i> Bring this QR code or reference number</li>
                                    <li><i class='hgi-stroke hgi-checkmark-circle-02 text-green-500 mr-1'></i> Valid ID may be required</li>
                                </ul>
                            </div>
                        </div>
                    @elseif($appointment->status === 'pending')
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-4">
                            <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
                                <div class="flex items-center">
                                    <i class='hgi-stroke hgi-clock-02 text-yellow-600 text-xl mr-2'></i>
                                    <h3 class="font-semibold text-yellow-800">Awaiting Confirmation</h3>
                                </div>
                                <p class="text-sm text-yellow-600 mt-1">Your appointment is being reviewed</p>
                            </div>
                            <div class="p-6 text-center">
                                <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class='hgi-stroke hgi-clock-02 text-yellow-500 text-5xl'></i>
                                </div>
                                <p class="text-gray-600 text-sm">
                                    QR code will appear here once your appointment is confirmed.
                                </p>
                                <a href="{{ route('patient.appointments.pending', $appointment->id) }}" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                    <i class='hgi-stroke hgi-refresh mr-2'></i>
                                    Refresh Status
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden sticky top-4">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-800">Appointment Status</h3>
                            </div>
                            <div class="p-6 text-center">
                                @if($appointment->status === 'cancelled')
                                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class='hgi-stroke hgi-cancel-circle text-red-500 text-5xl'></i>
                                    </div>
                                    <p class="text-gray-600 text-sm">This appointment has been cancelled.</p>
                                @else
                                    <p class="text-gray-600 text-sm">Status: {{ $statusLabels[$appointment->status] ?? $appointment->status }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        #qrcode canvas, #qrcode img {
            display: block !important;
            margin: 0 auto;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var qrContainer = document.getElementById('qrcode');
            if (qrContainer) {
                var qrData = '{!! addslashes($appointment->getQrCodeData()) !!}';
                var typeNumber = 0;
                var errorCorrectionLevel = 'L';
                var qr = qrcode(typeNumber, errorCorrectionLevel);
                qr.addData(qrData);
                qr.make();
                qrContainer.innerHTML = qr.createImgTag(5, 10);
            }
        });
    </script>
    @endpush
@endsection
