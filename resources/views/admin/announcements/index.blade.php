@extends('layouts.admin')

@section('title', 'Announcement Management')
@section('page-title', 'Announcement Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-news text-2xl'></i>
                    </div>
                    News & Announcements
                </h1>
                <p class="mt-2 text-blue-100">Manage news and announcements for your homepage</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.announcements.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg shadow-blue-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Add New Announcement
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalAnnouncements = $announcements->total();
                $publishedAnnouncements = \App\Models\Announcement::where('is_published', true)->count();
                $featuredAnnouncements = \App\Models\Announcement::where('is_featured', true)->where('is_published', true)->count();
                $deletedAnnouncements = \App\Models\Announcement::onlyTrashed()->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalAnnouncements }}</p>
                <p class="text-sm text-blue-200">Total</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $publishedAnnouncements }}</p>
                <p class="text-sm text-blue-200">Published</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $featuredAnnouncements }}</p>
                <p class="text-sm text-blue-200">Featured</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $deletedAnnouncements }}</p>
                <p class="text-sm text-blue-200">Deleted</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Announcements</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.announcements.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by title or description..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-600 mb-2">Type</label>
                        <select id="type" name="type" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm bg-white">
                            <option value="">All Types</option>
                            <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>News</option>
                            <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'type', 'featured', 'expired']))
                        <a href="{{ route('admin.announcements.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                    @if(request('featured') == '1')
                        <a href="{{ route('admin.announcements.index', array_merge(request()->except('featured'), ['featured' => '0'])) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Show All
                        </a>
                    @else
                        <a href="{{ route('admin.announcements.index', array_merge(request()->all(), ['featured' => '1'])) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-100 text-yellow-700 rounded-xl font-medium hover:bg-yellow-200 transition-all text-sm">
                            <i class='bx bx-star'></i>
                            Show Featured Only
                        </a>
                    @endif
                    @if(request('deleted') == '1')
                        <a href="{{ route('admin.announcements.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Show Active
                        </a>
                    @else
                        <a href="{{ route('admin.announcements.index', array_merge(request()->all(), ['deleted' => '1'])) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-100 text-red-700 rounded-xl font-medium hover:bg-red-200 transition-all text-sm">
                            <i class='bx bx-trash'></i>
                            Show Deleted
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Announcements Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all group {{ $announcement->trashed() ? 'opacity-60' : '' }}">
                <!-- Image Preview -->
                @if($announcement->image)
                    <div class="h-48 bg-gray-100 overflow-hidden">
                        <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="h-48 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        <i class='bx bx-news text-6xl text-blue-300'></i>
                    </div>
                @endif

                <!-- Card Header -->
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $announcement->title }}</h3>
                                @if($announcement->subtitle)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $announcement->subtitle }}</p>
                                @endif
                                <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $announcement->type === 'news' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                                @if($announcement->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <i class='bx bx-star mr-1'></i> Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    @if($announcement->description)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ Str::limit($announcement->description, 120) }}</p>
                    @endif
                    
                    <div class="space-y-2 mb-4 text-xs text-gray-500">
                        <div class="flex items-center">
                            <i class='bx bx-calendar text-blue-600 mr-2'></i>
                            <span>Created: {{ $announcement->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($announcement->expires_at)
                            <div class="flex items-center {{ $announcement->isExpired() ? 'text-red-600' : '' }}">
                                <i class='bx bx-time-five text-blue-600 mr-2'></i>
                                <span>Expires: {{ $announcement->expires_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        @if($announcement->trashed())
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">Deleted</span>
                        @elseif($announcement->is_published)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">Published</span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">Draft</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if($announcement->trashed())
                            <form action="{{ route('admin.announcements.restore', $announcement->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Restore this announcement?')"
                                    class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                    <i class='bx bx-undo text-lg'></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.announcements.force-delete', $announcement->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Permanently delete this announcement? This cannot be undone!')"
                                    class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                    <i class='bx bx-x-circle text-lg'></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.announcements.show', $announcement->id) }}"
                               class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                <i class='bx bx-show text-lg'></i>
                            </a>
                            <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                               class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                <i class='bx bx-edit text-lg'></i>
                            </a>
                            <form action="{{ route('admin.announcements.toggle-publish', $announcement->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-full {{ $announcement->is_published ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600' }} hover:scale-110 transition-all" 
                                    title="{{ $announcement->is_published ? 'Unpublish' : 'Publish' }}">
                                    <i class='bx {{ $announcement->is_published ? 'bx-hide' : 'bx-show' }} text-lg'></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.announcements.toggle-featured', $announcement->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-full {{ $announcement->is_featured ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }} hover:scale-110 transition-all" 
                                    title="{{ $announcement->is_featured ? 'Unfeature' : 'Feature' }}">
                                    <i class='bx {{ $announcement->is_featured ? 'bx-star' : 'bx-star' }} text-lg'></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this announcement?')"
                                    class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                    <i class='bx bx-trash text-lg'></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-news text-4xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No announcements found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new announcement</p>
                    <a href="{{ route('admin.announcements.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-all text-sm mt-4">
                        <i class='bx bx-plus'></i>
                        Add New Announcement
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($announcements->hasPages())
        <div class="flex justify-center">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
