@extends('layouts.doctor')

@section('title', 'My Patients')
@section('page-title', 'My Patients')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative">
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-user-circle text-xl'></i>
                    </div>
                    My Patients
                </h1>
                <p class="text-emerald-100 mt-2">View patients who have appointments with you</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <form method="GET" action="{{ route('doctor.patients.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, email, or patient ID..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <i class='bx bx-search mr-2'></i> Search
                </button>
                @if(request()->has('search'))
                    <a href="{{ route('doctor.patients.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                        <i class='bx bx-x mr-1'></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Patients Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Appointment</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-semibold">
                                        {{ $patient->patient_id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $patient->full_name }}</div>
                                            @if($patient->date_of_birth)
                                                <div class="text-xs text-gray-500">{{ $patient->date_of_birth->age }} years old</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $patient->email ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $patient->phone ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($patient->gender)
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-gray-100 text-gray-700">
                                            {{ ucfirst($patient->gender) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($patient->appointments->count() > 0)
                                        @php
                                            $lastAppointment = $patient->appointments->first();
                                        @endphp
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $lastAppointment->appointment_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($lastAppointment->status) }}</div>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end">
                                        <a href="{{ route('doctor.patients.show', $patient->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition"
                                            title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class='bx bx-user-x text-3xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No patients found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($patients->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection