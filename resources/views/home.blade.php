@extends('layouts.public')

@section('title', 'Welcome - Clinic Management System')

@section('content')
    <!-- Hero Section with Carousel -->
    <div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 lg:py-20">
            <!-- Hero Content (no slider) -->
            <div class="text-center mb-12 md:mb-16 lg:mb-20">
                <!-- Logo Section -->
                @php
                    $logoPath = get_setting('clinic_logo');
                    // Check if logo is base64 (for Vercel) or file path (for local)
                    if ($logoPath && str_starts_with($logoPath, 'data:')) {
                        $logoUrl = $logoPath; // Base64 data URI
                    } elseif ($logoPath) {
                        $logoUrl = asset('storage/' . $logoPath); // File path
                    } else {
                        $logoUrl = null;
                    }
                @endphp

                @if($logoUrl)
                    <div class="flex justify-center mb-12">
                        <img src="{{ $logoUrl }}" alt="{{ get_setting('clinic_name', 'Clinic Management') }} Logo"
                            class="h-40 md:h-56 lg:h-64 xl:h-72 w-auto object-contain">
                    </div>
                @endif

                <h1 class="text-4xl sm:text-5xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 md:mb-6">
                    {{ get_setting('hero_title_line1', 'Modern Clinic') }}
                    <span class="text-blue-600">{{ get_setting('hero_title_line2', 'Management') }}</span>
                    {{ get_setting('hero_title_line3', 'System') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-6 md:mb-8 max-w-3xl mx-auto px-4">
                    {{ get_setting('hero_description', 'Streamline your clinic operations with our comprehensive management solution. Manage patients, appointments, and staff all in one place.') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 px-4">
                    <a href="{{ route('register') }}"
                        class="hero-cta-button inline-flex items-center justify-center gap-2 px-5 sm:px-6 md:px-8 py-3 md:py-4 bg-blue-600 text-white rounded-lg text-sm sm:text-base md:text-lg font-semibold hover:bg-blue-700 active:bg-blue-800 transition shadow-lg hover:shadow-xl min-h-[44px]">
                        <i class='bx bx-rocket'></i>
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}"
                        class="hero-cta-button inline-flex items-center justify-center gap-2 px-5 sm:px-6 md:px-8 py-3 md:py-4 bg-white text-blue-600 rounded-lg text-sm sm:text-base md:text-lg font-semibold hover:bg-gray-50 active:bg-gray-100 transition shadow-lg hover:shadow-xl border-2 border-blue-600 min-h-[44px]">
                        <i class='bx bx-log-in'></i>
                        Sign In
                    </a>
                </div>
            </div>

            <!-- Announcements Section -->
            @if($recentAnnouncements->count() > 0)
                <div class="mb-16 md:mb-24">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 md:mb-8">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 md:p-3 rounded-full mr-3 md:mr-4">
                                <i class='bx bx-news text-2xl md:text-3xl text-blue-600'></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">News & Announcements</h2>
                                <p class="text-sm md:text-base text-gray-600">Stay updated with our latest news and
                                    announcements</p>
                            </div>
                        </div>
                        <a href="{{ route('announcements.index') }}"
                            class="text-blue-600 font-semibold hover:text-blue-800 text-sm md:text-base whitespace-nowrap">
                            View All <i class='bx bx-arrow-right ml-1'></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        @foreach($recentAnnouncements->take(6) as $announcement)
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                                @if($announcement->image)
                                    <div class="h-48 bg-gray-100 overflow-hidden">
                                        <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                        <i class='bx bx-news text-6xl text-blue-300'></i>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $announcement->type === 'news' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ ucfirst($announcement->type) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $announcement->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $announcement->title }}</h3>
                                    @if($announcement->subtitle)
                                        <p class="text-gray-500 text-sm mb-2 line-clamp-1 italic">{{ $announcement->subtitle }}</p>
                                    @endif
                                    @if($announcement->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ Str::limit($announcement->description, 100) }}</p>
                                    @endif
                                    @if($announcement->link_url)
                                        <a href="{{ $announcement->link_url }}"
                                            class="inline-flex items-center gap-1 text-blue-600 font-semibold hover:text-blue-800 text-sm">
                                            {{ $announcement->link_text ?: 'Read More' }}
                                            <i class='bx bx-arrow-right'></i>
                                        </a>
                                    @else
                                        <a href="{{ route('announcements.show', $announcement->id) }}"
                                            class="inline-flex items-center gap-1 text-blue-600 font-semibold hover:text-blue-800 text-sm">
                                            Read More
                                            <i class='bx bx-arrow-right'></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Services Section -->
            <div class="mt-16 md:mt-24">
                <!-- Psychology Services -->
                @if($psychologyServices->count() > 0)
                    <div class="mb-12 md:mb-16">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                    <i class='bx bx-brain text-2xl md:text-3xl text-blue-600'></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Psychology Services</h2>
                                    <p class="text-sm md:text-base text-gray-600">Professional mental health and counseling
                                        services</p>
                                </div>
                            </div>
                            <a href="{{ route('services.index', ['type' => 'psychology']) }}"
                                class="text-blue-600 font-semibold hover:text-blue-800 text-sm md:text-base whitespace-nowrap">
                                View All ({{ $totalPsychologyServices }}) <i class='bx bx-arrow-right ml-1'></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($psychologyServices as $service)
                                <div
                                    class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-hover">
                                    <div class="p-5 md:p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="bg-blue-100 p-3 rounded-full">
                                                <i class='bx bx-brain text-2xl text-blue-600'></i>
                                            </div>
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Psychology</span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 80) }}</p>
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <span
                                                    class="text-2xl font-bold text-blue-600">{{ get_currency_symbol() }}{{ number_format($service->price, 2) }}</span>
                                                <span class="text-gray-500 text-sm">/session</span>
                                            </div>
                                            <span class="text-gray-500 text-sm">
                                                <i class='bx bx-time'></i> {{ $service->duration_minutes }} min
                                            </span>
                                        </div>
                                        <a href="{{ route('services.show', $service->slug) }}"
                                            class="service-card block w-full text-center bg-blue-600 text-white py-2.5 sm:py-2 rounded-lg hover:bg-blue-700 active:bg-blue-800 transition text-sm font-medium min-h-[44px] flex items-center justify-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Homeopathy Services -->
                @if($homeopathyServices->count() > 0)
                    <div class="mb-12 md:mb-16">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-full mr-4">
                                    <i class='bx bx-leaf text-2xl md:text-3xl text-green-600'></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Homeopathy Services</h2>
                                    <p class="text-sm md:text-base text-gray-600">Natural and holistic treatment approaches</p>
                                </div>
                            </div>
                            <a href="{{ route('services.index', ['type' => 'homeopathy']) }}"
                                class="text-green-600 font-semibold hover:text-green-800 text-sm md:text-base whitespace-nowrap">
                                View All ({{ $totalHomeopathyServices }}) <i class='bx bx-arrow-right ml-1'></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($homeopathyServices as $service)
                                <div
                                    class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-hover">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="bg-green-100 p-3 rounded-full">
                                                <i class='bx bx-leaf text-2xl text-green-600'></i>
                                            </div>
                                            <x-ui.badge variant="success" size="sm">Homeopathy</x-ui.badge>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 80) }}</p>
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <span
                                                    class="text-2xl font-bold text-green-600">{{ get_currency_symbol() }}{{ number_format($service->price, 2) }}</span>
                                                <span class="text-gray-500 text-sm">/session</span>
                                            </div>
                                            <span class="text-gray-500 text-sm flex items-center gap-1">
                                                <i class='bx bx-time'></i> {{ $service->duration_minutes }} min
                                            </span>
                                        </div>
                                        <a href="{{ route('services.show', $service->slug) }}"
                                            class="service-card block w-full text-center bg-green-600 text-white py-2.5 sm:py-2 rounded-lg hover:bg-green-700 active:bg-green-800 transition text-sm font-medium min-h-[44px] flex items-center justify-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Services CTA -->
                <div class="mt-8 md:mt-12 text-center">
                    <a href="{{ route('services.index') }}"
                        class="inline-block bg-gradient-to-r from-blue-600 to-green-600 text-white px-6 md:px-8 py-3 md:py-4 rounded-lg text-base md:text-lg font-semibold hover:from-blue-700 hover:to-green-700 transition shadow-lg">
                        <i class='bx bx-list-ul mr-2'></i>
                        View All Services
                    </a>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-grid mt-16 md:mt-20 lg:mt-24 bg-white rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 lg:p-12">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 lg:gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            {{ get_setting('stat_secure', '100%') }}</div>
                        <div class="text-sm md:text-base text-gray-600">{{ get_setting('stat_secure_label', 'Secure') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            {{ get_setting('stat_support', '24/7') }}</div>
                        <div class="text-sm md:text-base text-gray-600">{{ get_setting('stat_support_label', 'Support') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            {{ get_setting('stat_users', '1000+') }}</div>
                        <div class="text-sm md:text-base text-gray-600">{{ get_setting('stat_users_label', 'Users') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">
                            {{ get_setting('stat_uptime', '99.9%') }}</div>
                        <div class="text-sm md:text-base text-gray-600">{{ get_setting('stat_uptime_label', 'Uptime') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 md:mt-24 text-center">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 md:p-8 lg:p-12 text-white">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3 md:mb-4">
                        {{ get_setting('cta_title', 'Ready to Get Started?') }}</h2>
                    <p class="text-blue-100 mb-6 md:mb-8 text-base md:text-lg px-4">
                        {{ get_setting('cta_description', 'Join thousands of clinics already using our management system') }}
                    </p>
                    <a href="{{ route('register') }}"
                        class="cta-section bg-white text-blue-600 px-5 sm:px-6 md:px-8 py-3 md:py-4 rounded-lg text-sm sm:text-base md:text-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block shadow-lg min-h-[44px] flex items-center justify-center">
                        Create Your Account Now
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection



