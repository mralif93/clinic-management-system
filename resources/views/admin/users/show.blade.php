@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-3xl border-2 border-white/30">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-blue-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-envelope'></i>
                            {{ $user->email }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @php
                                $roleConfig = [
                                    'admin' => ['icon' => 'bx-shield-quarter'],
                                    'doctor' => ['icon' => 'bx-plus-medical'],
                                    'staff' => ['icon' => 'bx-user'],
                                    'patient' => ['icon' => 'bx-user-circle'],
                                ];
                                $config = $roleConfig[$user->role] ?? $roleConfig['patient'];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                <i class='bx {{ $config['icon'] }} mr-1'></i> {{ ucfirst($user->role) }}
                            </span>
                            @if($user->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @endif
                            @if($user->id === auth()->id())
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-400/30">
                                    <i class='bx bx-user mr-1'></i> Current User
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$user->trashed())
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit User
                        </a>
                    @else
                        <button onclick="restoreUser({{ $user->id }}, '{{ $user->name }}')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                            <i class='bx bx-refresh'></i>
                            Restore User
                        </button>
                    @endif
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-blue-600'></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Full Name</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Email Address</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Email Verification</span>
                            @if($user->email_verified_at)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class='bx bx-check-circle mr-1'></i> Verified
                                    {{ $user->email_verified_at->format('M d, Y') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <i class='bx bx-time mr-1'></i> Not Verified
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">Role</span>
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                    'patient' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                    'doctor' => 'bg-green-50 text-green-700 border border-green-200',
                                    'staff' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                ];
                                $roleColor = $roleColors[$user->role] ?? 'bg-gray-50 text-gray-700 border border-gray-200';
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $roleColor }}">
                                <i class='bx {{ $config['icon'] }} mr-1'></i> {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-shield-alt-2 text-blue-600'></i>
                        Security Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Account Status</span>
                            @if($user->isLocked())
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                        <i class='bx bx-lock mr-1'></i> Locked
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Unlocks in
                                        {{ round($user->getRemainingLockoutMinutes()) }} min</p>
                                </div>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class='bx bx-check-circle mr-1'></i> Unlocked
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Failed Login Attempts</span>
                            <div class="text-right">
                                <span
                                    class="text-lg font-bold {{ $user->failed_login_attempts >= 3 ? 'text-red-600' : ($user->failed_login_attempts > 0 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $user->failed_login_attempts }}/5
                                </span>
                                @if($user->failed_login_attempts > 0)
                                    <p class="text-xs text-gray-500 mt-1">{{ 5 - $user->failed_login_attempts }} remaining</p>
                                @endif
                            </div>
                        </div>
                        @if($user->isLocked() || $user->failed_login_attempts > 0)
                            <div class="pt-4 flex flex-wrap gap-2">
                                @if($user->isLocked())
                                    <button onclick="unlockUser({{ $user->id }}, '{{ $user->name }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition-all text-sm">
                                        <i class='bx bx-lock-open'></i>
                                        Unlock Account
                                    </button>
                                @endif
                                @if($user->failed_login_attempts > 0)
                                    <button onclick="resetAttempts({{ $user->id }}, '{{ $user->name }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-all text-sm">
                                        <i class='bx bx-refresh'></i>
                                        Reset Attempts
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Timestamps -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-time text-blue-600'></i>
                    Account Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($user->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $user->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $user->deleted_at->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if($user->id !== auth()->id())
            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
                <div class="p-6 border-b border-red-100 bg-red-50/50">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                        <i class='bx bx-error-circle text-red-600'></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    @if(!$user->trashed())
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Delete this user</p>
                                <p class="text-sm text-gray-500">This will soft delete the user. You can restore them later.</p>
                            </div>
                            <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                                <i class='bx bx-trash'></i>
                                Delete User
                            </button>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-red-900">Permanently delete this user</p>
                                <p class="text-sm text-red-600"><strong>Warning:</strong> This action cannot be undone!</p>
                            </div>
                            <button onclick="forceDeleteUser({{ $user->id }}, '{{ $user->name }}')"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                                <i class='bx bx-trash'></i>
                                Permanently Delete
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
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

            function forceDeleteUser(id, name) {
                Swal.fire({
                    title: 'Permanently Delete?',
                    html: `<div class="text-left">
                    <p class="mb-3">Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${name}</strong>?</p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                        <p class="text-sm text-red-800"><i class='bx bx-error-circle mr-1.5'></i> <strong>Warning:</strong> This action cannot be undone!</p>
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