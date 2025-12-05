@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bxs-user-account text-2xl'></i>
                    </div>
                    User Management
                </h1>
                <p class="mt-2 text-violet-100">Manage system users, roles, and permissions</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-violet-600 rounded-xl font-semibold hover:bg-violet-50 transition-all shadow-lg shadow-violet-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Add New User
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalUsers = $users->total();
                $adminCount = \App\Models\User::where('role', 'admin')->count();
                $doctorCount = \App\Models\User::where('role', 'doctor')->count();
                $patientCount = \App\Models\User::where('role', 'patient')->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                <p class="text-sm text-violet-200">Total Users</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $adminCount }}</p>
                <p class="text-sm text-violet-200">Admins</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $doctorCount }}</p>
                <p class="text-sm text-violet-200">Doctors</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $patientCount }}</p>
                <p class="text-sm text-violet-200">Patients</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Users</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or email..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Role Filter -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-600 mb-2">Role</label>
                        <select id="role" name="role" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all text-sm bg-white">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                            <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all text-sm bg-white">
                            <option value="active" {{ !in_array(request('status'), ['deleted', 'locked']) ? 'selected' : '' }}>Active</option>
                            <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 text-white rounded-xl font-medium hover:bg-violet-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                    
                    <!-- Active Filters Tags -->
                    @if(request()->hasAny(['search', 'role']))
                        <div class="flex flex-wrap items-center gap-2 ml-4">
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-medium">
                                    <i class='bx bx-search'></i>
                                    "{{ request('search') }}"
                                    <a href="{{ route('admin.users.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-1 hover:text-violet-900">
                                        <i class='bx bx-x'></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('role'))
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium">
                                    <i class='bx bx-user'></i>
                                    {{ ucfirst(request('role')) }}
                                    <a href="{{ route('admin.users.index', array_merge(request()->except('role'), ['page' => 1])) }}" class="ml-1 hover:text-indigo-900">
                                        <i class='bx bx-x'></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Security</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $user->trashed() ? 'bg-red-50/30' : '' }}">
                            <!-- User Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        @if($user->id === auth()->id())
                                            <span class="text-xs text-violet-600 font-medium">(You)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 text-xs text-green-600 mt-1">
                                        <i class='bx bx-check-circle'></i> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-amber-600 mt-1">
                                        <i class='bx bx-time'></i> Unverified
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Role -->
                            <td class="px-6 py-4">
                                @php
                                    $roleStyles = [
                                        'admin' => 'bg-slate-100 text-slate-700 ring-slate-500/20',
                                        'doctor' => 'bg-emerald-50 text-emerald-700 ring-emerald-500/20',
                                        'staff' => 'bg-amber-50 text-amber-700 ring-amber-500/20',
                                        'patient' => 'bg-blue-50 text-blue-700 ring-blue-500/20',
                                    ];
                                    $roleIcons = [
                                        'admin' => 'bx-shield-quarter',
                                        'doctor' => 'bx-plus-medical',
                                        'staff' => 'bx-id-card',
                                        'patient' => 'bx-user',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $roleStyles[$user->role] ?? $roleStyles['patient'] }}">
                                    <i class='bx {{ $roleIcons[$user->role] ?? 'bx-user' }}'></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($user->trashed())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-inset ring-red-500/20">
                                        <i class='bx bx-trash'></i> Deleted
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->deleted_at->format('M d, Y') }}</p>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-inset ring-green-500/20">
                                        <i class='bx bx-check-circle'></i> Active
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Security -->
                            <td class="px-6 py-4">
                                @if($user->isLocked())
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">
                                            <i class='bx bx-lock-alt'></i> Locked
                                        </span>
                                        <p class="text-xs text-gray-500">{{ round($user->getRemainingLockoutMinutes()) }}m left</p>
                                    </div>
                                @elseif($user->failed_login_attempts > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                        <i class='bx bx-error'></i> {{ $user->failed_login_attempts }}/5
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">
                                        <i class='bx bx-shield-check'></i> Secure
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Joined -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->format('h:i A') }}</p>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    @if($user->trashed())
                                        <button onclick="restoreUser({{ $user->id }}, '{{ $user->name }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                        <button onclick="forceDeleteUser({{ $user->id }}, '{{ $user->name }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                            <i class='bx bx-x-circle text-lg'></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class='bx bx-user-x text-4xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No users found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new user</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function deleteUser(id, name) {
        Swal.fire({
            title: 'Delete User?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><br><span class="text-gray-500 text-sm">This action will soft delete the user. You can restore them later.</span>`,
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
                form.action = `/admin/users/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function restoreUser(id, name) {
        Swal.fire({
            title: 'Restore User?',
            html: `Are you sure you want to restore <strong>${name}</strong>?`,
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
                form.action = `/admin/users/${id}/restore`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function forceDeleteUser(id, name) {
        Swal.fire({
            title: 'Permanently Delete?',
            html: `<div class="text-left">
                <p class="mb-3">Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${name}</strong>?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-700"><i class='bx bx-error-circle mr-1'></i><strong>Warning:</strong> This cannot be undone!</p>
                </div>
            </div>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-x-circle mr-1"></i> Delete Permanently',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}/force-delete`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
