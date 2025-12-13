@extends('layouts.admin')

@section('title', 'Pages Management')
@section('page-title', 'Pages Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-file text-2xl'></i>
                    </div>
                    Pages
                </h1>
                <p class="mt-2 text-indigo-100">Manage website pages and content</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pages.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Create New Page
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalPages = $pages->total();
                $publishedPages = \App\Models\Page::where('is_published', true)->count();
                $draftPages = \App\Models\Page::where('is_published', false)->count();
                $customPages = \App\Models\Page::where('type', 'custom')->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalPages }}</p>
                <p class="text-sm text-indigo-200">Total Pages</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $publishedPages }}</p>
                <p class="text-sm text-indigo-200">Published</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $draftPages }}</p>
                <p class="text-sm text-indigo-200">Draft</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $customPages }}</p>
                <p class="text-sm text-indigo-200">Custom</p>
            </div>
        </div>
    </div>

    <!-- Module Visibility Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-toggle-left text-indigo-600'></i>
                <h3 class="font-semibold text-gray-700">Module Visibility Control</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Control which modules appear on the public website</p>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $modulePages = [
                        'services' => [
                            'name' => 'Services',
                            'icon' => 'bx-grid-alt',
                            'color' => 'cyan',
                            'route' => 'services.index',
                            'admin_route' => 'admin.services.index',
                        ],
                        'packages' => [
                            'name' => 'Packages',
                            'icon' => 'bx-package',
                            'color' => 'purple',
                            'route' => 'packages.index',
                            'admin_route' => 'admin.packages.index',
                        ],
                        'team' => [
                            'name' => 'Team',
                            'icon' => 'bx-group',
                            'color' => 'indigo',
                            'route' => 'team.index',
                            'admin_route' => 'admin.team.index',
                        ],
                        'about' => [
                            'name' => 'About',
                            'icon' => 'bx-info-circle',
                            'color' => 'blue',
                            'route' => 'about',
                            'admin_route' => 'admin.pages.about',
                        ],
                    ];
                    
                    foreach ($modulePages as $type => $config) {
                        $page = \App\Models\Page::where('type', $type)->first();
                        $isPublished = $page ? $page->is_published : false;
                        $modulePages[$type]['page'] = $page;
                        $modulePages[$type]['is_published'] = $isPublished;
                    }
                @endphp

                @foreach($modulePages as $type => $config)
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition {{ $config['is_published'] ? 'bg-green-50/50 border-green-200' : 'bg-gray-50/50' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-{{ $config['color'] }}-100 flex items-center justify-center">
                                    <i class='bx {{ $config['icon'] }} text-xl text-{{ $config['color'] }}-600'></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $config['name'] }}</h4>
                                    <p class="text-xs text-gray-500">Module</p>
                                </div>
                            </div>
                            @if($config['is_published'])
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                    <i class='bx bx-check-circle'></i> Visible
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                                    <i class='bx bx-x-circle'></i> Hidden
                                </span>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            @if($config['page'])
                                <form action="{{ route('admin.pages.toggle-status', $config['page']->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full px-3 py-2 rounded-lg text-sm font-medium transition-all
                                        {{ $config['is_published'] ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        <i class='bx {{ $config['is_published'] ? 'bx-hide' : 'bx-show' }} mr-1'></i>
                                        {{ $config['is_published'] ? 'Hide' : 'Show' }} Module
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-gray-500 text-center py-2">Page not created yet</p>
                            @endif
                            
                            <div class="flex gap-2">
                                @if($type !== 'about')
                                    <a href="{{ route($config['admin_route']) }}" 
                                       class="flex-1 px-3 py-2 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-700 rounded-lg text-sm font-medium hover:bg-{{ $config['color'] }}-200 transition text-center">
                                        <i class='bx bx-cog mr-1'></i> Manage
                                    </a>
                                @else
                                    <a href="{{ route($config['admin_route']) }}" 
                                       class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition text-center">
                                        <i class='bx bx-edit mr-1'></i> Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Pages</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.pages.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by title or slug..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-600 mb-2">Type</label>
                        <select id="type" name="type" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm bg-white">
                            <option value="">All Types</option>
                            <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                            <option value="about" {{ request('type') == 'about' ? 'selected' : '' }}>About</option>
                            <option value="team" {{ request('type') == 'team' ? 'selected' : '' }}>Team</option>
                            <option value="packages" {{ request('type') == 'packages' ? 'selected' : '' }}>Packages</option>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('admin.pages.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50 transition-colors {{ $page->trashed() ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $page->title }}</div>
                                        <div class="text-sm text-gray-500">/{{ $page->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'custom' => 'bg-purple-100 text-purple-700',
                                        'about' => 'bg-blue-100 text-blue-700',
                                        'team' => 'bg-indigo-100 text-indigo-700',
                                        'packages' => 'bg-purple-100 text-purple-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $typeColors[$page->type] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($page->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($page->trashed())
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">Deleted</span>
                                @elseif($page->is_published)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">Published</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $page->order }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $page->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if($page->trashed())
                                        <button onclick="restorePage({{ $page->id }}, '{{ addslashes($page->title) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                        <button onclick="forceDeletePage({{ $page->id }}, '{{ addslashes($page->title) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                            <i class='bx bx-x-circle text-lg'></i>
                                        </button>
                                    @else
                                        @if($page->type === 'about')
                                            <a href="{{ route('admin.pages.about') }}"
                                               class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="Edit">
                                                <i class='bx bx-edit text-lg'></i>
                                            </a>
                                        @elseif(in_array($page->type, ['team', 'packages', 'services']))
                                            @php
                                                $moduleRoutes = [
                                                    'team' => 'admin.team.index',
                                                    'packages' => 'admin.packages.index',
                                                    'services' => 'admin.services.index',
                                                ];
                                            @endphp
                                            <a href="{{ route($moduleRoutes[$page->type]) }}"
                                               class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="Manage Module">
                                                <i class='bx bx-cog text-lg'></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.pages.show', $page->id) }}"
                                               class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                                <i class='bx bx-show text-lg'></i>
                                            </a>
                                            <a href="{{ route('admin.pages.edit', $page->id) }}"
                                               class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                                <i class='bx bx-edit text-lg'></i>
                                            </a>
                                        @endif
                                        <button onclick="togglePageStatus({{ $page->id }}, '{{ addslashes($page->title) }}', {{ $page->is_published ? 'false' : 'true' }})"
                                            class="w-9 h-9 flex items-center justify-center rounded-full {{ $page->is_published ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600' }} hover:scale-110 transition-all" 
                                            title="{{ $page->is_published ? 'Unpublish' : 'Publish' }}">
                                            <i class='bx {{ $page->is_published ? 'bx-hide' : 'bx-show' }} text-lg'></i>
                                        </button>
                                        <button onclick="duplicatePage({{ $page->id }}, '{{ addslashes($page->title) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-purple-100 text-purple-600 hover:bg-purple-200 hover:scale-110 transition-all" title="Duplicate">
                                            <i class='bx bx-copy text-lg'></i>
                                        </button>
                                        @if($page->type === 'custom')
                                            <button onclick="deletePage({{ $page->id }}, '{{ addslashes($page->title) }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                    <i class='bx bx-file text-4xl text-gray-400'></i>
                                </div>
                                <p class="text-gray-500 font-medium">No pages found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new page</p>
                                <a href="{{ route('admin.pages.create') }}" 
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm mt-4">
                                    <i class='bx bx-plus'></i>
                                    Create New Page
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($pages->hasPages())
        <div class="flex justify-center">
            {{ $pages->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function deletePage(id, title) {
        Swal.fire({
            title: 'Delete Page?',
            html: `Are you sure you want to delete <strong>${title}</strong>?<br><br><span class="text-gray-500 text-sm">You can restore it later.</span>`,
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
                form.action = `/admin/pages/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function restorePage(id, title) {
        Swal.fire({
            title: 'Restore Page?',
            html: `Are you sure you want to restore <strong>${title}</strong>?`,
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
                form.action = `/admin/pages/${id}/restore`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function forceDeletePage(id, title) {
        Swal.fire({
            title: 'Permanently Delete?',
            html: `<div class="text-left">
                <p>Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${title}</strong>?</p>
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
                form.action = `/admin/pages/${id}/force-delete`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function togglePageStatus(id, title, newStatus) {
        const action = newStatus ? 'publish' : 'unpublish';
        Swal.fire({
            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Page?`,
            html: `Are you sure you want to ${action} <strong>${title}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: newStatus ? '#10b981' : '#6b7280',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `${action.charAt(0).toUpperCase() + action.slice(1)}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: `${action.charAt(0).toUpperCase() + action.slice(1)}ing...`, allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/pages/${id}/toggle-status`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function duplicatePage(id, title) {
        Swal.fire({
            title: 'Duplicate Page?',
            html: `Are you sure you want to duplicate <strong>${title}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8b5cf6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-copy mr-1"></i> Duplicate',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Duplicating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/pages/${id}/duplicate`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
