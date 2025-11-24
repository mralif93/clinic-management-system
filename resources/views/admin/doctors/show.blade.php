@extends('layouts.admin')

@section('title', 'Doctor Details')
@section('page-title', 'Doctor Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-plus-medical text-green-600 mr-3'></i>
                    Doctor Details
                </h2>
                <p class="text-sm text-gray-600 mt-1">View and manage doctor information</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(!$doctor->trashed())
                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" 
                       class="inline-flex items-center px-3 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class='bx bx-edit mr-2 text-base'></i>
                        Edit Doctor
                    </a>
                @else
                    <button onclick="restoreDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')" 
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class='bx bx-refresh mr-2 text-base'></i>
                        Restore Doctor
                    </button>
                @endif
                <a href="{{ route('admin.doctors.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2 text-base'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Doctor Profile Card -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-white border-opacity-30">
                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold mb-2">{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</h3>
                <p class="text-green-100 text-sm mb-5 flex items-center justify-center md:justify-start">
                    <i class='bx bx-envelope mr-2 text-base'></i>
                    {{ $doctor->email }}
                </p>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    @if($doctor->doctor_id)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-id-card mr-2 text-base'></i>
                            ID: {{ $doctor->doctor_id }}
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                        <i class='bx bx-category mr-2 text-base'></i>
                        {{ ucfirst($doctor->type) }}
                    </span>
                    @if($doctor->trashed())
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-trash mr-2 text-base'></i>
                            Deleted
                        </span>
                    @elseif($doctor->is_available)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-check-circle mr-2 text-base'></i>
                            Available
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-500 bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-time mr-2 text-base'></i>
                            Unavailable
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
                <i class='bx bx-info-circle text-green-600 mr-2 text-base'></i>
                Basic Information
            </h3>
            <div class="space-y-0 divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Full Name</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</p>
                </div>
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Email Address</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->email }}</p>
                </div>
                @if($doctor->phone)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->phone }}</p>
                </div>
                @endif
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Type</label>
                    <div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ ucfirst($doctor->type) }}
                        </span>
                    </div>
                </div>
                @if($doctor->specialization)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Specialization</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->specialization }}</p>
                </div>
                @endif
                @if($doctor->qualification)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Qualification</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->qualification }}</p>
                </div>
                @endif
                @if($doctor->user)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">User Account</label>
                    <div>
                        <a href="{{ route('admin.users.show', $doctor->user->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            {{ $doctor->user->email }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Professional Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                <i class='bx bx-briefcase text-green-600 mr-2 text-base'></i>
                Professional Information
            </h3>
            <div class="space-y-0 divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Status</label>
                    <div>
                        @if($doctor->trashed())
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                <i class='bx bx-trash mr-1.5 text-sm'></i>
                                Deleted
                            </span>
                        @elseif($doctor->is_available)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                <i class='bx bx-check-circle mr-1.5 text-sm'></i>
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <i class='bx bx-time mr-1.5 text-sm'></i>
                                Unavailable
                            </span>
                        @endif
                    </div>
                </div>
                @if($doctor->bio)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Bio</label>
                    <p class="text-gray-900 text-right max-w-xs">{{ $doctor->bio }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
            <i class='bx bx-time text-green-600 mr-2 text-base'></i>
            Account Timestamps
        </h3>
        <div class="space-y-0 divide-y divide-gray-100">
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Created At</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $doctor->created_at->format('h:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Last Updated</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->updated_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $doctor->updated_at->format('h:i A') }}</p>
                </div>
            </div>
            @if($doctor->trashed())
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Deleted At</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $doctor->deleted_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $doctor->deleted_at->format('h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone -->
    @if(!$doctor->trashed())
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-5 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2 text-base'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="deleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')" 
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2 text-base'></i>
                Delete Doctor
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-3">
            <i class='bx bx-info-circle mr-1.5 text-sm'></i>
            Deleting a doctor will soft delete them. You can restore them later from the doctor list.
        </p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-5 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2 text-base'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="forceDeleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}')" 
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2 text-base'></i>
                Permanently Delete
            </button>
        </div>
        <p class="text-sm text-red-600 mt-3">
            <i class='bx bx-error-circle mr-1.5 text-sm'></i>
            <strong>Warning:</strong> This action cannot be undone! This will permanently delete the doctor from the system.
        </p>
    </div>
    @endif
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
