@extends('layouts.public')

@section('title', 'Special Packages - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $packagesHeroTitle ?: 'Special Packages' }}</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $packagesHeroSubtitle ?: 'Choose from our specially curated packages designed to meet your wellness needs.' }}
            </p>
        </div>

        <!-- Packages Grid -->
        @if(!empty($packages))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($packages as $package)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class='bx bx-package text-2xl text-purple-600'></i>
                                </div>
                                @if(!empty($package['original_price']) && !empty($package['price']))
                                    @php
                                        $discount = round((($package['original_price'] - $package['price']) / $package['original_price']) * 100);
                                    @endphp
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $discount }}% OFF
                                    </span>
                                @else
                                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">Package</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package['name'] ?? 'Package' }}</h3>
                            @if(!empty($package['description']))
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($package['description'], 100) }}</p>
                            @endif
                            
                            <!-- Package Details -->
                            <div class="space-y-2 mb-4">
                                @if(!empty($package['sessions']))
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class='bx bx-calendar-check text-purple-600 mr-2'></i>
                                        <span>{{ $package['sessions'] }} Sessions</span>
                                    </div>
                                @endif
                                @if(!empty($package['duration']))
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class='bx bx-time text-purple-600 mr-2'></i>
                                        <span>{{ $package['duration'] }} Per Session</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Pricing -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if(!empty($package['original_price']))
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-lg text-gray-400 line-through">RM{{ number_format($package['original_price'], 0) }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($package['price']))
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-2xl font-bold text-purple-600">RM{{ number_format($package['price'], 0) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @auth
                                <a href="{{ route('patient.dashboard') }}" 
                                   class="block w-full text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                                    Book Package
                                </a>
                            @else
                                <a href="{{ route('register') }}" 
                                   class="block w-full text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                                    Get Started
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-package text-3xl text-purple-400'></i>
                </div>
                <p class="text-gray-500 text-lg">No packages available at the moment.</p>
                <p class="text-gray-400 text-sm mt-2">Please check back later or contact us for more information.</p>
            </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-12 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Ready to Get Started?</h3>
            <p class="text-purple-100 mb-6">Choose from our special packages designed for your wellness journey</p>
            @auth
                <a href="{{ route('patient.dashboard') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
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
