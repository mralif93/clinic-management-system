@extends('layouts.admin')

@section('title', 'Staff Details')
@section('page-title', 'Staff Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-3xl border-2 border-white/30">
                        {{ strtoupper(substr($staff->first_name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}</h1>
                        @if($staff->user)
                            <p class="text-cyan-100 flex items-center gap-2 mt-1">
                                <i class='bx bx-envelope'></i>
                                {{ $staff->user->email }}
                            </p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($staff->staff_id)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                    <i class='bx bx-id-card mr-1'></i> ID: {{ $staff->staff_id }}
                                </span>
                            @endif
                            @if($staff->position)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                    <i class='bx bx-briefcase mr-1'></i> {{ $staff->position }}
                                </span>
                            @endif
                            @if($staff->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$staff->trashed())
                        <a href="{{ route('admin.staff.edit', $staff->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-cyan-600 rounded-xl font-semibold hover:bg-cyan-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit Staff
                        </a>
                    @else
                        <button
                            onclick="restoreStaff({{ $staff->id }}, '{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                            <i class='bx bx-refresh'></i>
                            Restore Staff
                        </button>
                    @endif
                    <a href="{{ route('admin.staff.index') }}"
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
                        <i class='bx bx-user text-cyan-600'></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Full Name</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}</span>
                        </div>
                        @if($staff->user)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">User Account</span>
                                <a href="{{ route('admin.users.show', $staff->user->id) }}"
                                    class="text-sm font-medium text-cyan-600 hover:text-cyan-700">
                                    {{ $staff->user->email }} <i class='bx bx-link-external ml-1'></i>
                                </a>
                            </div>
                        @endif
                        @if($staff->phone)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span class="text-sm font-medium text-gray-900">{{ $staff->phone }}</span>
                            </div>
                        @endif
                        @if($staff->position)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Position</span>
                                <span class="text-sm font-medium text-gray-900">{{ $staff->position }}</span>
                            </div>
                        @endif
                        @if($staff->department)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">Department</span>
                                <span class="text-sm font-medium text-gray-900">{{ $staff->department }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-briefcase text-cyan-600'></i>
                        Employment Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($staff->hire_date)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Hire Date</span>
                                <span class="text-sm font-medium text-gray-900">{{ $staff->hire_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($staff->employment_type)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Employment Type</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $staff->employment_type === 'full_time' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                    {{ $staff->employment_type === 'full_time' ? 'Full Time' : 'Part Time' }}
                                </span>
                            </div>
                        @endif
                        @if($staff->basic_salary)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Basic Salary</span>
                                <span class="text-sm font-semibold text-gray-900">RM
                                    {{ number_format($staff->basic_salary, 2) }}</span>
                            </div>
                        @endif
                        @if($staff->hourly_rate)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Hourly Rate</span>
                                <span class="text-sm font-semibold text-gray-900">RM
                                    {{ number_format($staff->hourly_rate, 2) }}/hr</span>
                            </div>
                        @endif
                        @if($staff->notes)
                            <div class="py-3">
                                <span class="text-sm text-gray-500 block mb-2">Notes</span>
                                <p class="text-sm text-gray-900 bg-gray-50 rounded-xl p-3">{{ $staff->notes }}</p>
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
                    <i class='bx bx-time text-cyan-600'></i>
                    Account Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $staff->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $staff->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $staff->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $staff->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($staff->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $staff->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $staff->deleted_at->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
            <div class="p-6 border-b border-red-100 bg-red-50/50">
                <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                    <i class='bx bx-error-circle text-red-600'></i>
                    Danger Zone
                </h3>
            </div>
            <div class="p-6">
                @if(!$staff->trashed())
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete this staff member</p>
                            <p class="text-sm text-gray-500">This will soft delete the staff. You can restore them later.</p>
                        </div>
                        <button
                            onclick="deleteStaff({{ $staff->id }}, '{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}')"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                            <i class='bx bx-trash'></i>
                            Delete Staff
                        </button>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-red-900">Permanently delete this staff member</p>
                            <p class="text-sm text-red-600"><strong>Warning:</strong> This action cannot be undone!</p>
                        </div>
                        <button
                            onclick="forceDeleteStaff({{ $staff->id }}, '{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}')"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                            <i class='bx bx-trash'></i>
                            Permanently Delete
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
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

            function forceDeleteStaff(id, name) {
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