@extends('layouts.admin')

@section('title', 'Doctor Details')
@section('page-title', 'Doctor Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-3xl border-2 border-white/30">
                        {{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</h1>
                        <p class="text-green-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-envelope'></i>
                            {{ $doctor->email }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($doctor->doctor_id)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                    <i class='bx bx-id-card mr-1'></i> ID: {{ $doctor->doctor_id }}
                                </span>
                            @endif
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                <i class='bx bx-category mr-1'></i> {{ ucfirst($doctor->type) }}
                            </span>
                            @if($doctor->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($doctor->is_available)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Available
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-400/30">
                                    <i class='bx bx-time mr-1'></i> Unavailable
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$doctor->trashed())
                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit Doctor
                        </a>
                    @else
                        <button
                            onclick="restoreDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                            <i class='bx bx-refresh'></i>
                            Restore Doctor
                        </button>
                    @endif
                    <a href="{{ route('admin.doctors.index') }}"
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
                        <i class='bx bx-user text-green-600'></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Full Name</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Email Address</span>
                            <span class="text-sm font-medium text-gray-900">{{ $doctor->email }}</span>
                        </div>
                        @if($doctor->phone)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span class="text-sm font-medium text-gray-900">{{ $doctor->phone }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Type</span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                {{ ucfirst($doctor->type) }}
                            </span>
                        </div>
                        @if($doctor->specialization)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Specialization</span>
                                <span class="text-sm font-medium text-gray-900">{{ $doctor->specialization }}</span>
                            </div>
                        @endif
                        @if($doctor->qualification)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Qualification</span>
                                <span class="text-sm font-medium text-gray-900">{{ $doctor->qualification }}</span>
                            </div>
                        @endif
                        @if($doctor->user)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">User Account</span>
                                <a href="{{ route('admin.users.show', $doctor->user->id) }}"
                                    class="text-sm font-medium text-green-600 hover:text-green-700">
                                    {{ $doctor->user->email }} <i class='bx bx-link-external ml-1'></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-briefcase text-green-600'></i>
                        Professional Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Status</span>
                            @if($doctor->trashed())
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($doctor->is_available)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class='bx bx-check-circle mr-1'></i> Available
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <i class='bx bx-time mr-1'></i> Unavailable
                                </span>
                            @endif
                        </div>
                        @if($doctor->bio)
                            <div class="py-3">
                                <span class="text-sm text-gray-500 block mb-2">Biography</span>
                                <p class="text-sm text-gray-900 bg-gray-50 rounded-xl p-4">{{ $doctor->bio }}</p>
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
                    <i class='bx bx-time text-green-600'></i>
                    Account Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $doctor->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $doctor->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $doctor->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $doctor->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($doctor->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $doctor->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $doctor->deleted_at->format('h:i A') }}</p>
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
                @if(!$doctor->trashed())
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete this doctor</p>
                            <p class="text-sm text-gray-500">This will soft delete the doctor. You can restore them later.</p>
                        </div>
                        <button
                            onclick="deleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                            <i class='bx bx-trash'></i>
                            Delete Doctor
                        </button>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-red-900">Permanently delete this doctor</p>
                            <p class="text-sm text-red-600"><strong>Warning:</strong> This action cannot be undone!</p>
                        </div>
                        <button
                            onclick="forceDeleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')"
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

            function forceDeleteDoctor(id, name) {
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