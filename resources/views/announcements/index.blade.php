@extends('layouts.public')

@section('title', 'News & Announcements - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex flex-col">
    <div class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <!-- Page Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                News & Announcements
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Stay updated with our latest news, announcements, and important information
            </p>
        </div>

        <!-- Announcements Grid -->
        @if($announcements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($announcements as $announcement)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all">
                        @if($announcement->image)
                            <div class="h-64 bg-gray-100 overflow-hidden">
                                <a href="{{ route('announcements.show', $announcement->id) }}">
                                    <img src="{{ $announcement->image_url }}" 
                                         alt="{{ $announcement->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </a>
                            </div>
                        @else
                            <div class="h-64 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <i class='bx bx-news text-6xl text-blue-300'></i>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $announcement->type === 'news' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $announcement->created_at->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('announcements.show', $announcement->id) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $announcement->title }}
                                </a>
                            </h3>
                            @if($announcement->subtitle)
                                <p class="text-gray-500 text-sm mb-2 line-clamp-1 italic">{{ $announcement->subtitle }}</p>
                            @endif
                            @if($announcement->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($announcement->description, 120) }}</p>
                            @endif
                            <a href="{{ route('announcements.show', $announcement->id) }}" 
                               class="inline-flex items-center gap-1 text-blue-600 font-semibold hover:text-blue-800 text-sm">
                                Read More
                                <i class='bx bx-arrow-right'></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($announcements->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-semibold text-gray-900">{{ $announcements->firstItem() }}</span> to <span class="font-semibold text-gray-900">{{ $announcements->lastItem() }}</span> of <span class="font-semibold text-gray-900">{{ $announcements->total() }}</span> results
                    </div>
                    <div class="flex justify-end">
                        {{ $announcements->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-news text-4xl text-gray-400'></i>
                </div>
                <p class="text-gray-500 font-medium text-lg">No announcements available</p>
                <p class="text-gray-400 text-sm mt-1">Check back later for updates</p>
            </div>
        @endif
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
