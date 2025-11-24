@extends('layouts.admin')

@section('title', 'Staff Details')
@section('page-title', 'Staff Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user text-yellow-600 mr-3'></i>
                    Staff Details
                </h2>
                <p class="text-sm text-gray-600 mt-1">View and manage staff information</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(!$staff->trashed())
                    <a href="{{ route('admin.staff.edit', $staff->id) }}" 
                       class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white font-semibold text-sm rounded-lg hover:bg-yellow-700 transition shadow-md">
                        <i class='bx bx-edit mr-2 text-base'></i>
                        Edit Staff
                    </a>
                @else
                    <button onclick="restoreStaff({{ $staff->id }}, '{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}')" 
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class='bx bx-refresh mr-2 text-base'></i>
                        Restore Staff
                    </button>
                @endif
                <a href="{{ route('admin.staff.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2 text-base'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Staff Profile Card -->
    <div class="bg-gradient-to-r from-yellow-500 to-amber-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-white border-opacity-30">
                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($staff->first_name ?? 'S', 0, 1)) }}</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold mb-2">{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}</h3>
                @if($staff->user)
                    <p class="text-yellow-100 text-sm mb-5 flex items-center justify-center md:justify-start">
                        <i class='bx bx-envelope mr-2 text-base'></i>
                        {{ $staff->user->email }}
                    </p>
                @endif
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    @if($staff->staff_id)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-id-card mr-2 text-base'></i>
                            ID: {{ $staff->staff_id }}
                        </span>
                    @endif
                    @if($staff->position)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm">
                            <i class='bx bx-briefcase mr-2 text-base'></i>
                            {{ $staff->position }}
                        </span>
                    @endif
                    @if($staff->trashed())
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
                </div>
            </div>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                <i class='bx bx-info-circle text-yellow-600 mr-2 text-base'></i>
                Basic Information
            </h3>
            <div class="space-y-0 divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Full Name</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}</p>
                </div>
                @if($staff->user)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">User Account</label>
                    <div>
                        <a href="{{ route('admin.users.show', $staff->user->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            {{ $staff->user->email }}
                        </a>
                    </div>
                </div>
                @endif
                @if($staff->phone)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->phone }}</p>
                </div>
                @endif
                @if($staff->position)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Position</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->position }}</p>
                </div>
                @endif
                @if($staff->department)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Department</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->department }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Employment Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                <i class='bx bx-briefcase text-yellow-600 mr-2 text-base'></i>
                Employment Information
            </h3>
            <div class="space-y-0 divide-y divide-gray-100">
                @if($staff->hire_date)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Hire Date</label>
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->hire_date->format('M d, Y') }}</p>
                </div>
                @endif
                @if($staff->notes)
                <div class="flex items-center justify-between py-3.5">
                    <label class="text-sm font-medium text-gray-600">Notes</label>
                    <p class="text-gray-900 text-right max-w-xs whitespace-pre-wrap">{{ $staff->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
            <i class='bx bx-time text-yellow-600 mr-2 text-base'></i>
            Account Timestamps
        </h3>
        <div class="space-y-0 divide-y divide-gray-100">
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Created At</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $staff->created_at->format('h:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Last Updated</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->updated_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $staff->updated_at->format('h:i A') }}</p>
                </div>
            </div>
            @if($staff->trashed())
            <div class="flex items-center justify-between py-3.5">
                <label class="text-sm font-medium text-gray-600">Deleted At</label>
                <div class="text-right">
                    <p class="text-gray-900 font-semibold text-sm">{{ $staff->deleted_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600">{{ $staff->deleted_at->format('h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone -->
    @if(!$staff->trashed())
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-5 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2 text-base'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="deleteStaff({{ $staff->id }}, '{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}')" 
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2 text-base'></i>
                Delete Staff
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-3">
            <i class='bx bx-info-circle mr-1.5 text-sm'></i>
            Deleting a staff will soft delete them. You can restore them later from the staff list.
        </p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-5 flex items-center">
            <i class='bx bx-error-circle text-red-600 mr-2 text-base'></i>
            Danger Zone
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="forceDeleteStaff({{ $staff->id }}, '{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}')" 
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition">
                <i class='bx bx-trash mr-2 text-base'></i>
                Permanently Delete
            </button>
        </div>
        <p class="text-sm text-red-600 mt-3">
            <i class='bx bx-error-circle mr-1.5 text-sm'></i>
            <strong>Warning:</strong> This action cannot be undone! This will permanently delete the staff from the system.
        </p>
    </div>
    @endif
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
