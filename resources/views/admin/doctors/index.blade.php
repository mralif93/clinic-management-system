@extends('layouts.admin')

@section('title', 'Doctor Management')
@section('page-title', 'Doctor Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-plus-circle text-2xl'></i>
                    </div>
                    Doctor Management
                </h1>
                <p class="mt-2 text-green-100">Manage doctor profiles and information</p>
            </div>
            <a href="{{ route('admin.doctors.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg shadow-green-900/20">
                <i class='bx bx-plus text-xl'></i>
                Add New Doctor
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $doctors->total() }}</p>
                <p class="text-sm text-green-200">Total Doctors</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $doctors->where('is_available', true)->count() }}</p>
                <p class="text-sm text-green-200">Available</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $doctors->where('type', 'psychology')->count() }}</p>
                <p class="text-sm text-green-200">Psychology</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $doctors->where('type', 'general')->count() }}</p>
                <p class="text-sm text-green-200">General</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Doctors</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.doctors.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by name, email, or specialization..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-600 mb-2">Type</label>
                        <select id="type" name="type" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm bg-white">
                            <option value="">All Types</option>
                            <option value="psychology" {{ request('type') == 'psychology' ? 'selected' : '' }}>Psychology</option>
                            <option value="homeopathy" {{ request('type') == 'homeopathy' ? 'selected' : '' }}>Homeopathy</option>
                            <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm bg-white">
                            <option value="active" {{ request('status') != 'deleted' ? 'selected' : '' }}>Active</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('admin.doctors.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Doctors Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($doctors as $doctor)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $doctor->trashed() ? 'bg-red-50/30' : '' }}">
                            <!-- Doctor Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</p>
                                        @if($doctor->qualification)
                                            <p class="text-xs text-gray-500">{{ $doctor->qualification }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $doctor->email }}</p>
                                <p class="text-xs text-gray-500">{{ $doctor->phone ?? 'N/A' }}</p>
                            </td>
                            
                            <!-- Specialization -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $doctor->specialization ?? 'N/A' }}</p>
                            </td>
                            
                            <!-- Type -->
                            <td class="px-6 py-4">
                                @php
                                    $typeStyles = [
                                        'psychology' => 'bg-purple-50 text-purple-700',
                                        'homeopathy' => 'bg-cyan-50 text-cyan-700',
                                        'general' => 'bg-blue-50 text-blue-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $typeStyles[$doctor->type] ?? 'bg-gray-50 text-gray-700' }}">
                                    {{ ucfirst($doctor->type) }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($doctor->trashed())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-inset ring-red-500/20">
                                        <i class='bx bx-trash'></i> Deleted
                                    </span>
                                @elseif($doctor->is_available)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-inset ring-green-500/20">
                                        <i class='bx bx-check-circle'></i> Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-500/20">
                                        <i class='bx bx-time'></i> Unavailable
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    @if($doctor->trashed())
                                        <button onclick="restoreDoctor({{ $doctor->id }}, '{{ addslashes($doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name)) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                        <button onclick="forceDeleteDoctor({{ $doctor->id }}, '{{ addslashes($doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name)) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                            <i class='bx bx-x-circle text-lg'></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        <button onclick="deleteDoctor({{ $doctor->id }}, '{{ addslashes($doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name)) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class='bx bx-plus-circle text-4xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No doctors found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new doctor</p>
                                    <a href="{{ route('admin.doctors.create') }}" 
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition-all text-sm mt-4">
                                        <i class='bx bx-plus'></i>
                                        Add New Doctor
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($doctors->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function deleteDoctor(id, name) {
        Swal.fire({
            title: 'Delete Doctor?',
            html: `Are you sure you want to delete <strong>${name}</strong>?`,
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
                form.action = `/admin/doctors/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
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
            confirmButtonText: '<i class="bx bx-undo mr-1"></i> Restore',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Restoring...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/doctors/${id}/restore`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function forceDeleteDoctor(id, name) {
        Swal.fire({
            title: 'Permanently Delete?',
            html: `<div class="text-left">
                <p>Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${name}</strong>?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                    <p class="text-sm text-red-700"><i class='bx bx-error-circle mr-1'></i> This cannot be undone!</p>
                </div>
            </div>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete Permanently',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/doctors/${id}/force-delete`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection