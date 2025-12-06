@extends('layouts.doctor')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative">
                <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                    <i class='bx bx-arrow-back'></i> Back to Details
                </a>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-edit text-xl'></i>
                    </div>
                    Edit Appointment
                </h1>
            </div>
        </div>

        <!-- Appointment Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                @if($appointment->service)
                                    <span class="text-gray-400">•</span> {{ $appointment->service->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-700',
                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-purple-100 text-purple-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'no_show' => 'bg-amber-100 text-amber-700',
                            ];
                            $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg {{ $statusColor }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('doctor.appointments.update', $appointment->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('status') border-red-500 @enderror">
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
                    <label for="diagnosis" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-search-alt text-gray-400 mr-1'></i> Diagnosis
                    </label>
                    <textarea id="diagnosis" name="diagnosis" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('diagnosis') border-red-500 @enderror"
                        placeholder="Enter diagnosis...">{{ old('diagnosis', $appointment->diagnosis) }}</textarea>
                    @error('diagnosis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prescription -->
                <div class="mb-6">
                    <label for="prescription" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-capsule text-gray-400 mr-1'></i> Prescription
                    </label>
                    <textarea id="prescription" name="prescription" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('prescription') border-red-500 @enderror"
                        placeholder="Enter prescription...">{{ old('prescription', $appointment->prescription) }}</textarea>
                    @error('prescription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-note text-gray-400 mr-1'></i> Notes
                    </label>
                    <textarea id="notes" name="notes" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('notes') border-red-500 @enderror"
                        placeholder="Enter notes...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                        class="px-5 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium shadow-sm">
                        <i class='bx bx-save mr-2'></i>
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
