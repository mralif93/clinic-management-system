@extends('layouts.admin')

@section('title', 'Package Management')
@section('page-title', 'Package Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-package text-2xl'></i>
                    </div>
                    Packages
                </h1>
                <p class="mt-2 text-purple-100">Manage special packages and offers</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.packages.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg shadow-purple-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Add New Package
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalPackages = $packages->total();
                $activePackages = \App\Models\Package::where('is_active', true)->count();
                $deletedPackages = \App\Models\Package::onlyTrashed()->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalPackages }}</p>
                <p class="text-sm text-purple-200">Total Packages</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $activePackages }}</p>
                <p class="text-sm text-purple-200">Active</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $deletedPackages }}</p>
                <p class="text-sm text-purple-200">Deleted</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalPackages - $activePackages }}</p>
                <p class="text-sm text-purple-200">Inactive</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Packages</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.packages.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or description..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-xl font-medium hover:bg-purple-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.packages.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                    @if(request('deleted') == '1')
                        <a href="{{ route('admin.packages.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Show Active
                        </a>
                    @else
                        <a href="{{ route('admin.packages.index', ['deleted' => '1']) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-100 text-red-700 rounded-xl font-medium hover:bg-red-200 transition-all text-sm">
                            <i class='bx bx-trash'></i>
                            Show Deleted
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $package)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all group {{ $package->trashed() ? 'opacity-60' : '' }}">
                <!-- Card Header -->
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class='bx bx-package text-2xl text-white'></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $package->name }}</h3>
                                @if($package->discount_percentage)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-700">
                                        {{ $package->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        @if($package->trashed())
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">Deleted</span>
                        @elseif($package->is_active)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    @if($package->description)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $package->description }}</p>
                    @endif
                    
                    <div class="space-y-2 mb-4">
                        @if($package->sessions)
                            <div class="flex items-center text-gray-600 text-sm">
                                <i class='bx bx-calendar-check text-purple-600 mr-2'></i>
                                <span>{{ $package->sessions }}</span>
                            </div>
                        @endif
                        @if($package->duration)
                            <div class="flex items-center text-gray-600 text-sm">
                                <i class='bx bx-time text-purple-600 mr-2'></i>
                                <span>{{ $package->duration }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-3">
                        @if($package->original_price)
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="text-sm text-gray-400 line-through">RM{{ number_format($package->original_price, 0) }}</span>
                            </div>
                        @endif
                        <p class="text-2xl font-bold text-purple-600">RM{{ number_format($package->price, 0) }}</p>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    @if($package->trashed())
                        <form action="{{ route('admin.packages.restore', $package->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Restore this package?')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                <i class='bx bx-undo text-lg'></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.packages.force-delete', $package->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Permanently delete this package? This cannot be undone!')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                <i class='bx bx-x-circle text-lg'></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.packages.show', $package->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                            <i class='bx bx-show text-lg'></i>
                        </a>
                        <a href="{{ route('admin.packages.edit', $package->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                            <i class='bx bx-edit text-lg'></i>
                        </a>
                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this package?')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                <i class='bx bx-trash text-lg'></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-package text-4xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No packages found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new package</p>
                    <a href="{{ route('admin.packages.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-xl font-medium hover:bg-purple-700 transition-all text-sm mt-4">
                        <i class='bx bx-plus'></i>
                        Add New Package
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($packages->hasPages())
        <div class="flex justify-center">
            {{ $packages->links() }}
        </div>
    @endif
</div>
@endsection
