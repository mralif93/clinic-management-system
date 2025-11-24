@extends('layouts.admin')

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user text-blue-600 mr-3'></i>
                    Patient Details
                </h2>
                <p class="text-sm text-gray-600 mt-1">View and manage patient information</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(!$patient->trashed())
                    <a href="{{ route('admin.patients.edit', $patient->id) }}" 
                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition shadow-md">
                        <i class='bx bx-edit mr-2 text-base'></i>
                        Edit Patient
                    </a>
                @else
                    <button onclick="restorePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')" 
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class='bx bx-refresh mr-2 text-base'></i>
                        Restore Patient
                    </button>
                @endif
                <a href="{{ route('admin.patients.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2 text-base'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Patient Profile Card -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-white border-opacity-30">
                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold mb-2">{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</h3>
                @if($patient->email)
                    <p class="text-blue-100 text-sm mb-5 flex items-center justify-center md:justify-start">
                        <i class='bx bx-envelope mr-2 text-base'></i>
                        {{ $patient->email }}
                    </p>
                @endif
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    @if($patient->patient_id)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-id-card mr-2 text-base'></i>
                            ID: {{ $patient->patient_id }}
                        </span>
                    @endif
                    @if($patient->trashed())
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-trash mr-2 text-base'></i>
                            Deleted
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-check-circle mr-2 text-base'></i>
                            Active
                        </span>
                    @endif
                    @if($patient->date_of_birth)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-calendar mr-2 text-base'></i>
                            {{ $patient->date_of_birth->age }} years old
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
            <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                <i class='bx bx-info-circle text-blue-600 mr-2 text-base'></i>
                Basic Information
            </h3>
            <div class="space-y-0 divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Full Name</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</p>
                </div>
                @if($patient->email)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Email Address</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->email }}</p>
                </div>
                @endif
                @if($patient->phone)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->phone }}</p>
                </div>
                @endif
                @if($patient->gender)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Gender</label>
                    <div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-50 text-gray-700 border border-gray-200">
                            {{ ucfirst($patient->gender) }}
                        </span>
                    </div>
                </div>
                @endif
                @if($patient->date_of_birth)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->date_of_birth->format('M d, Y') }}</p>
                </div>
                @endif
                @if($patient->user)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">User Account</label>
                    <div>
                        <a href="{{ route('admin.users.show', $patient->user->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            {{ $patient->user->email }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact & Medical Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                <i class='bx bx-phone text-blue-600 mr-2 text-base'></i>
                Contact & Medical Information
            </h3>
            <div class="space-y-0 divide-y divide-gray-100">
                @if($patient->address)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Address</label>
                    <p class="text-gray-900 text-right max-w-xs">{{ $patient->address }}</p>
                </div>
                @endif
                @if($patient->medical_history)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Medical History</label>
                    <p class="text-gray-900 text-right max-w-xs">{{ $patient->medical_history }}</p>
                </div>
                @endif
                @if($patient->allergies)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Allergies</label>
                    <p class="text-gray-900 text-right max-w-xs">{{ $patient->allergies }}</p>
                </div>
                @endif
                @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Emergency Contact</label>
                    <p class="text-gray-900 text-right">
                        @if($patient->emergency_contact_name){{ $patient->emergency_contact_name }}@endif
                        @if($patient->emergency_contact_phone) - {{ $patient->emergency_contact_phone }}@endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
            <i class='bx bx-time text-blue-600 mr-2 text-base'></i>
            Account Timestamps
        </h3>
        <div class="space-y-0 divide-y divide-gray-100">
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Created At</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $patient->created_at->format('h:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Last Updated</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->updated_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $patient->updated_at->format('h:i A') }}</p>
                </div>
            </div>
            @if($patient->trashed())
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Deleted At</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $patient->deleted_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $patient->deleted_at->format('h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone -->
    @if(!$patient->trashed())
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-5 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2 text-base'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="deletePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')" 
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2 text-base'></i>
                Delete Patient
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-3">
            <i class='bx bx-info-circle mr-1.5 text-sm'></i>
            Deleting a patient will soft delete them. You can restore them later from the patient list.
        </p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-5 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2 text-base'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="forceDeletePatient({{ $patient->id }}, '{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}')" 
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2 text-base'></i>
                Permanently Delete
            </button>
        </div>
        <p class="text-sm text-red-600 mt-3">
            <i class='bx bx-error-circle mr-1.5 text-sm'></i>
            <strong>Warning:</strong> This action cannot be undone! This will permanently delete the patient from the system.
        </p>
    </div>
    @endif
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
                    <p class="text-sm text-red-800"><i class='bx bx-error-circle mr-1.5 text-sm'></i> <strong>Warning:</strong> This action cannot be undone!</p>
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
