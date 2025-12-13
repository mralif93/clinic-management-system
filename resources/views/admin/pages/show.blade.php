@extends('layouts.admin')

@section('title', 'Page Details')
@section('page-title', 'Page Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-file text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $page->title }}</h1>
                        <p class="text-indigo-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-link'></i>
                            <span class="font-mono text-sm">{{ $page->slug }}</span>
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @php
                                $typeColors = [
                                    'custom' => 'bg-purple-500/30',
                                    'about' => 'bg-blue-500/30',
                                    'team' => 'bg-indigo-500/30',
                                    'packages' => 'bg-purple-500/30',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                <i class='bx bx-category mr-1'></i> {{ ucfirst($page->type) }}
                            </span>
                            @if($page->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($page->is_published)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Published
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-400/30">
                                    <i class='bx bx-hide mr-1'></i> Draft
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$page->trashed())
                        @if(in_array($page->type, ['about', 'team', 'packages']))
                            <a href="{{ route('admin.pages.' . $page->type) }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg">
                                <i class='bx bx-edit'></i>
                                Edit Page
                            </a>
                        @else
                            <a href="{{ route('admin.pages.edit', $page->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg">
                                <i class='bx bx-edit'></i>
                                Edit Page
                            </a>
                            <form action="{{ route('admin.pages.toggle-status', $page->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white {{ $page->is_published ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }} rounded-xl font-semibold transition-all shadow-lg">
                                    <i class='bx {{ $page->is_published ? 'bx-hide' : 'bx-show' }}'></i>
                                    {{ $page->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.pages.duplicate', $page->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg">
                                    <i class='bx bx-copy'></i>
                                    Duplicate
                                </button>
                            </form>
                        @endif
                    @else
                        <form action="{{ route('admin.pages.restore', $page->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                                <i class='bx bx-refresh'></i>
                                Restore Page
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.pages.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <i class='bx bx-category text-2xl text-indigo-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="text-2xl font-bold text-gray-900">{{ ucfirst($page->type) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-sort text-2xl text-blue-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Order</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $page->order }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $page->is_published ? 'bg-green-50' : 'bg-amber-50' }} flex items-center justify-center">
                        <i class='bx {{ $page->is_published ? 'bx-check-circle' : 'bx-hide' }} text-2xl {{ $page->is_published ? 'text-green-600' : 'text-amber-600' }}'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $page->is_published ? 'Published' : 'Draft' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Page Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-info-circle text-indigo-600'></i>
                        Page Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Title</span>
                            <span class="text-sm font-medium text-gray-900">{{ $page->title }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Slug</span>
                            <span
                                class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Type</span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ ucfirst($page->type) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Status</span>
                            @if($page->trashed())
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($page->is_published)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class='bx bx-check-circle mr-1'></i> Published
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class='bx bx-hide mr-1'></i> Draft
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Order</span>
                            <span class="text-sm font-medium text-gray-900">{{ $page->order }}</span>
                        </div>
                        @if($page->creator)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-500">Created By</span>
                                <span class="text-sm font-medium text-gray-900">{{ $page->creator->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-search text-indigo-600'></i>
                        SEO Settings
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 block mb-2">Meta Title</span>
                            @if($page->meta_title)
                                <p class="text-sm font-medium text-gray-900">{{ $page->meta_title }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">Not set (will use page title)</p>
                            @endif
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block mb-2">Meta Description</span>
                            @if($page->meta_description)
                                <p class="text-sm text-gray-700">{{ $page->meta_description }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">Not set</p>
                            @endif
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block mb-2">URL</span>
                            <a href="{{ $page->url }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-700 font-mono break-all">
                                {{ $page->url }}
                                <i class='bx bx-link-external text-xs'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Preview -->
        @if($page->content)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-text text-indigo-600'></i>
                        Content Preview
                    </h3>
                </div>
                <div class="p-6">
                    <pre class="text-xs text-gray-700 bg-gray-50 p-4 rounded-xl overflow-x-auto">{{ json_encode($page->content, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif

        <!-- Account Timestamps -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-time text-indigo-600'></i>
                    Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $page->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $page->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $page->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $page->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($page->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $page->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $page->deleted_at->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if(!$page->trashed() && $page->type === 'custom')
            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
                <div class="p-6 border-b border-red-100 bg-red-50/50">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                        <i class='bx bx-error-circle text-red-600'></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete this page</p>
                            <p class="text-sm text-gray-500">This will soft delete the page. You can restore it later.</p>
                        </div>
                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this page?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                                <i class='bx bx-trash'></i>
                                Delete Page
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

