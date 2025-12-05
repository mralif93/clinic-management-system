@extends('layouts.admin')

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-rose-600 to-pink-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-3xl border-2 border-white/30">
                        {{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</h1>
                        @if($patient->email)
                            <p class="text-rose-100 flex items-center gap-2 mt-1">
                                <i class='bx bx-envelope'></i>
                                {{ $patient->email }}
                            </p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($patient->patient_id)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                    <i class='bx bx-id-card mr-1'></i> ID: {{ $patient->patient_id }}
                                </span>
                            @endif
                            @if($patient->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @endif
                            @if($patient->date_of_birth)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                    <i class='bx bx-calendar mr-1'></i> {{ $patient->date_of_birth->age }} years old
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$patient->trashed())
                        <a href="{{ route('admin.patients.edit', $patient->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-rose-600 rounded-xl font-semibold hover:bg-rose-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit Patient
                        </a>
                    @else
                        <button
                            onclick="restorePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                            <i class='bx bx-refresh'></i>
                            Restore Patient
                        </button>
                    @endif
                    <a href="{{ route('admin.patients.index') }}"
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
                        <i class='bx bx-user text-rose-600'></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Full Name</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</span>
                        </div>
                        @if($patient->email)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Email Address</span>
                                <span class="text-sm font-medium text-gray-900">{{ $patient->email }}</span>
                            </div>
                        @endif
                        @if($patient->phone)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span class="text-sm font-medium text-gray-900">{{ $patient->phone }}</span>
                            </div>
                        @endif
                        @if($patient->gender)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Gender</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                    {{ ucfirst($patient->gender) }}
                                </span>
                            </div>
                        @endif
                        @if($patient->date_of_birth)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Date of Birth</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $patient->date_of_birth->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($patient->user)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">User Account</span>
                                <a href="{{ route('admin.users.show', $patient->user->id) }}"
                                    class="text-sm font-medium text-rose-600 hover:text-rose-700">
                                    {{ $patient->user->email }} <i class='bx bx-link-external ml-1'></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact & Medical Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-heart text-rose-600'></i>
                        Medical Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($patient->address)
                            <div class="py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500 block mb-2">Address</span>
                                <p class="text-sm text-gray-900 bg-gray-50 rounded-xl p-3">{{ $patient->address }}</p>
                            </div>
                        @endif
                        @if($patient->medical_history)
                            <div class="py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500 block mb-2">Medical History</span>
                                <p class="text-sm text-gray-900 bg-gray-50 rounded-xl p-3">{{ $patient->medical_history }}</p>
                            </div>
                        @endif
                        @if($patient->allergies)
                            <div class="py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500 block mb-2 flex items-center gap-2">
                                    Allergies
                                    <span
                                        class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg">Important</span>
                                </span>
                                <p class="text-sm text-gray-900 bg-red-50 rounded-xl p-3 border border-red-100">
                                    {{ $patient->allergies }}</p>
                            </div>
                        @endif
                        @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
                            <div class="py-3">
                                <span class="text-sm text-gray-500 block mb-2">Emergency Contact</span>
                                <div class="bg-amber-50 rounded-xl p-3 border border-amber-100">
                                    <p class="text-sm font-medium text-gray-900">{{ $patient->emergency_contact_name }}</p>
                                    @if($patient->emergency_contact_phone)
                                        <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                            <i class='bx bx-phone'></i> {{ $patient->emergency_contact_phone }}
                                        </p>
                                    @endif
                                </div>
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
                    <i class='bx bx-time text-rose-600'></i>
                    Account Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $patient->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $patient->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($patient->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $patient->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $patient->deleted_at->format('h:i A') }}</p>
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
                @if(!$patient->trashed())
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete this patient</p>
                            <p class="text-sm text-gray-500">This will soft delete the patient. You can restore them later.</p>
                        </div>
                        <button
                            onclick="deletePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                            <i class='bx bx-trash'></i>
                            Delete Patient
                        </button>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-red-900">Permanently delete this patient</p>
                            <p class="text-sm text-red-600"><strong>Warning:</strong> This action cannot be undone!</p>
                        </div>
                        <button
                            onclick="forceDeletePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')"
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

            function forceDeletePatient(id, name) {
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