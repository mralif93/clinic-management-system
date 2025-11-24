@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user-circle text-blue-600 mr-3'></i>
                    User Management
                </h1>
                <p class="text-sm text-gray-600 mt-2">Manage system users, roles, and permissions</p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class='bx bx-plus mr-2 text-base'></i>
                Add New User
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
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
                           placeholder="Search by name or email..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                
                <!-- Role Filter -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-user mr-1'></i> Role
                    </label>
                    <select id="role"
                            name="role" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-info-circle mr-1'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="active" {{ !in_array(request('status'), ['deleted', 'locked']) ? 'selected' : '' }}>Active</option>
                        <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
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
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        <i class='bx bx-x mr-2 text-base'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
            
            <!-- Active Filters Indicator -->
            @if(request()->hasAny(['search', 'role', 'status']))
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class='bx bx-search mr-1'></i>
                                Search: "{{ request('search') }}"
                                <a href="{{ route('admin.users.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-blue-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('role'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class='bx bx-user mr-1'></i>
                                Role: {{ ucfirst(request('role')) }}
                                <a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-purple-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('status') && request('status') != 'active')
                            @php
                                $statusColor = request('status') == 'deleted' ? 'red' : 'yellow';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium @if(request('status') == 'deleted') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                <i class='bx bx-info-circle mr-1'></i>
                                Status: {{ ucfirst(request('status')) }}
                                <a href="{{ route('admin.users.index', array_merge(request()->except('status'), ['page' => 1])) }}" 
                                   class="ml-2 @if(request('status') == 'deleted') hover:text-red-600 @else hover:text-yellow-600 @endif">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Security
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition-colors duration-150 {{ $user->trashed() ? 'opacity-60' : '' }}">
                            <!-- User Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-base">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                        @if($user->id === auth()->id())
                                            <span class="text-xs text-blue-600 font-medium">(You)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <div class="text-xs text-green-600 flex items-center mt-1">
                                        <i class='bx bx-check-circle mr-1'></i> Verified
                                    </div>
                                @else
                                    <div class="text-xs text-yellow-600 flex items-center mt-1">
                                        <i class='bx bx-time mr-1'></i> Unverified
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Role -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleConfig = [
                                        'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'bx-shield-quarter'],
                                        'doctor' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'bx-plus-medical'],
                                        'staff' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'bx-user'],
                                        'patient' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'bx-user-circle'],
                                    ];
                                    $config = $roleConfig[$user->role] ?? $roleConfig['patient'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                    <i class='bx {{ $config['icon'] }} mr-1.5'></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class='bx bx-trash mr-1.5'></i>
                                        Deleted
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $user->deleted_at->format('M d, Y') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class='bx bx-check-circle mr-1.5'></i>
                                        Active
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Security -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->isLocked())
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                                            <i class='bx bx-lock mr-1'></i> Locked
                                        </span>
                                        <span class="text-xs text-gray-600">
                                            {{ round($user->getRemainingLockoutMinutes()) }} min left
                                        </span>
                                    </div>
                                @elseif($user->failed_login_attempts > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $user->failed_login_attempts }}/5 attempts
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                        <i class='bx bx-shield-check mr-1'></i> Secure
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->format('h:i A') }}</div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($user->trashed())
                                        <!-- Restore -->
                                        <button onclick="restoreUser({{ $user->id }}, '{{ $user->name }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                title="Restore User">
                                            <i class='bx bx-undo text-base'></i>
                                        </button>
                                        <!-- Force Delete -->
                                        <button onclick="forceDeleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Permanently Delete">
                                            <i class='bx bx-x-circle text-base'></i>
                                        </button>
                                    @else
                                        <!-- View -->
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm"
                                           title="View Details">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm"
                                           title="Edit User">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <!-- Delete -->
                                        @if($user->id !== auth()->id())
                                            <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                                    class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                    title="Delete User">
                                                <i class='bx bx-trash-alt text-base'></i>
                                            </button>
                                        @else
                                            <span class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 cursor-not-allowed rounded-full" title="Cannot delete your own account">
                                                <i class='bx bx-trash-alt text-base'></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class='bx bx-user-x text-6xl text-gray-300 mb-4'></i>
                                    <p class="text-gray-500 text-base font-medium">No users found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new user</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Delete User (Soft Delete)
    function deleteUser(id, name) {
        Swal.fire({
            title: 'Delete User?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><br>This action will soft delete the user. You can restore them later.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            width: '450px'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}`;
                
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

    // Restore User
    function restoreUser(id, name) {
        Swal.fire({
            title: 'Restore User?',
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
                // Show loading
                Swal.fire({
                    title: 'Restoring...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}/restore`;
                
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

    // Force Delete User
    function forceDeleteUser(id, name) {
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
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}/force-delete`;
                
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
