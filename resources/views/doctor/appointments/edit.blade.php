@extends('layouts.doctor')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Appointment Info Header -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Patient: {{ $appointment->patient->full_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </p>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-purple-100 text-purple-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'no_show' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusColor }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </div>
                </div>
                @if($appointment->service)
                    <p class="text-sm text-gray-600 mt-2">Service: {{ $appointment->service->name }}</p>
                @endif
            </div>

            <form action="{{ route('doctor.appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Diagnosis -->
                <div class="mb-6">
                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('diagnosis') border-red-500 @enderror"
                        placeholder="Enter diagnosis...">{{ old('diagnosis', $appointment->diagnosis) }}</textarea>
                    @error('diagnosis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prescription -->
                <div class="mb-6">
                    <label for="prescription" class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                    <textarea id="prescription" name="prescription" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('prescription') border-red-500 @enderror"
                        placeholder="Enter prescription...">{{ old('prescription', $appointment->prescription) }}</textarea>
                    @error('prescription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                        placeholder="Enter notes...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-save mr-2 text-base'></i>
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
