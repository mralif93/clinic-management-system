@extends('layouts.public')

@section('title', 'About Us - Clinic Management System')

@section('content')
<div class="bg-white overflow-x-hidden min-h-screen flex flex-col">
    <!-- Hero Section -->
    <section class="bg-slate-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 lg:py-20">
            <div class="max-w-4xl">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
                    {{ $aboutHeroTitle }}
                </h1>
                <p class="text-base md:text-lg text-gray-600 leading-relaxed text-justify">
                    {{ $aboutHeroSubtitle }}
                </p>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">
                <div class="lg:col-span-4">
                    <div class="sticky top-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-11 h-11 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class='bx bx-group text-blue-600 text-xl'></i>
                            </div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Who We Are</h2>
                        </div>
                        <div class="w-14 h-0.5 bg-blue-600 mb-6"></div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Our Story</p>
                    </div>
                </div>
                <div class="lg:col-span-8">
                    <div class="space-y-6">
                        <p class="text-base md:text-lg text-gray-800 leading-relaxed font-medium text-justify">
                            {{ $aboutStoryShort }}
                        </p>
                        <p class="text-base md:text-lg text-gray-600 leading-relaxed text-justify">
                            {{ $aboutStoryLong }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="bg-slate-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">
                <div class="lg:col-span-4">
                    <div class="sticky top-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-11 h-11 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class='bx bx-target-lock text-blue-600 text-xl'></i>
                            </div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Our Vision</h2>
                        </div>
                        <div class="w-14 h-0.5 bg-blue-600 mb-6"></div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Future Goals</p>
                    </div>
                </div>
                <div class="lg:col-span-8">
                    <div class="space-y-6">
                        <p class="text-base md:text-lg text-gray-800 leading-relaxed text-justify">
                            {{ $aboutVision }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">
                <div class="lg:col-span-4">
                    <div class="sticky top-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-11 h-11 bg-indigo-50 rounded-lg flex items-center justify-center">
                                <i class='bx bx-bullseye text-indigo-600 text-xl'></i>
                            </div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Our Mission</h2>
                        </div>
                        <div class="w-14 h-0.5 bg-indigo-600 mb-6"></div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Our Purpose</p>
                    </div>
                </div>
                <div class="lg:col-span-8">
                    <div class="space-y-5">
                        <ul class="space-y-4 list-none pl-0">
                            @foreach($aboutMissionItems as $item)
                                <li class="flex items-start gap-4">
                                    <div class="mt-1 flex-shrink-0">
                                        <div class="w-6 h-6 bg-indigo-50 rounded-md flex items-center justify-center">
                                            <i class='bx bx-check text-indigo-600 text-base'></i>
                                        </div>
                                    </div>
                                    <span class="text-base md:text-lg text-gray-700 leading-relaxed pt-0.5 text-justify">
                                        {{ $item }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="flex-1 bg-slate-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <i class='bx bx-heart text-indigo-600 text-xl'></i>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">Our Values</h2>
                </div>
                <div class="w-14 h-0.5 bg-indigo-600 mb-6"></div>
                <p class="text-base md:text-lg text-gray-600 text-justify">
                    {{ $aboutValuesDescription }}
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                @foreach($values as $value)
                    <div class="bg-white border border-gray-200 rounded-xl p-7 md:p-8 hover:shadow-md hover:border-gray-300 transition-all duration-200">
                        <div class="mb-6">
                            <div class="w-14 h-14 {{ $value['accent'] }} rounded-xl flex items-center justify-center mb-5">
                                <i class='bx {{ $value['icon'] }} text-2xl'></i>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">{{ $value['title'] }}</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base text-justify">
                            {{ $value['description'] }}
                        </p>
                    </div>
                @endforeach
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
