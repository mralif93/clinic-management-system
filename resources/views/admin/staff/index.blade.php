@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page-title', 'Staff Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user text-yellow-600 mr-3'></i>
                    Staff Management
                </h1>
                <p class="text-sm text-gray-600 mt-2">Manage staff profiles and information</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class='bx bx-plus mr-2 text-base'></i>
                Add New Staff
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="space-y-4">
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
                           placeholder="Search by name, position, or department..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                </div>
                
                <!-- Department Filter -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-building mr-1'></i> Department
                    </label>
                    <input type="text" 
                           id="department"
                           name="department" 
                           value="{{ request('department') }}"
                           placeholder="Filter by department..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-info-circle mr-1'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
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
                @if(request()->hasAny(['search', 'department', 'status']))
                    <a href="{{ route('admin.staff.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        <i class='bx bx-x mr-2 text-base'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
            
            <!-- Active Filters Indicator -->
            @if(request()->hasAny(['search', 'department', 'status']))
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class='bx bx-search mr-1'></i>
                                Search: "{{ request('search') }}"
                                <a href="{{ route('admin.staff.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-blue-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('department'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class='bx bx-building mr-1'></i>
                                Department: {{ request('department') }}
                                <a href="{{ route('admin.staff.index', array_merge(request()->except('department'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-purple-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('status') == 'deleted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class='bx bx-trash mr-1'></i>
                                Deleted Records
                                <a href="{{ route('admin.staff.index', array_merge(request()->except('status'), ['page' => 1])) }}" 
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

    <!-- Staff Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staff as $staffMember)
                        <tr class="hover:bg-yellow-50 transition-colors duration-150 {{ $staffMember->trashed() ? 'opacity-60' : '' }}">
                            <!-- Staff Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($staffMember->first_name ?? 'S', 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name) }}</div>
                                        @if($staffMember->user)
                                            <div class="text-xs text-gray-500">{{ $staffMember->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $staffMember->phone ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Position -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $staffMember->position ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Department -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $staffMember->department ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($staffMember->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class='bx bx-trash mr-1.5'></i>
                                        Deleted
                                    </span>
                                    @if($staffMember->deleted_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $staffMember->deleted_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class='bx bx-check-circle mr-1.5'></i>
                                        Active
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $staffMember->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $staffMember->created_at->format('h:i A') }}</div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($staffMember->trashed())
                                        <!-- Restore -->
                                        <button onclick="restoreStaff({{ $staffMember->id }}, '{{ $staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name) }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                title="Restore Staff">
                                            <i class='bx bx-undo text-base'></i>
                                        </button>
                                        <!-- Force Delete -->
                                        <button onclick="forceDeleteStaff({{ $staffMember->id }}, '{{ $staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name) }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Permanently Delete">
                                            <i class='bx bx-x-circle text-base'></i>
                                        </button>
                                    @else
                                        <!-- View -->
                                        <a href="{{ route('admin.staff.show', $staffMember->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm"
                                           title="View Details">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.staff.edit', $staffMember->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm"
                                           title="Edit Staff">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <!-- Delete -->
                                        <button onclick="deleteStaff({{ $staffMember->id }}, '{{ $staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name) }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Delete Staff">
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
                                    <i class='bx bx-user-x text-6xl text-gray-300 mb-4'></i>
                                    <p class="text-gray-500 text-lg font-medium">No staff found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new staff member</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($staff->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $staff->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Delete Staff (Soft Delete)
    function deleteStaff(id, name) {
        Swal.fire({
            title: 'Delete Staff?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><br>This action will soft delete the staff. You can restore them later.`,
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
                form.action = `/admin/staff/${id}`;
                
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

    // Restore Staff
    function restoreStaff(id, name) {
        Swal.fire({
            title: 'Restore Staff?',
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
                form.action = `/admin/staff/${id}/restore`;
                
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

    // Force Delete Staff
    function forceDeleteStaff(id, name) {
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
                form.action = `/admin/staff/${id}/force-delete`;
                
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
