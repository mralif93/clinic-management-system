@extends('layouts.admin')

@section('title', 'Service Management')
@section('page-title', 'Service Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-grid text-2xl'></i>
                    </div>
                    Services
                </h1>
                <p class="mt-2 text-cyan-100">Manage clinic services and treatments</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.services.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-cyan-600 rounded-xl font-semibold hover:bg-cyan-50 transition-all shadow-lg shadow-cyan-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Add New Service
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalServices = $services->total();
                $activeServices = \App\Models\Service::where('is_active', true)->count();
                $psychologyCount = \App\Models\Service::where('type', 'psychology')->count();
                $generalCount = \App\Models\Service::where('type', 'general')->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalServices }}</p>
                <p class="text-sm text-cyan-200">Total Services</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $activeServices }}</p>
                <p class="text-sm text-cyan-200">Active</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $psychologyCount }}</p>
                <p class="text-sm text-cyan-200">Psychology</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $generalCount }}</p>
                <p class="text-sm text-cyan-200">General</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Services</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.services.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or description..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-600 mb-2">Type</label>
                        <select id="type" name="type" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm bg-white">
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
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-600 text-white rounded-xl font-medium hover:bg-cyan-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('admin.services.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all group {{ $service->trashed() ? 'opacity-60' : '' }}">
                <!-- Card Header -->
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            @php
                                $typeColors = [
                                    'psychology' => 'from-purple-500 to-indigo-600',
                                    'homeopathy' => 'from-green-500 to-emerald-600',
                                    'general' => 'from-blue-500 to-cyan-600',
                                ];
                                $typeIcons = [
                                    'psychology' => 'bx-brain',
                                    'homeopathy' => 'bx-leaf',
                                    'general' => 'bx-plus-circle',
                                ];
                            @endphp
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $typeColors[$service->type] ?? 'from-gray-500 to-gray-600' }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class='bx {{ $typeIcons[$service->type] ?? 'bx-category' }} text-2xl text-white'></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 group-hover:text-cyan-600 transition-colors">{{ $service->name }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ ucfirst($service->type) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        @if($service->trashed())
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">Deleted</span>
                        @elseif($service->is_active)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    @if($service->description)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $service->description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($service->price, 2) }}</p>
                            <p class="text-xs text-gray-500">Price</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-gray-900">{{ $service->duration_minutes }}</p>
                            <p class="text-xs text-gray-500">Minutes</p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    @if($service->trashed())
                        <button onclick="restoreService({{ $service->id }}, '{{ addslashes($service->name) }}')"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                            <i class='bx bx-undo text-lg'></i>
                        </button>
                        <button onclick="forceDeleteService({{ $service->id }}, '{{ addslashes($service->name) }}')"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                            <i class='bx bx-x-circle text-lg'></i>
                        </button>
                    @else
                        <a href="{{ route('admin.services.show', $service->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                            <i class='bx bx-show text-lg'></i>
                        </a>
                        <a href="{{ route('admin.services.edit', $service->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                            <i class='bx bx-edit text-lg'></i>
                        </a>
                        <button onclick="deleteService({{ $service->id }}, '{{ addslashes($service->name) }}')"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                            <i class='bx bx-trash text-lg'></i>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-grid text-4xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No services found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new service</p>
                    <a href="{{ route('admin.services.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-600 text-white rounded-xl font-medium hover:bg-cyan-700 transition-all text-sm mt-4">
                        <i class='bx bx-plus'></i>
                        Add New Service
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($services->hasPages())
        <div class="flex justify-center">
            {{ $services->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function deleteService(id, name) {
        Swal.fire({
            title: 'Delete Service?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br><br><span class="text-gray-500 text-sm">You can restore it later.</span>`,
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
                form.action = `/admin/services/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function restoreService(id, name) {
        Swal.fire({
            title: 'Restore Service?',
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
                form.action = `/admin/services/${id}/restore`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function forceDeleteService(id, name) {
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
                form.action = `/admin/services/${id}/force-delete`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection