@extends('layouts.admin')

@section('title', 'Service Management')
@section('page-title', 'Service Management')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-category text-primary-600 text-2xl'></i>
                    Services
                </h1>
                <p class="text-sm text-gray-600">Manage clinic services and treatments</p>
            </div>
            <a href="{{ route('admin.services.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                <i class='bx bx-plus text-base'></i>
                Add New Service
            </a>
        </div>

        <!-- Filters Section -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.services.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-search'></i> Search
                        </label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or description..."
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-category'></i> Type
                        </label>
                        <select id="type" name="type"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Types</option>
                            <option value="psychology" {{ request('type') == 'psychology' ? 'selected' : '' }}>Psychology
                            </option>
                            <option value="homeopathy" {{ request('type') == 'homeopathy' ? 'selected' : '' }}>Homeopathy
                            </option>
                            <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-info-circle'></i> Status
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mt-4">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                        <i class='bx bx-filter-alt'></i>
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('admin.services.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                            <i class='bx bx-x'></i>
                            Clear Filters
                        </a>
                    @endif
                </div>

                <!-- Active Filters Indicator -->
                @if(request()->hasAny(['search', 'type', 'status']))
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                            @if(request('search'))
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                    <i class='bx bx-search mr-1'></i>
                                    Search: "{{ request('search') }}"
                                    <a href="{{ route('admin.services.index', array_merge(request()->except('search'), ['page' => 1])) }}"
                                        class="ml-2 hover:text-primary-700">
                                        <i class='bx bx-x'></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('type'))
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    <i class='bx bx-category mr-1'></i>
                                    Type: {{ ucfirst(request('type')) }}
                                    <a href="{{ route('admin.services.index', array_merge(request()->except('type'), ['page' => 1])) }}"
                                        class="ml-2 hover:text-gray-700">
                                        <i class='bx bx-x'></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('status'))
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-success-50 text-success-600">
                                    <i class='bx bx-info-circle mr-1'></i>
                                    Status: {{ ucfirst(request('status')) }}
                                    <a href="{{ route('admin.services.index', array_merge(request()->except('status'), ['page' => 1])) }}"
                                        class="ml-2 hover:text-success-600">
                                        <i class='bx bx-x'></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <!-- Services Table -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left tracking-wide">
                                Service</th>
                            <th class="px-6 py-3 text-left tracking-wide">Type
                            </th>
                            <th class="px-6 py-3 text-left tracking-wide">Price
                            </th>
                            <th class="px-6 py-3 text-left tracking-wide">
                                Duration</th>
                            <th class="px-6 py-3 text-left tracking-wide">
                                Status</th>
                            <th class="px-6 py-3 text-right tracking-wide">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                    @if($service->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($service->description, 60) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                        {{ ucfirst($service->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ get_setting('currency', '$') }}{{ number_format($service->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $service->duration_minutes }} min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->trashed())
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                            Deleted
                                        </span>
                                    @elseif($service->is_active)
                                        <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-success-50 text-success-600">
                                            Active
                                        </span>
                                    @else
                                        <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($service->trashed())
                                            <button onclick="restoreService({{ $service->id }}, '{{ addslashes($service->name) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 text-xs"
                                                title="Restore">
                                                <i class='bx bx-undo text-base'></i>
                                            </button>
                                            <button
                                                onclick="forceDeleteService({{ $service->id }}, '{{ addslashes($service->name) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                title="Permanently Delete">
                                                <i class='bx bx-x-circle text-base'></i>
                                            </button>
                                        @else
                                            <a href="{{ route('admin.services.show', $service->id) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 text-xs"
                                                title="View">
                                                <i class='bx bx-info-circle text-base'></i>
                                            </a>
                                            <a href="{{ route('admin.services.edit', $service->id) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-xs"
                                                title="Edit">
                                                <i class='bx bx-pencil text-base'></i>
                                            </a>
                                            <button onclick="deleteService({{ $service->id }}, '{{ addslashes($service->name) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                title="Delete">
                                                <i class='bx bx-trash text-base'></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No services found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteService(id, name) {
            Swal.fire({
                title: 'Delete Service?',
                html: `Are you sure you want to delete <strong>${name}</strong>?<br><br>This action will soft delete the service. You can restore it later.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
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
                confirmButtonText: 'Yes, Restore',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
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
                html: `Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${name}</strong>?<br><br>This action cannot be undone!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete Permanently',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
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