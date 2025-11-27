@extends('layouts.public')

@section('title', 'My Appointments')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Appointments</h1>
                    <p class="text-gray-600 mt-1">View and manage your appointments</p>
                </div>
                <a href="{{ route('patient.appointments.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class='bx bx-plus mr-2 text-base'></i>
                    Book Appointment
                </a>
            </div>

            @if($appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($appointments as $appointment)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-calendar text-blue-600 text-2xl'></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}
                                        </h3>
                                        <p class="text-gray-600 mt-1">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <i class='bx bx-calendar mr-1'></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class='bx bx-time mr-1'></i>
                                                {{ $appointment->appointment_time }}
                                            </span>
                                        </div>
                                        @if($appointment->notes)
                                            <p class="text-sm text-gray-600 mt-2">
                                                <span class="font-medium">Notes:</span> {{ $appointment->notes }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-4 py-2 text-sm font-medium rounded-full
                                                                        @if($appointment->status === 'completed') bg-green-100 text-green-700
                                                                        @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-700
                                                                        @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-700
                                                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                                                        @else bg-gray-100 text-gray-700
                                                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <a href="{{ route('patient.appointments.show', $appointment->id) }}"
                                        class="text-blue-600 hover:text-blue-700">
                                        <i class='bx bx-chevron-right text-3xl'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                    <i class='bx bx-calendar-x text-6xl text-gray-300 mb-4'></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Appointments Yet</h3>
                    <p class="text-gray-600 mb-6">You haven't booked any appointments yet.</p>
                    <a href="{{ route('patient.appointments.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class='bx bx-plus mr-2 text-lg'></i>
                        Book Your First Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection