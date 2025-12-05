@extends('layouts.admin')

@section('title', 'Patient Management')
@section('page-title', 'Patient Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-user text-primary-600 text-2xl'></i>
                    Patient Management
                </h1>
                <p class="text-sm text-gray-600">Manage patient records and information</p>
            </div>
            <a href="{{ route('admin.patients.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                <i class='bx bx-plus text-base'></i>
                Add New Patient
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.patients.index') }}" class="space-y-4">
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
                           placeholder="Search by name, email, or phone..."
                           class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                </div>
                
                <!-- Gender Filter -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-user'></i> Gender
                    </label>
                    <select id="gender"
                            name="gender" 
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-info-circle'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                        <option value="active" {{ request('status') != 'deleted' ? 'selected' : '' }}>Active</option>
                        <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-4">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-filter-alt'></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'gender', 'status']))
                    <a href="{{ route('admin.patients.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                        <i class='bx bx-x'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
            
            <!-- Active Filters Indicator -->
            @if(request()->hasAny(['search', 'gender', 'status']))
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                <i class='bx bx-search mr-1'></i>
                                Search: "{{ request('search') }}"
                                <a href="{{ route('admin.patients.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-primary-700">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('gender'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                <i class='bx bx-user mr-1'></i>
                                Gender: {{ ucfirst(request('gender')) }}
                                <a href="{{ route('admin.patients.index', array_merge(request()->except('gender'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-gray-700">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('status') == 'deleted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                <i class='bx bx-trash mr-1'></i>
                                Deleted Records
                                <a href="{{ route('admin.patients.index', array_merge(request()->except('status'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-red-700">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </form>
    </div>

    <!-- Patients Table -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left tracking-wide">Patient</th>
                        <th class="px-6 py-3 text-left tracking-wide">Contact</th>
                        <th class="px-6 py-3 text-left tracking-wide">Gender</th>
                        <th class="px-6 py-3 text-left tracking-wide">Status</th>
                        <th class="px-6 py-3 text-left tracking-wide">Created</th>
                        <th class="px-6 py-3 text-right tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $patient->trashed() ? 'opacity-60' : '' }}">
                            <!-- Patient Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-sm">
                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</div>
                                        @if($patient->date_of_birth)
                                            <div class="text-xs text-gray-500">{{ $patient->date_of_birth->age }} years old</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $patient->email ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->phone ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Gender -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($patient->gender)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ ucfirst($patient->gender) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($patient->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                        <i class='bx bx-trash mr-1.5'></i>
                                        Deleted
                                    </span>
                                    @if($patient->deleted_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $patient->deleted_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-success-50 text-success-600">
                                        <i class='bx bx-check-circle mr-1.5'></i>
                                        Active
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $patient->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->created_at->format('h:i A') }}</div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($patient->trashed())
                                        <!-- Restore -->
                                        <button onclick="restorePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 text-xs"
                                                title="Restore Patient">
                                            <i class='bx bx-undo text-base'></i>
                                        </button>
                                        <!-- Force Delete -->
                                        <button onclick="forceDeletePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                title="Permanently Delete">
                                            <i class='bx bx-x-circle text-base'></i>
                                        </button>
                                    @else
                                        <!-- View -->
                                        <a href="{{ route('admin.patients.show', $patient->id) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 text-xs"
                                           title="View Details">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.patients.edit', $patient->id) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-xs"
                                           title="Edit Patient">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <!-- Delete -->
                                        <button onclick="deletePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                title="Delete Patient">
                                            <i class='bx bx-trash text-base'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class='bx bx-user-x text-6xl text-gray-300 mb-4'></i>
                                    <p class="text-gray-500 text-lg font-medium">No patients found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new patient</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Delete Patient (Soft Delete)
    function deletePatient(id, name) {
        Swal.fire({
            title: 'Delete Patient?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><br>This action will soft delete the patient. You can restore them later.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            width: '450px'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/patients/${id}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Restore Patient
    function restorePatient(id, name) {
        Swal.fire({
            title: 'Restore Patient?',
            html: `Are you sure you want to restore <strong>${name}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Restore',
            cancelButtonText: 'Cancel',
            width: '450px'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Restoring...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/patients/${id}/restore`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Force Delete Patient
    function forceDeletePatient(id, name) {
        Swal.fire({
            title: 'Permanently Delete?',
            html: `<div class="text-left">
                <p class="mb-3">Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${name}</strong>?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                    <p class="text-sm text-red-800"><i class='bx bx-error-circle mr-1'></i> <strong>Warning:</strong> This action cannot be undone!</p>
                </div>
            </div>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete Permanently',
            cancelButtonText: 'Cancel',
            width: '500px'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/patients/${id}/force-delete`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
