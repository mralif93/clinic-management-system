@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user-circle text-blue-600 mr-3'></i>
                    User Details
                </h2>
                <p class="text-gray-600 mt-1">View and manage user information</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(!$user->trashed())
                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-md">
                        <i class='bx bx-edit mr-2'></i>
                        Edit User
                    </a>
                @else
                    <button onclick="restoreUser({{ $user->id }}, '{{ $user->name }}')" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class='bx bx-refresh mr-2'></i>
                        Restore User
                    </button>
                @endif
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-white border-opacity-30">
                <span class="text-white font-bold text-4xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-3xl font-bold mb-2">{{ $user->name }}</h3>
                <p class="text-blue-100 text-lg mb-4 flex items-center justify-center md:justify-start">
                    <i class='bx bx-envelope mr-2'></i>
                    {{ $user->email }}
                </p>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    @php
                        $roleConfig = [
                            'admin' => ['bg' => 'bg-purple-500', 'icon' => 'bx-shield-quarter'],
                            'doctor' => ['bg' => 'bg-green-500', 'icon' => 'bx-user-md'],
                            'staff' => ['bg' => 'bg-yellow-500', 'icon' => 'bx-user'],
                            'patient' => ['bg' => 'bg-blue-400', 'icon' => 'bx-user-circle'],
                        ];
                        $config = $roleConfig[$user->role] ?? $roleConfig['patient'];
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                        <i class='bx {{ $config['icon'] }} mr-2'></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->trashed())
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-trash mr-2'></i>
                            Deleted
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-check-circle mr-2'></i>
                            Active
                        </span>
                    @endif
                    @if($user->id === auth()->id())
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-400 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-user mr-2'></i>
                            Current User
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class='bx bx-info-circle text-blue-600 mr-2'></i>
                Basic Information
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Full Name</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Email Address</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Email Verification</label>
                    <p class="mt-1">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class='bx bx-check-circle mr-1'></i>
                                Verified on {{ $user->email_verified_at->format('M d, Y') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class='bx bx-time mr-1'></i>
                                Not Verified
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Role</label>
                    <p class="mt-1">
                        @php
                            $roleColors = [
                                'admin' => 'bg-purple-100 text-purple-800',
                                'patient' => 'bg-blue-100 text-blue-800',
                                'doctor' => 'bg-green-100 text-green-800',
                                'staff' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $roleColor }}">
                            <i class='bx {{ $config['icon'] }} mr-1.5'></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Security Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class='bx bx-shield-alt-2 text-blue-600 mr-2'></i>
                Security Information
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Account Status</label>
                    <p class="mt-1">
                        @if($user->isLocked())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class='bx bx-lock mr-1'></i>
                                Locked
                            </span>
                            <span class="ml-2 text-sm text-gray-600">
                                (Unlocks in {{ round($user->getRemainingLockoutMinutes()) }} minutes)
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class='bx bx-check-circle mr-1'></i>
                                Unlocked
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Failed Login Attempts</label>
                    <p class="mt-1">
                        <span class="text-2xl font-bold {{ $user->failed_login_attempts >= 3 ? 'text-red-600' : ($user->failed_login_attempts > 0 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $user->failed_login_attempts }}/5
                        </span>
                        @if($user->failed_login_attempts > 0)
                            <span class="text-sm text-gray-600 ml-2">
                                ({{ 5 - $user->failed_login_attempts }} remaining)
                            </span>
                        @endif
                    </p>
                </div>
                @if($user->locked_until)
                <div>
                    <label class="text-sm font-medium text-gray-500">Locked Until</label>
                    <p class="mt-1 text-gray-900 font-medium">
                        {{ $user->locked_until->format('M d, Y H:i:s') }}
                    </p>
                </div>
                @endif
                
                @if($user->isLocked() || $user->failed_login_attempts > 0)
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        @if($user->isLocked())
                            <button onclick="unlockUser({{ $user->id }}, '{{ $user->name }}')" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                                <i class='bx bx-lock-open mr-2'></i>
                                Unlock Account
                            </button>
                        @endif
                        @if($user->failed_login_attempts > 0)
                            <button onclick="resetAttempts({{ $user->id }}, '{{ $user->name }}')" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                <i class='bx bx-refresh mr-2'></i>
                                Reset Attempts
                            </button>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class='bx bx-time text-blue-600 mr-2'></i>
            Account Timestamps
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Created At</label>
                <p class="mt-1 text-gray-900 font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500">{{ $user->created_at->format('h:i A') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Last Updated</label>
                <p class="mt-1 text-gray-900 font-medium">{{ $user->updated_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500">{{ $user->updated_at->format('h:i A') }}</p>
            </div>
            @if($user->trashed())
            <div>
                <label class="text-sm font-medium text-gray-500">Deleted At</label>
                <p class="mt-1 text-gray-900 font-medium">{{ $user->deleted_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500">{{ $user->deleted_at->format('h:i A') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone (if not current user) -->
    @if($user->id !== auth()->id() && !$user->trashed())
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-4 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2'></i>
                Delete User
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-3">
            <i class='bx bx-info-circle mr-1'></i>
            Deleting a user will soft delete them. You can restore them later from the user list.
        </p>
    </div>
    @elseif($user->id !== auth()->id() && $user->trashed())
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-4 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="forceDeleteUser({{ $user->id }}, '{{ $user->name }}')" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2'></i>
                Permanently Delete
            </button>
        </div>
        <p class="text-sm text-red-600 mt-3">
            <i class='bx bx-error-circle mr-1'></i>
            <strong>Warning:</strong> This action cannot be undone! This will permanently delete the user from the system.
        </p>
    </div>
    @endif
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

    // Unlock User
    function unlockUser(id, name) {
        Swal.fire({
            title: 'Unlock Account?',
            html: `Are you sure you want to unlock <strong>${name}</strong>'s account?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Unlock',
            cancelButtonText: 'Cancel',
            width: '450px'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}/unlock`;
                
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

    // Reset Attempts
    function resetAttempts(id, name) {
        Swal.fire({
            title: 'Reset Login Attempts?',
            html: `Are you sure you want to reset failed login attempts for <strong>${name}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Reset',
            cancelButtonText: 'Cancel',
            width: '450px'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}/reset-attempts`;
                
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
</script>
@endpush
@endsection
