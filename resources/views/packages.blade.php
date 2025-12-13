@extends('layouts.public')

@section('title', 'Special Packages - Clinic Management System')

@section('content')
<div class="bg-white min-h-screen flex flex-col">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-slate-50 to-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 lg:py-24">
            <div class="text-center max-w-3xl mx-auto">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-4">Special Packages</p>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-5">
                    {{ $packagesHeroTitle }}
                </h1>
                <p class="text-base md:text-lg text-gray-600 leading-relaxed">
                    {{ $packagesHeroSubtitle }}
                </p>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="flex-1 py-16 md:py-20 lg:py-24 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(!empty($packages))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                    @foreach($packages as $package)
                        <div class="group bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300">
                            <!-- Package Image -->
                            @if(!empty($package['image']))
                                <div class="relative h-64 overflow-hidden bg-gray-100">
                                    <img src="{{ str_starts_with($package['image'], 'data:') ? $package['image'] : (str_starts_with($package['image'], 'http') ? $package['image'] : asset('storage/' . $package['image'])) }}" 
                                        alt="{{ $package['name'] ?? 'Package' }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        onerror="this.style.display='none'; this.parentElement.classList.add('bg-gradient-to-br', 'from-indigo-100', 'to-purple-100');">
                                </div>
                            @else
                                <div class="h-64 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                    <i class='bx bx-package text-6xl text-indigo-300'></i>
                                </div>
                            @endif

                            <!-- Package Content -->
                            <div class="p-6 lg:p-7">
                                <!-- Package Name -->
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 group-hover:text-indigo-600 transition-colors duration-300">
                                    {{ $package['name'] ?? 'Package' }}
                                </h3>

                                <!-- Divider -->
                                <div class="h-px bg-gray-200 mb-4"></div>

                                <!-- Pricing -->
                                <div class="mb-5">
                                    @if(!empty($package['original_price']))
                                        <div class="flex items-baseline gap-2 mb-2">
                                            <span class="text-lg text-gray-400 line-through">
                                                RM{{ number_format($package['original_price'], 0) }}
                                            </span>
                                        </div>
                                    @endif
                                    @if(!empty($package['price']))
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-3xl md:text-4xl font-bold text-indigo-600">
                                                RM{{ number_format($package['price'], 0) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Package Details -->
                                <div class="space-y-3 mb-6">
                                    @if(!empty($package['sessions']))
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class='bx bx-calendar-check text-indigo-600 text-sm'></i>
                                            </div>
                                            <span class="text-sm md:text-base text-gray-700 font-medium">
                                                {{ $package['sessions'] }} Sessions
                                            </span>
                                        </div>
                                    @endif
                                    @if(!empty($package['duration']))
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class='bx bx-time text-indigo-600 text-sm'></i>
                                            </div>
                                            <span class="text-sm md:text-base text-gray-700 font-medium">
                                                {{ $package['duration'] }} Per Session
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Description -->
                                @if(!empty($package['description']))
                                    <div class="pt-4 border-t border-gray-100">
                                        <p class="text-sm text-gray-600 leading-relaxed text-justify">
                                            {{ $package['description'] }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-package text-3xl text-indigo-400'></i>
                    </div>
                    <p class="text-gray-500 text-lg">No packages available at the moment.</p>
                    <p class="text-gray-400 text-sm mt-2">Please check back later or contact us for more information.</p>
                </div>
            @endif
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

