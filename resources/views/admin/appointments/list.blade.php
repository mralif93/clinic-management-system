@extends('layouts.admin')

@section('title', 'Appointment Management')
@section('page-title', 'Appointment Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-blue-600 to-sky-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-calendar text-2xl'></i>
                    </div>
                    Appointments
                </h1>
                <p class="mt-2 text-blue-100">Appointments for {{ $monthName }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.appointments.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='bx bx-arrow-back'></i>
                    All Months
                </a>
                <a href="{{ route('admin.appointments.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg shadow-blue-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    New Appointment
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                <p class="text-sm text-blue-200">Total</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['scheduled'] }}</p>
                <p class="text-sm text-blue-200">Scheduled</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['completed'] }}</p>
                <p class="text-sm text-blue-200">Completed</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $stats['cancelled'] ?? 0 }}</p>
                <p class="text-sm text-blue-200">Cancelled</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Appointments</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.appointments.by-month', ['year' => $year, 'month' => $month]) }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search patient, doctor, or service..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm bg-white">
                            <option value="">All Statuses</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </div>
                    
                    <!-- Date Filter -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-600 mb-2">Date</label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date']))
                        <a href="{{ route('admin.appointments.by-month', ['year' => $year, 'month' => $month]) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fee</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $appointment->trashed() ? 'bg-red-50/30' : '' }}">
                            <!-- Patient -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($appointment->patient->full_name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $appointment->patient->full_name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Doctor -->
                            <td class="px-6 py-4">
                                @if($appointment->doctor)
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $appointment->doctor->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                                        @if($appointment->is_locum_doctor)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 mt-1">
                                                <i class='bx bx-briefcase-alt'></i> Locum
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Not Assigned</span>
                                @endif
                            </td>
                            
                            <!-- Service -->
                            <td class="px-6 py-4">
                                @if($appointment->service)
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($appointment->service->type) }}</p>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Date & Time -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                        <i class='bx bx-calendar text-blue-600'></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'scheduled' => 'bg-blue-50 text-blue-700 ring-blue-500/20',
                                        'confirmed' => 'bg-indigo-50 text-indigo-700 ring-indigo-500/20',
                                        'completed' => 'bg-green-50 text-green-700 ring-green-500/20',
                                        'cancelled' => 'bg-red-50 text-red-700 ring-red-500/20',
                                        'no_show' => 'bg-amber-50 text-amber-700 ring-amber-500/20',
                                    ];
                                    $statusIcons = [
                                        'scheduled' => 'bx-time',
                                        'confirmed' => 'bx-check',
                                        'completed' => 'bx-check-double',
                                        'cancelled' => 'bx-x',
                                        'no_show' => 'bx-error',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$appointment->status] ?? $statusStyles['scheduled'] }}">
                                    <i class='bx {{ $statusIcons[$appointment->status] ?? 'bx-time' }}'></i>
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            
                            <!-- Fee -->
                            <td class="px-6 py-4">
                                @if($appointment->fee)
                                    <p class="text-sm font-semibold text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($appointment->fee, 2) }}</p>
                                    @if($appointment->discount_amount > 0)
                                        <p class="text-xs text-orange-600">-{{ get_setting('currency', '$') }}{{ number_format($appointment->discount_amount, 2) }}</p>
                                        <p class="text-xs font-semibold text-green-600">{{ get_setting('currency', '$') }}{{ number_format($appointment->final_amount, 2) }}</p>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    @if($appointment->trashed())
                                        <button onclick="restoreAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient->name ?? 'Unknown') }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                        <button onclick="forceDeleteAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient->name ?? 'Unknown') }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                            <i class='bx bx-x-circle text-lg'></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.invoice', $appointment->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-200 hover:scale-110 transition-all" title="Invoice">
                                            <i class='bx bx-receipt text-lg'></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        <button onclick="deleteAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient->name ?? 'Unknown') }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class='bx bx-calendar-x text-4xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No appointments found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or schedule a new appointment</p>
                                    <a href="{{ route('admin.appointments.create') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-all text-sm mt-4">
                                        <i class='bx bx-plus'></i>
                                        Schedule Appointment
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function deleteAppointment(id, patientName) {
        Swal.fire({
            title: 'Delete Appointment?',
            html: `Are you sure you want to delete the appointment for <strong>${patientName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-trash mr-1"></i> Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/appointments/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function restoreAppointment(id, patientName) {
        Swal.fire({
            title: 'Restore Appointment?',
            html: `Are you sure you want to restore the appointment for <strong>${patientName}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-undo mr-1"></i> Restore',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Restoring...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/appointments/${id}/restore`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function forceDeleteAppointment(id, patientName) {
        Swal.fire({
            title: 'Permanently Delete?',
            html: `<div class="text-left">
                <p>Are you sure you want to <strong class="text-red-600">permanently delete</strong> the appointment for <strong>${patientName}</strong>?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                    <p class="text-sm text-red-700"><i class='bx bx-error-circle mr-1'></i> This cannot be undone!</p>
                </div>
            </div>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete Permanently',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/appointments/${id}/force-delete`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection