@extends('layouts.admin')

@section('title', 'Service Management')
@section('page-title', 'Service Management')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Services</h1>
            <p class="text-sm text-gray-600 mt-1">Manage clinic services and treatments</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="bg-blue-600 text-white px-3 py-2rounded-lg hover:bg-blue-700 transition flex items-center">
            <i class='bx bx-plus mr-2 text-base'></i>
            Add New Service
        </a>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.services.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-search mr-1'></i> Search
                    </label>
                    <input type="text" 
                           id="search"
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name or description..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-category mr-1'></i> Type
                    </label>
                    <select id="type"
                            name="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">All Types</option>
                        <option value="psychology" {{ request('type') == 'psychology' ? 'selected' : '' }}>Psychology</option>
                        <option value="homeopathy" {{ request('type') == 'homeopathy' ? 'selected' : '' }}>Homeopathy</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-info-circle mr-1'></i> Status
                    </label>
                    <select id="status"
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gray-700 text-white font-medium rounded-lg hover:bg-gray-800 transition">
                    <i class='bx bx-filter-alt mr-2 text-base'></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.services.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                        <i class='bx bx-x mr-2 text-base'></i>
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class='bx bx-search mr-1'></i>
                                Search: "{{ request('search') }}"
                                <a href="{{ route('admin.services.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-blue-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('type'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class='bx bx-category mr-1'></i>
                                Type: {{ ucfirst(request('type')) }}
                                <a href="{{ route('admin.services.index', array_merge(request()->except('type'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-purple-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class='bx bx-info-circle mr-1'></i>
                                Status: {{ ucfirst(request('status')) }}
                                <a href="{{ route('admin.services.index', array_merge(request()->except('status'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-green-600">
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
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Deleted
                                    </span>
                                @elseif($service->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($service->trashed())
                                        <form action="{{ route('admin.services.restore', $service->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm" title="Restore">
                                                <i class='bx bx-undo text-base'></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.services.force-delete', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this service? This action cannot be undone!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm" title="Permanently Delete">
                                                <i class='bx bx-x-circle text-base'></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.services.show', $service->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm" title="View">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm" title="Edit">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm" title="Delete">
                                                <i class='bx bx-trash-alt text-base'></i>
                                            </button>
                                        </form>
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

