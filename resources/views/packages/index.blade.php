@extends('layouts.public')

@section('title', 'Special Packages - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Special Packages</h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                Choose from our specially curated packages designed to meet your wellness needs.
            </p>
        </div>

        <!-- Packages Grid -->
        @if($packages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($packages as $package)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-hover">
                        <div class="p-5 md:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class='bx bx-package text-2xl text-purple-600'></i>
                                </div>
                                @if($package->discount_percentage)
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $package->discount_percentage }}% OFF
                                    </span>
                                @else
                                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">Package</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                            @if($package->description)
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($package->description, 100) }}</p>
                            @endif
                            
                            <!-- Package Details -->
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
                                        <span>{{ $package->duration }} Per Session</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Pricing -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if($package->original_price)
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-lg text-gray-400 line-through">RM{{ number_format($package->original_price, 0) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-purple-600">RM{{ number_format($package->price, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('packages.show', $package->slug) }}" 
                               class="package-card block w-full text-center bg-purple-600 text-white py-2.5 sm:py-2 rounded-lg hover:bg-purple-700 active:bg-purple-800 transition text-sm font-medium min-h-[44px] flex items-center justify-center">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui.empty-state
                icon="bx bx-package"
                title="No Packages Available"
                description="We're currently updating our special packages. Please check back soon or contact us for more information."
                variant="no-results"
            />
        @endif

        <!-- CTA Section -->
        <div class="cta-section mt-8 md:mt-12 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-5 sm:p-6 md:p-8 text-white text-center">
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-3 md:mb-4">Ready to Get Started?</h3>
            <p class="text-purple-100 mb-4 md:mb-6 text-xs sm:text-sm md:text-base">Choose from our special packages designed for your wellness journey</p>
            @auth
                <a href="{{ route('patient.dashboard') }}" class="bg-white text-purple-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block min-h-[44px] flex items-center justify-center mx-auto">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-purple-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block min-h-[44px] flex items-center justify-center mx-auto">
                    Get Started
                </a>
            @endauth
        </div>
        </div>
    </div>
</div>
@endsection
