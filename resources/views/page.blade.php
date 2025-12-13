@extends('layouts.public')

@section('title', ($page->meta_title ?: $page->title) . ' - Clinic Management System')

@if($page->meta_description)
    @section('meta_description', $page->meta_description)
@endif

@section('content')
<div class="bg-white min-h-screen flex flex-col">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-slate-50 to-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 lg:py-24">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-5">
                    {{ $page->title }}
                </h1>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="flex-1 py-16 md:py-20 lg:py-24 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                @if($page->content && is_array($page->content))
                    @if(isset($page->content['sections']))
                        @foreach($page->content['sections'] as $section)
                            <div class="mb-8">
                                @if($section['type'] === 'text')
                                    <div class="text-base md:text-lg text-gray-700 leading-relaxed text-justify">
                                        {!! nl2br(e($section['content'] ?? '')) !!}
                                    </div>
                                @elseif($section['type'] === 'heading')
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                                        {{ $section['content'] ?? '' }}
                                    </h2>
                                @elseif($section['type'] === 'html')
                                    <div class="text-base md:text-lg text-gray-700 leading-relaxed">
                                        {!! $section['content'] ?? '' !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-base md:text-lg text-gray-700 leading-relaxed text-justify">
                            {{ json_encode($page->content, JSON_PRETTY_PRINT) }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-file text-3xl text-indigo-400'></i>
                        </div>
                        <p class="text-gray-500 text-lg">No content available for this page.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

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

