@extends('layouts.public')

@section('title', 'About Us - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $aboutHeroTitle ?: 'About Us' }}</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $aboutHeroSubtitle ?: 'Learn more about our clinic and our commitment to your health and well-being.' }}
            </p>
        </div>

        <!-- Who We Are Section -->
        @if(!empty($aboutStoryShort) || !empty($aboutStoryLong))
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class='bx bx-group text-3xl text-blue-600'></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Who We Are</h2>
                    <p class="text-gray-600">Our story and commitment</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                @if(!empty($aboutStoryShort))
                    <p class="text-gray-800 text-lg font-medium mb-4 text-justify">
                        {{ $aboutStoryShort }}
                    </p>
                @endif
                @if(!empty($aboutStoryLong))
                    <p class="text-gray-600 leading-relaxed text-justify">
                        {{ $aboutStoryLong }}
                    </p>
                @endif
            </div>
        </div>
        @endif

        <!-- Vision Section -->
        @if(!empty($aboutVision))
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class='bx bx-target-lock text-3xl text-blue-600'></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Vision</h2>
                    <p class="text-gray-600">Our future goals and aspirations</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <p class="text-gray-800 text-lg leading-relaxed text-justify">
                    {{ $aboutVision }}
                </p>
            </div>
        </div>
        @endif

        <!-- Mission Section -->
        @if(!empty($aboutMissionItems))
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <i class='bx bx-bullseye text-3xl text-indigo-600'></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Mission</h2>
                    <p class="text-gray-600">Our purpose and commitment</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <ul class="space-y-4 list-none pl-0">
                    @foreach($aboutMissionItems as $item)
                        <li class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0">
                                <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center">
                                    <i class='bx bx-check text-indigo-600 text-base'></i>
                                </div>
                            </div>
                            <span class="text-gray-700 leading-relaxed pt-0.5 text-justify">
                                {{ $item }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Values Section -->
        @if(!empty($values))
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <i class='bx bx-heart text-3xl text-indigo-600'></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Values</h2>
                    <p class="text-gray-600">What we stand for</p>
                </div>
            </div>
            @if(!empty($aboutValuesDescription))
                <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 mb-6">
                    <p class="text-gray-600 leading-relaxed text-justify">
                        {{ $aboutValuesDescription }}
                    </p>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($values as $value)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="{{ $value['accent'] ?? 'bg-indigo-100' }} p-3 rounded-full">
                                    <i class='bx {{ $value['icon'] ?? 'bx-heart' }} text-2xl {{ str_contains($value['accent'] ?? '', 'blue') ? 'text-blue-600' : (str_contains($value['accent'] ?? '', 'green') ? 'text-green-600' : 'text-indigo-600') }}'></i>
                                </div>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">Value</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $value['title'] ?? '' }}</h3>
                            <p class="text-gray-600 text-sm leading-relaxed text-justify">
                                {{ $value['description'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Ready to Get Started?</h3>
            <p class="text-blue-100 mb-6">Experience our comprehensive healthcare services</p>
            @auth
                <a href="{{ route('patient.dashboard') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    Get Started
                </a>
            @endauth
        </div>
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
