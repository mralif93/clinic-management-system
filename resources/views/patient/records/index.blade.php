@extends('layouts.public')

@section('title', 'Medical Records')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Medical Records</h1>
                <p class="text-gray-600 mt-1">View your completed appointments and medical history</p>
            </div>

            @if($records->count() > 0)
                <div class="space-y-4">
                    @foreach($records as $record)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $record->service->name ?? 'N/A' }}
                                            </h3>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                Completed
                                            </span>
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-gray-600">
                                                <i class='bx bx-user-plus mr-1'></i>
                                                Dr. {{ $record->doctor->user->name ?? 'N/A' }}
                                                @if($record->doctor->specialization)
                                                    <span class="text-gray-500">- {{ $record->doctor->specialization }}</span>
                                                @endif
                                            </p>

                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <i class='bx bx-calendar mr-1'></i>
                                                    {{ \Carbon\Carbon::parse($record->appointment_date)->format('M d, Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class='bx bx-time mr-1'></i>
                                                    {{ $record->appointment_time }}
                                                </span>
                                            </div>

                                            @if($record->notes)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-sm font-medium text-gray-700 mb-1">Notes:</p>
                                                    <p class="text-sm text-gray-600">{{ $record->notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">
                                        ${{ number_format($record->service->price ?? 0, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Service Fee</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $records->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                    <i class='bx bx-file text-6xl text-gray-300 mb-4'></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Medical Records Yet</h3>
                    <p class="text-gray-600 mb-6">You don't have any completed appointments yet.</p>
                    <a href="{{ route('patient.appointments.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class='bx bx-calendar-plus mr-2'></i>
                        Book an Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection