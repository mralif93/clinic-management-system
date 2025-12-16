@extends('layouts.staff')

@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar-check text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Appointments</h1>
                        <p class="text-amber-100 text-sm mt-1">Manage all clinic appointments</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-calendar mr-1'></i>
                        {{ now()->format('l, M d, Y') }}
                    </div>
                    <a href="{{ route('staff.appointments.create') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition shadow-lg hover:shadow-xl">
                        <i class='bx bx-plus mr-2 text-lg'></i>
                        Schedule Appointment
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $todayCount = $appointments->where('appointment_date', today())->count();
                $scheduledCount = $appointments->where('status', 'scheduled')->count();
                $confirmedCount = $appointments->where('status', 'confirmed')->count();
                $completedCount = $appointments->where('status', 'completed')->count();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $appointments->total() }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-time text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $scheduledCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Scheduled</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $confirmedCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Checked In</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-double text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $completedCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 rounded-2xl border border-amber-100/50 p-5">
            <form method="GET" action="{{ route('staff.appointments.index') }}">
                <!-- Search Bar -->
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class='bx bx-search text-amber-500 text-xl'></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search appointments by patient name, phone, or email..."
                        class="w-full pl-12 pr-4 py-3 bg-white border-0 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 text-gray-700 placeholder-gray-400">
                </div>

                <!-- Filter Options -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-amber-700 flex items-center gap-1.5">
                        <i class='bx bx-filter-alt'></i> Filters:
                    </span>

                    <select name="status"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 text-sm text-gray-700">
                        <option value="">All Statuses</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>📅 Scheduled</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Checked In</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>👨‍⚕️ In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✔️ Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>⚠️ No Show</option>
                    </select>

                    <select name="doctor_id"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 text-sm text-gray-700">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>Dr. {{ $doctor->full_name }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="date" value="{{ request('date') }}"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 text-sm text-gray-700">

                    <div class="flex items-center gap-2 ml-auto">
                        @if(request()->hasAny(['search', 'status', 'doctor_id', 'date']))
                            <a href="{{ route('staff.appointments.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                                <i class='bx bx-x mr-1'></i> Clear
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-amber-600 hover:to-orange-600 transition-all">
                            <i class='bx bx-search mr-1.5'></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Appointments Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-amber-500'></i>
                        Appointments List
                    </h3>
                    <span class="text-sm text-gray-500">Showing {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-amber-50/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}{{ strtoupper(substr($appointment->patient->last_name ?? '', 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">{{ $appointment->patient->full_name }}</div>
                                            <div class="text-xs text-amber-600 font-medium">{{ $appointment->patient->patient_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-user text-blue-600'></i>
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $appointment->doctor->full_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium">
                                        <i class='bx bx-briefcase-alt mr-1'></i>
                                        {{ $appointment->service->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class='bx bx-time'></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'scheduled' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-calendar', 'label' => 'Scheduled'],
                                            'confirmed' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'icon' => 'bx-check-circle', 'label' => 'Checked In'],
                                            'in_progress' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'bx-loader-circle', 'label' => 'In Consultation'],
                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check-double', 'label' => 'Completed'],
                                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-x-circle', 'label' => 'Cancelled'],
                                            'no_show' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-error', 'label' => 'No Show'],
                                        ];
                                        $config = $statusConfig[$appointment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-help-circle', 'label' => ucfirst($appointment->status)];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                        <i class='bx {{ $config['icon'] }}'></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->fee)
                                        <div class="space-y-0.5">
                                            @if($appointment->discount_value > 0)
                                                <span class="text-xs text-gray-400 line-through">{{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}</span>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-sm font-semibold text-green-600">{{ get_currency_symbol() }}{{ number_format($appointment->final_amount, 2) }}</span>
                                                    <span class="text-xs px-1.5 py-0.5 bg-red-100 text-red-600 rounded font-medium">-{{ $appointment->discount_display }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm font-semibold text-green-600">{{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm hover:shadow" title="View Details">
                                            <i class='bx bx-show text-sm'></i>
                                        </a>
                                        <a href="{{ route('staff.appointments.invoice', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm hover:shadow" title="View Invoice">
                                            <i class='bx bx-receipt text-sm'></i>
                                        </a>
                                        <a href="{{ route('staff.appointments.edit', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-amber-500 text-white hover:bg-amber-600 rounded-full transition shadow-sm hover:shadow" title="Edit">
                                            <i class='bx bx-edit text-sm'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No appointments found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new appointment</p>
                                        <a href="{{ route('staff.appointments.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition text-sm font-medium">
                                            <i class='bx bx-plus mr-1'></i> Schedule Appointment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection