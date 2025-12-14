@extends('layouts.admin')

@section('title', 'View Announcement')
@section('page-title', 'Announcement Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">{{ $announcement->title }}</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-white/20">
                            {{ ucfirst($announcement->type) }}
                        </span>
                        @if($announcement->is_featured)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-yellow-500/20">
                                <i class='bx bx-star mr-1'></i> Featured
                            </span>
                        @endif
                        @if($announcement->is_published)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-green-500/20">
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-amber-500/20">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-edit'></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.announcements.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($announcement->image)
                <div class="h-96 bg-gray-100 overflow-hidden">
                    <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" 
                         class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $announcement->title }}</h2>
                    
                    @if($announcement->subtitle)
                        <p class="text-lg text-gray-600 mb-4 italic">{{ $announcement->subtitle }}</p>
                    @endif
                    
                    @if($announcement->description)
                        <div class="text-gray-700 whitespace-pre-wrap mb-6">{{ $announcement->description }}</div>
                    @endif

                    @if($announcement->link_url)
                        <div class="mt-6">
                            <a href="{{ $announcement->link_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-all">
                                {{ $announcement->link_text ?: 'Learn More' }}
                                <i class='bx bx-link-external'></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="font-medium text-gray-900">{{ $announcement->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                    @if($announcement->creator)
                        <div>
                            <span class="text-gray-500">Created by:</span>
                            <span class="font-medium text-gray-900">{{ $announcement->creator->name }}</span>
                        </div>
                    @endif
                    @if($announcement->expires_at)
                        <div>
                            <span class="text-gray-500">Expires:</span>
                            <span class="font-medium {{ $announcement->isExpired() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $announcement->expires_at->format('F d, Y \a\t g:i A') }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <span class="text-gray-500">Display Order:</span>
                        <span class="font-medium text-gray-900">{{ $announcement->order }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
