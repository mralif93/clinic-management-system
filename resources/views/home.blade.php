@extends('layouts.public')

@section('title', 'Welcome - Clinic Management System')

@section('content')
<!-- Hero Section -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Hero Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                {{ get_setting('hero_title_line1', 'Modern Clinic') }}
                <span class="text-blue-600">{{ get_setting('hero_title_line2', 'Management') }}</span>
                {{ get_setting('hero_title_line3', 'System') }}
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                {{ get_setting('hero_description', 'Streamline your clinic operations with our comprehensive management solution. Manage patients, appointments, and staff all in one place.') }}
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 transition shadow-lg hover:shadow-xl">
                    <i class='bx bx-rocket mr-2'></i>
                    Get Started Free
                </a>
                <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition shadow-lg hover:shadow-xl border-2 border-blue-600">
                    <i class='bx bx-log-in mr-2'></i>
                    Sign In
                </a>
            </div>
        </div>

        <!-- Services Section -->
        <div class="mt-24">
            <!-- Psychology Services -->
            @if($psychologyServices->count() > 0)
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class='bx bx-brain text-3xl text-blue-600'></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Psychology Services</h2>
                            <p class="text-gray-600">Professional mental health and counseling services</p>
                        </div>
                    </div>
                    <a href="{{ route('services.index', ['type' => 'psychology']) }}" class="text-blue-600 font-semibold hover:text-blue-800">
                        View All ({{ $totalPsychologyServices }}) <i class='bx bx-arrow-right ml-1'></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($psychologyServices as $service)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-blue-100 p-3 rounded-full">
                                        <i class='bx bx-brain text-2xl text-blue-600'></i>
                                    </div>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Psychology</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 80) }}</p>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <span class="text-2xl font-bold text-blue-600">{{ get_currency_symbol() }}{{ number_format($service->price, 2) }}</span>
                                        <span class="text-gray-500 text-sm">/session</span>
                                    </div>
                                    <span class="text-gray-500 text-sm">
                                        <i class='bx bx-time'></i> {{ $service->duration_minutes }} min
                                    </span>
                                </div>
                                <a href="{{ route('services.show', $service->slug) }}" 
                                   class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm">
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
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class='bx bx-leaf text-3xl text-green-600'></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">Homeopathy Services</h2>
                            <p class="text-gray-600">Natural and holistic treatment approaches</p>
                        </div>
                    </div>
                    <a href="{{ route('services.index', ['type' => 'homeopathy']) }}" class="text-green-600 font-semibold hover:text-green-800">
                        View All ({{ $totalHomeopathyServices }}) <i class='bx bx-arrow-right ml-1'></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($homeopathyServices as $service)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-green-100 p-3 rounded-full">
                                        <i class='bx bx-leaf text-2xl text-green-600'></i>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Homeopathy</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 80) }}</p>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">{{ get_currency_symbol() }}{{ number_format($service->price, 2) }}</span>
                                        <span class="text-gray-500 text-sm">/session</span>
                                    </div>
                                    <span class="text-gray-500 text-sm">
                                        <i class='bx bx-time'></i> {{ $service->duration_minutes }} min
                                    </span>
                                </div>
                                <a href="{{ route('services.show', $service->slug) }}" 
                                   class="block w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Services CTA -->
            <div class="mt-12 text-center">
                <a href="{{ route('services.index') }}" class="inline-block bg-gradient-to-r from-blue-600 to-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:from-blue-700 hover:to-green-700 transition shadow-lg">
                    <i class='bx bx-list-ul mr-2'></i>
                    View All Services
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-24 bg-white rounded-2xl shadow-lg p-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ get_setting('stat_secure', '100%') }}</div>
                    <div class="text-gray-600">{{ get_setting('stat_secure_label', 'Secure') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ get_setting('stat_support', '24/7') }}</div>
                    <div class="text-gray-600">{{ get_setting('stat_support_label', 'Support') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ get_setting('stat_users', '1000+') }}</div>
                    <div class="text-gray-600">{{ get_setting('stat_users_label', 'Users') }}</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ get_setting('stat_uptime', '99.9%') }}</div>
                    <div class="text-gray-600">{{ get_setting('stat_uptime_label', 'Uptime') }}</div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-24 text-center">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-12 text-white">
                <h2 class="text-3xl font-bold mb-4">{{ get_setting('cta_title', 'Ready to Get Started?') }}</h2>
                <p class="text-blue-100 mb-8 text-lg">
                    {{ get_setting('cta_description', 'Join thousands of clinics already using our management system') }}
                </p>
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition inline-block shadow-lg">
                    Create Your Account Now
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class='bx bx-clinic text-2xl text-blue-400 mr-2'></i>
                        <span class="text-lg font-bold">{{ get_setting('clinic_name', 'Clinic Management') }}</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        {{ get_setting('footer_description', 'Modern clinic management solution for healthcare professionals.') }}
                    </p>
                    @if(get_setting('clinic_address'))
                    <p class="text-gray-400 text-sm mt-2">
                        <i class='bx bx-map mr-1'></i> {{ get_setting('clinic_address') }}
                    </p>
                    @endif
                    @if(get_setting('clinic_phone'))
                    <p class="text-gray-400 text-sm mt-1">
                        <i class='bx bx-phone mr-1'></i> {{ get_setting('clinic_phone') }}
                    </p>
                    @endif
                    @if(get_setting('clinic_email'))
                    <p class="text-gray-400 text-sm mt-1">
                        <i class='bx bx-envelope mr-1'></i> {{ get_setting('clinic_email') }}
                    </p>
                    @endif
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition">Updates</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition">Privacy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Clinic Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
@endsection

