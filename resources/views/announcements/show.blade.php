@extends('layouts.public')

@section('title', $announcement->title . ' - Clinic Management System')

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .sidebar { display: none !important; }
    }
    .article-content p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }
    .article-content ul, .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    .article-content li {
        margin-bottom: 0.75rem;
        line-height: 1.7;
    }
    .article-content h2, .article-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex flex-col">
    <!-- Hero Section -->
    @if($announcement->image)
    <div class="announcement-hero relative h-[50vh] sm:h-[60vh] md:h-[70vh] min-h-[400px] sm:min-h-[500px] md:min-h-[600px] bg-gradient-to-br from-blue-600 to-indigo-700 overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ $announcement->image_url }}" 
                 alt="{{ $announcement->title }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>
        </div>
        <div class="relative h-full flex items-end">
            <div class="max-w-7xl mx-auto w-full px-6 sm:px-8 lg:px-12 pb-12 lg:pb-16">
                <!-- Breadcrumb -->
                <nav class="mb-8" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-white/90 hover:text-white transition-colors duration-200">Home</a></li>
                        <li><i class='bx bx-chevron-right text-white/60 text-xs'></i></li>
                        <li><a href="{{ route('announcements.index') }}" class="text-white/90 hover:text-white transition-colors duration-200">Announcements</a></li>
                        <li><i class='bx bx-chevron-right text-white/60 text-xs'></i></li>
                        <li class="text-white font-medium truncate max-w-xs">{{ Str::limit($announcement->title, 40) }}</li>
                    </ol>
                </nav>
                
                <!-- Meta Information Row -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md text-white border border-white/30 shadow-lg">
                        <i class='bx {{ $announcement->type === 'news' ? 'bx-news' : 'bx-bullhorn' }} mr-2 text-sm'></i>
                        {{ ucfirst($announcement->type) }}
                    </span>
                    <span class="text-white/95 flex items-center gap-2 text-sm font-medium">
                        <i class='bx bx-calendar text-base'></i>
                        {{ $announcement->created_at->format('F d, Y') }}
                    </span>
                    @if($announcement->expires_at)
                        <span class="text-white/95 flex items-center gap-2 text-sm font-medium {{ $announcement->isExpired() ? 'bg-red-500/40 px-3 py-1.5 rounded-full' : '' }}">
                            <i class='bx bx-time text-base'></i>
                            Expires: {{ $announcement->expires_at->format('M d, Y') }}
                        </span>
                    @endif
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 max-w-5xl">
                    {{ $announcement->title }}
                </h1>
                
                <!-- Subtitle -->
                @if($announcement->subtitle)
                    <p class="text-lg md:text-xl lg:text-2xl text-white/95 font-light leading-relaxed max-w-4xl mb-8">
                        {{ $announcement->subtitle }}
                    </p>
                @endif
            </div>
        </div>
    </div>
    @else
    <!-- Hero Section without Image -->
    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Breadcrumb -->
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-white/90 hover:text-white transition-colors duration-200">Home</a></li>
                    <li><i class='bx bx-chevron-right text-white/60 text-xs'></i></li>
                    <li><a href="{{ route('announcements.index') }}" class="text-white/90 hover:text-white transition-colors duration-200">Announcements</a></li>
                    <li><i class='bx bx-chevron-right text-white/60 text-xs'></i></li>
                    <li class="text-white font-medium truncate max-w-xs">{{ Str::limit($announcement->title, 40) }}</li>
                </ol>
            </nav>
            
            <!-- Meta Information Row -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md text-white border border-white/30 shadow-lg">
                    <i class='bx {{ $announcement->type === 'news' ? 'bx-news' : 'bx-bullhorn' }} mr-2 text-sm'></i>
                    {{ ucfirst($announcement->type) }}
                </span>
                <span class="text-white/95 flex items-center gap-2 text-sm font-medium">
                    <i class='bx bx-calendar text-base'></i>
                    {{ $announcement->created_at->format('F d, Y') }}
                </span>
                @if($announcement->expires_at)
                    <span class="text-white/95 flex items-center gap-2 text-sm font-medium {{ $announcement->isExpired() ? 'bg-red-500/40 px-3 py-1.5 rounded-full' : '' }}">
                        <i class='bx bx-time text-base'></i>
                        Expires: {{ $announcement->expires_at->format('M d, Y') }}
                    </span>
                @endif
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 max-w-5xl">
                {{ $announcement->title }}
            </h1>
            
            <!-- Subtitle -->
            @if($announcement->subtitle)
                <p class="text-lg md:text-xl lg:text-2xl text-white/95 font-light leading-relaxed max-w-4xl mb-8">
                    {{ $announcement->subtitle }}
                </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Main Content Area -->
    <div class="flex-1 -mt-16 lg:-mt-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            <!-- Main Content Column -->
            <div class="lg:col-span-8">
                <!-- Article Content Card -->
                <article class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                    <div class="p-8 md:p-10 lg:p-12 xl:p-16">
                        @if($announcement->description)
                            <div class="article-content text-gray-800 text-base md:text-lg leading-relaxed" style="max-width: 70ch;">
                                @php
                                    $description = nl2br(e($announcement->description));
                                    // Convert double line breaks to paragraphs
                                    $description = preg_replace('/(<br\s*\/?>\s*){2,}/', '</p><p class="mb-6">', '<p class="mb-6">' . $description);
                                    if (!str_starts_with($description, '<p')) {
                                        $description = '<p class="mb-6">' . $description;
                                    }
                                    if (!str_ends_with($description, '</p>')) {
                                        $description = $description . '</p>';
                                    }
                                @endphp
                                {!! $description !!}
                            </div>
                        @endif

                        <!-- Last Updated and Published By Info -->
                        @if($announcement->updated_at && $announcement->updated_at->ne($announcement->created_at) || $announcement->creator)
                            <div class="mt-12 pt-10 border-t-2 border-gray-200">
                                <div class="flex flex-wrap items-center gap-6">
                                    @if($announcement->updated_at && $announcement->updated_at->ne($announcement->created_at))
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <i class='bx bx-edit text-blue-600 text-lg'></i>
                                            <span class="font-medium">Last Updated:</span>
                                            <span>{{ $announcement->updated_at->diffForHumans() }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($announcement->creator)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                                                {{ strtoupper(substr($announcement->creator->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium">Published by:</span>
                                            <span>{{ $announcement->creator->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </article>
            </div>

            <!-- Sidebar Column -->
            <div class="sidebar lg:col-span-4">
                <!-- Share Section -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-4 sm:p-6 lg:p-8 no-print lg:sticky lg:top-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg">
                            <i class='bx bx-share-alt text-white text-xl'></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Share This</h3>
                    </div>
                    <div class="space-y-3">
                        <button onclick="if(navigator.share) { navigator.share({title: '{{ addslashes($announcement->title) }}', text: '{{ addslashes($announcement->subtitle ?? $announcement->title) }}', url: window.location.href}); } else { navigator.clipboard.writeText(window.location.href); const btn = this; const original = btn.innerHTML; btn.innerHTML='<i class=\'bx bx-check text-lg\'></i> Copied!'; btn.classList.add('bg-green-600'); setTimeout(() => { btn.innerHTML = original; btn.classList.remove('bg-green-600'); }, 2000); }"
                                class="share-button w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 active:from-blue-800 active:to-indigo-800 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 min-h-[48px] text-sm sm:text-base">
                            <i class='bx bx-share text-lg sm:text-xl'></i> Share
                        </button>
                        <button onclick="navigator.clipboard.writeText(window.location.href); const btn = this; const original = btn.innerHTML; btn.innerHTML='<i class=\'bx bx-check text-lg\'></i> Copied!'; btn.classList.add('bg-green-600', 'text-white'); setTimeout(() => { btn.innerHTML = original; btn.classList.remove('bg-green-600', 'text-white'); }, 2000);"
                                class="share-button w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 active:bg-gray-300 transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md min-h-[48px] text-sm sm:text-base">
                            <i class='bx bx-link text-lg sm:text-xl'></i> Copy Link
                        </button>
                        <button onclick="window.print();"
                                class="share-button w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 active:bg-gray-300 transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md min-h-[48px] text-sm sm:text-base">
                            <i class='bx bx-printer text-lg sm:text-xl'></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Announcements Section -->
        @if($relatedAnnouncements->count() > 0)
            <div class="related-grid mt-12 sm:mt-16 md:mt-20 pt-8 sm:pt-12 md:pt-16 border-t-2 border-gray-200">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6 mb-8 sm:mb-12">
                    <div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-2 sm:mb-3">Related Announcements</h2>
                        <p class="text-gray-600 text-base sm:text-lg md:text-xl">You might also be interested in these updates</p>
                    </div>
                    <a href="{{ route('announcements.index') }}" 
                       class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3.5 bg-white border-2 border-blue-600 text-blue-600 rounded-xl font-bold hover:bg-blue-50 active:bg-blue-100 transition-all shadow-lg hover:shadow-xl min-h-[44px] text-sm sm:text-base">
                        View All
                        <i class='bx bx-arrow-right text-lg sm:text-xl'></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    @foreach($relatedAnnouncements as $related)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:border-blue-300 transition-all duration-300 group cursor-pointer transform hover:-translate-y-1">
                            <a href="{{ route('announcements.show', $related->id) }}" class="block">
                                @if($related->image)
                                    <div class="h-64 bg-gray-100 overflow-hidden relative">
                                        <img src="{{ $related->image_url }}" 
                                             alt="{{ $related->title }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <div class="absolute top-4 left-4 flex items-center gap-2">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $related->type === 'news' ? 'bg-blue-600 text-white' : 'bg-green-600 text-white' }} shadow-xl">
                                                {{ ucfirst($related->type) }}
                                            </span>
                                            @if($related->created_at->isAfter(now()->subDays(7)))
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold bg-yellow-500 text-white shadow-xl">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="h-64 bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 flex items-center justify-center relative group-hover:from-blue-200 group-hover:via-indigo-200 group-hover:to-purple-200 transition-all duration-300">
                                        <i class='bx bx-news text-7xl text-blue-300 group-hover:text-blue-400 transition-colors'></i>
                                        <div class="absolute top-4 left-4 flex items-center gap-2">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $related->type === 'news' ? 'bg-blue-600 text-white' : 'bg-green-600 text-white' }} shadow-xl">
                                                {{ ucfirst($related->type) }}
                                            </span>
                                            @if($related->created_at->isAfter(now()->subDays(7)))
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold bg-yellow-500 text-white shadow-xl">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="p-6 lg:p-7">
                                    <div class="flex items-center gap-2 mb-3 text-xs text-gray-500 font-medium">
                                        <i class='bx bx-calendar text-sm'></i>
                                        <span>{{ $related->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors leading-tight">
                                        {{ $related->title }}
                                    </h3>
                                    @if($related->subtitle)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-1 italic font-medium">{{ $related->subtitle }}</p>
                                    @endif
                                    @if($related->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">{{ Str::limit($related->description, 100) }}</p>
                                    @endif
                                    <div class="inline-flex items-center gap-2 text-blue-600 font-bold group-hover:gap-3 transition-all">
                                        Read More
                                        <i class='bx bx-arrow-right text-lg'></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Clinic Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
@endsection
