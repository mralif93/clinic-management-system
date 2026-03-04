@extends('layouts.public')

@section('title', 'Appointment Submitted')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="px-6 py-8 text-center bg-gradient-to-b from-blue-50 to-white border-b border-gray-200">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-time text-blue-600 text-4xl'></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Appointment Submitted!</h1>
                        <p class="text-gray-600">Your appointment request has been received and is pending confirmation.</p>
                    </div>

                    <div class="p-6">
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <i class='bx bx-info-circle text-amber-500 text-xl mr-3 mt-0.5'></i>
                                <div>
                                    <h3 class="font-semibold text-amber-800">Waiting for Confirmation</h3>
                                    <p class="text-sm text-amber-700 mt-1">
                                        Our staff will review your appointment request. You will receive a confirmation once approved.
                                        Please check back later or refresh this page to see your confirmation status.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Appointment Details</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Reference No:</span>
                                    <span class="font-mono font-semibold text-gray-900">#{{ $appointment->confirmation_token ?? $appointment->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Doctor:</span>
                                    <span class="text-gray-900">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Service:</span>
                                    <span class="text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Time:</span>
                                    <span class="text-gray-900">{{ $appointment->appointment_time }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                        Pending Confirmation
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('patient.appointments.pending', $appointment->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                <i class='bx bx-refresh mr-2'></i>
                                Refresh Status
                            </a>
                            <a href="{{ route('patient.appointments.index') }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                <i class='bx bx-list-ul mr-2'></i>
                                View All Appointments
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Submitted on {{ \Carbon\Carbon::parse($appointment->created_at)->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.location.reload();
            }, 60000);
        });
    </script>
    @endpush
@endsection
