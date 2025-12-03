@extends('layouts.admin')

@section('title', 'Appointment Management')
@section('page-title', 'Appointment Management')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
            <p class="text-sm text-gray-600 mt-1">Appointments for {{ $monthName }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.appointments.index') }}" 
               class="bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                <i class='bx bx-arrow-back mr-2 text-base'></i>
                Back to Months
            </a>
            <a href="{{ route('admin.appointments.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class='bx bx-plus mr-2 text-base'></i>
                Schedule Appointment
            </a>
        </div>
    </div>

    <!-- Statistics Cards (Contextual) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total Appointments</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class='bx bx-calendar text-3xl'></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Scheduled</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['scheduled'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class='bx bx-time text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Completed</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['completed'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class='bx bx-check-double text-3xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.appointments.by-month', ['year' => $year, 'month' => $month]) }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-search mr-1'></i> Search
                    </label>
                    <input type="text" 
                           id="search"
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by patient name, doctor, or service..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-info-circle mr-1'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
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
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-calendar mr-1'></i> Date
                    </label>
                    <input type="date" 
                           id="date"
                           name="date" 
                           value="{{ request('date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gray-700 text-white font-medium rounded-lg hover:bg-gray-800 transition">
                    <i class='bx bx-filter-alt mr-2 text-base'></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'status', 'date']))
                    <a href="{{ route('admin.appointments.by-month', ['year' => $year, 'month' => $month]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        <i class='bx bx-x mr-2 text-base'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->patient->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->doctor)
                                    <div class="text-sm text-gray-900">{{ $appointment->doctor->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->doctor->specialization ?? 'N/A' }}</div>
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($appointment->fee)
                                    {{ get_setting('currency', '$') }}{{ number_format($appointment->fee, 2) }}
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