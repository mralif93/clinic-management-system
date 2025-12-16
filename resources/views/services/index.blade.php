@extends('layouts.public')

@section('title', 'Our Services - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Our Services</h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                We offer comprehensive psychology and homeopathy treatments to support your health and well-being.
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center mb-6 md:mb-8 gap-2 md:gap-4 px-4">
            <a href="{{ route('services.index') }}" 
               class="filter-tab px-4 md:px-6 py-2.5 md:py-3 rounded-lg text-sm md:text-base font-medium transition {{ !request('type') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 active:bg-gray-200' }} min-h-[44px] flex items-center justify-center">
                All Services
            </a>
            <a href="{{ route('services.index', ['type' => 'psychology']) }}" 
               class="filter-tab px-4 md:px-6 py-2.5 md:py-3 rounded-lg text-sm md:text-base font-medium transition {{ request('type') == 'psychology' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 active:bg-gray-200' }} min-h-[44px] flex items-center justify-center">
                Psychology
            </a>
            <a href="{{ route('services.index', ['type' => 'homeopathy']) }}" 
               class="filter-tab px-4 md:px-6 py-2.5 md:py-3 rounded-lg text-sm md:text-base font-medium transition {{ request('type') == 'homeopathy' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 active:bg-gray-200' }} min-h-[44px] flex items-center justify-center">
                Homeopathy
            </a>
        </div>

        <!-- Psychology Services -->
        @if(!request('type') || request('type') == 'psychology')
        <div class="mb-8 md:mb-12">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="bg-blue-100 p-2 md:p-3 rounded-full mr-3 md:mr-4">
                    <i class='bx bx-brain text-2xl md:text-3xl text-blue-600'></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Psychology Services</h2>
                    <p class="text-sm md:text-base text-gray-600">Professional mental health and counseling services</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @forelse($groupedServices['psychology'] ?? [] as $service)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class='bx bx-brain text-2xl text-blue-600'></i>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Psychology</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 100) }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-2xl font-bold text-blue-600">${{ number_format($service->price, 2) }}</span>
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
                @empty
                    <div class="col-span-full">
                        <x-ui.empty-state
                            icon="bx bx-brain"
                            title="No Psychology Services"
                            description="We're currently updating our psychology services. Please check back soon."
                            variant="no-results"
                        />
                    </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Homeopathy Services -->
        @if(!request('type') || request('type') == 'homeopathy')
        <div class="mb-8 md:mb-12">
            <div class="flex items-center mb-4 md:mb-6">
                <div class="bg-green-100 p-2 md:p-3 rounded-full mr-3 md:mr-4">
                    <i class='bx bx-leaf text-2xl md:text-3xl text-green-600'></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Homeopathy Services</h2>
                    <p class="text-sm md:text-base text-gray-600">Natural and holistic treatment approaches</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @forelse($groupedServices['homeopathy'] ?? [] as $service)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class='bx bx-leaf text-2xl text-green-600'></i>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Homeopathy</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->description, 100) }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-2xl font-bold text-green-600">${{ number_format($service->price, 2) }}</span>
                                    <span class="text-gray-500 text-sm">/session</span>
                                </div>
                                <span class="text-gray-500 text-sm">
                                    <i class='bx bx-time'></i> {{ $service->duration_minutes }} min
                                </span>
                            </div>
                            <a href="{{ route('services.show', $service->slug) }}" 
                               class="service-card block w-full text-center bg-green-600 text-white py-2.5 sm:py-2 rounded-lg hover:bg-green-700 active:bg-green-800 transition text-sm font-medium min-h-[44px] flex items-center justify-center">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <x-ui.empty-state
                            icon="bx bx-leaf"
                            title="No Homeopathy Services"
                            description="We're currently updating our homeopathy services. Please check back soon."
                            variant="no-results"
                        />
                    </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- CTA Section -->
        <div class="cta-section mt-8 md:mt-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-5 sm:p-6 md:p-8 text-white text-center">
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-3 md:mb-4">Ready to Book an Appointment?</h3>
            <p class="text-blue-100 mb-4 md:mb-6 text-xs sm:text-sm md:text-base">Choose from our psychology or homeopathy services</p>
            @auth
                <a href="{{ route('patient.dashboard') }}" class="bg-white text-blue-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block min-h-[44px] flex items-center justify-center mx-auto">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block min-h-[44px] flex items-center justify-center mx-auto">
                    Get Started
                </a>
            @endauth
        </div>
        </div>
    </div>
</div>
@endsection

