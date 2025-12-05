@extends('layouts.admin')

@section('title', 'Appointment Management')
@section('page-title', 'Appointment Management')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class='bx bx-calendar text-primary-600 text-2xl'></i>
                Appointments
            </h1>
            <p class="text-sm text-gray-600">Appointments for {{ $monthName }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.appointments.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                <i class='bx bx-arrow-back text-base'></i>
                Back to Months
            </a>
            <a href="{{ route('admin.appointments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                <i class='bx bx-plus text-base'></i>
                Schedule Appointment
            </a>
        </div>
    </div>

    <!-- Statistics Cards (Contextual) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl'></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Scheduled</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['scheduled'] }}</h3>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-time text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['completed'] }}</h3>
                </div>
                <div class="bg-success-50 text-success-600 p-4 rounded-full">
                    <i class='bx bx-check-double text-3xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.appointments.by-month', ['year' => $year, 'month' => $month]) }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-search'></i> Search
                    </label>
                    <input type="text" 
                           id="search"
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by patient name, doctor, or service..."
                           class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-info-circle'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
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
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-calendar'></i> Date
                    </label>
                    <input type="date" 
                           id="date"
                           name="date" 
                           value="{{ request('date') }}"
                           class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-filter-alt text-base'></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'status', 'date']))
                    <a href="{{ route('admin.appointments.by-month', ['year' => $year, 'month' => $month]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                        <i class='bx bx-x text-base'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left tracking-wide">Patient</th>
                        <th class="px-6 py-3 text-left tracking-wide">Doctor</th>
                        <th class="px-6 py-3 text-left tracking-wide">Service</th>
                        <th class="px-6 py-3 text-left tracking-wide">Date & Time</th>
                        <th class="px-6 py-3 text-left tracking-wide">Status</th>
                        <th class="px-6 py-3 text-left tracking-wide">Fee</th>
                        <th class="px-6 py-3 text-right tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $appointment->trashed() ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->patient->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->doctor)
                                    <div class="text-sm text-gray-900">{{ $appointment->doctor->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->doctor->specialization ?? 'N/A' }}</div>
                                    @if($appointment->is_locum_doctor)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 mt-1">
                                            <i class='bx bx-briefcase-alt text-xs mr-1'></i>
                                            Locum
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">Not Assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->service)
                                    <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($appointment->service->type) }}</div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'no_show' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($appointment->fee)
                                    <div class="text-gray-900 font-medium">
                                        {{ get_setting('currency', '$') }}{{ number_format($appointment->fee, 2) }}
                                    </div>

                                    @if($appointment->discount_amount > 0)
                                        <div class="text-xs text-orange-600">
                                            <i class='bx bx-purchase-tag-alt'></i>
                                            Discount: -{{ get_setting('currency', '$') }}{{ number_format($appointment->discount_amount, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-600 font-semibold">
                                            Final: {{ get_setting('currency', '$') }}{{ number_format($appointment->final_amount, 2) }}
                                        </div>
                                    @endif

                                    @if($appointment->is_locum_doctor && $appointment->doctor_commission > 0)
                                        <div class="text-xs text-purple-600 mt-1 flex items-center">
                                            <i class='bx bx-wallet mr-1'></i>
                                            Commission ({{ number_format($appointment->doctor_commission_rate, 0) }}%):
                                            <span class="font-semibold ml-1">{{ get_setting('currency', '$') }}{{ number_format($appointment->doctor_commission, 2) }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($appointment->trashed())
                                        <button onclick="restoreAppointment({{ $appointment->id }}, '{{ $appointment->patient->name ?? 'Unknown' }}')"
                                                class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                title="Restore">
                                            <i class='bx bx-undo text-base'></i>
                                        </button>
                                        <button onclick="forceDeleteAppointment({{ $appointment->id }}, '{{ $appointment->patient->name ?? 'Unknown' }}')"
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Permanently Delete">
                                            <i class='bx bx-x-circle text-base'></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm" title="View">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.invoice', $appointment->id) }}" class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm" title="Invoice">
                                            <i class='bx bx-receipt text-base'></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm" title="Edit">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <button onclick="deleteAppointment({{ $appointment->id }}, '{{ $appointment->patient->name ?? 'Unknown' }}')"
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Delete">
                                            <i class='bx bx-trash text-base'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No appointments found for this month.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteAppointment(id, patientName) {
        Swal.fire({
            title: 'Delete Appointment?',
            html: `Are you sure you want to delete the appointment for <strong>${patientName}</strong>?<br><br>This action will soft delete the appointment. You can restore it later.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
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
            confirmButtonText: 'Yes, Restore',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
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
            html: `Are you sure you want to <strong class="text-red-600">permanently delete</strong> the appointment for <strong>${patientName}</strong>?<br><br>This action cannot be undone!`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete Permanently',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
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