@extends('layouts.admin')

@section('title', 'Doctor Management')
@section('page-title', 'Doctor Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-plus-medical text-green-600 mr-3'></i>
                    Doctor Management
                </h1>
                <p class="text-sm text-gray-600 mt-2">Manage doctor profiles and information</p>
            </div>
            <a href="{{ route('admin.doctors.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class='bx bx-plus mr-2 text-base'></i>
                Add New Doctor
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.doctors.index') }}" class="space-y-4">
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
                           placeholder="Search by name, email, or specialization..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                </div>
                
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-user mr-1'></i> Type
                    </label>
                    <select id="type"
                            name="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">All Types</option>
                        <option value="psychology" {{ request('type') == 'psychology' ? 'selected' : '' }}>Psychology</option>
                        <option value="homeopathy" {{ request('type') == 'homeopathy' ? 'selected' : '' }}>Homeopathy</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-info-circle mr-1'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="active" {{ request('status') != 'deleted' ? 'selected' : '' }}>Active</option>
                        <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gray-700 text-white font-medium rounded-lg hover:bg-gray-800 transition">
                    <i class='bx bx-filter-alt mr-2 text-base'></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.doctors.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        <i class='bx bx-x mr-2 text-base'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
            
            <!-- Active Filters Indicator -->
            @if(request()->hasAny(['search', 'type', 'status']))
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class='bx bx-search mr-1'></i>
                                Search: "{{ request('search') }}"
                                <a href="{{ route('admin.doctors.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-blue-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('type'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class='bx bx-user mr-1'></i>
                                Type: {{ ucfirst(request('type')) }}
                                <a href="{{ route('admin.doctors.index', array_merge(request()->except('type'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-purple-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('status') == 'deleted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class='bx bx-trash mr-1'></i>
                                Deleted Records
                                <a href="{{ route('admin.doctors.index', array_merge(request()->except('status'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-red-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </form>
    </div>

    <!-- Doctors Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($doctors as $doctor)
                        <tr class="hover:bg-green-50 transition-colors duration-150 {{ $doctor->trashed() ? 'opacity-60' : '' }}">
                            <!-- Doctor Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</div>
                                        @if($doctor->qualification)
                                            <div class="text-xs text-gray-500">{{ $doctor->qualification }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $doctor->email }}</div>
                                <div class="text-xs text-gray-500">{{ $doctor->phone ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Specialization -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $doctor->specialization ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Type -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    {{ ucfirst($doctor->type) }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($doctor->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class='bx bx-trash mr-1.5'></i>
                                        Deleted
                                    </span>
                                    @if($doctor->deleted_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $doctor->deleted_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                @elseif($doctor->is_available)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class='bx bx-check-circle mr-1.5'></i>
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <i class='bx bx-time mr-1.5'></i>
                                        Unavailable
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $doctor->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $doctor->created_at->format('h:i A') }}</div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($doctor->trashed())
                                        <!-- Restore -->
                                        <button onclick="restoreDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                title="Restore Doctor">
                                            <i class='bx bx-undo text-base'></i>
                                        </button>
                                        <!-- Force Delete -->
                                        <button onclick="forceDeleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Permanently Delete">
                                            <i class='bx bx-x-circle text-base'></i>
                                        </button>
                                    @else
                                        <!-- View -->
                                        <a href="{{ route('admin.doctors.show', $doctor->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm"
                                           title="View Details">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm"
                                           title="Edit Doctor">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <!-- Delete -->
                                        <button onclick="deleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Delete Doctor">
                                            <i class='bx bx-trash-alt text-base'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class='bx bx-plus-medical text-6xl text-gray-300 mb-4'></i>
                                    <p class="text-gray-500 text-lg font-medium">No doctors found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new doctor</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($doctors->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Delete Doctor (Soft Delete)
    function deleteDoctor(id, name) {
        Swal.fire({
            title: 'Delete Doctor?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><br>This action will soft delete the doctor. You can restore them later.`,
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
                form.action = `/admin/doctors/${id}`;
                
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

    // Restore Doctor
    function restoreDoctor(id, name) {
        Swal.fire({
            title: 'Restore Doctor?',
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
                form.action = `/admin/doctors/${id}/restore`;
                
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

    // Force Delete Doctor
    function forceDeleteDoctor(id, name) {
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
                form.action = `/admin/doctors/${id}/force-delete`;
                
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
