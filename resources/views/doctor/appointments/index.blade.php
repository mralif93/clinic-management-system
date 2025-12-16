@extends('layouts.doctor')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-calendar-check text-xl'></i>
                        </div>
                        My Appointments
                    </h1>
                    <p class="text-emerald-100 mt-2">Manage your patient appointments</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <form method="GET" action="{{ route('doctor.appointments.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by patient name..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                <div>
                    <select name="status"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <option value="">All Statuses</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <i class='bx bx-search mr-2'></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date']))
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                        <i class='bx bx-x mr-1'></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Appointments Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $appointment->patient->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $appointment->patient->patient_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-blue-100 text-blue-700',
                                            'confirmed' => 'bg-cyan-100 text-cyan-700',
                                            'in_progress' => 'bg-amber-100 text-amber-700',
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            'no_show' => 'bg-gray-100 text-gray-700',
                                        ];
                                        $statusLabels = [
                                            'scheduled' => 'Scheduled',
                                            'confirmed' => 'Checked In',
                                            'in_progress' => 'In Consultation',
                                            'completed' => 'Completed',
                                            'cancelled' => 'Cancelled',
                                            'no_show' => 'No Show',
                                        ];
                                        $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg {{ $statusColor }}">
                                        {{ $statusLabels[$appointment->status] ?? ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    @if($appointment->fee)
                                        {{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('doctor.appointments.invoice', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition" title="Invoice">
                                            <i class='bx bx-receipt text-lg'></i>
                                        </a>
                                        <a href="{{ route('doctor.appointments.edit', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition" title="Edit">
                                            <i class='bx bx-pencil text-lg'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No appointments found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection