@extends('layouts.public')

@section('title', 'Our Services - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We offer comprehensive psychology and homeopathy treatments to support your health and well-being.
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex justify-center mb-8 space-x-4">
            <a href="{{ route('services.index') }}" 
               class="px-6 py-3 rounded-lg font-medium transition {{ !request('type') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                All Services
            </a>
            <a href="{{ route('services.index', ['type' => 'psychology']) }}" 
               class="px-6 py-3 rounded-lg font-medium transition {{ request('type') == 'psychology' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Psychology
            </a>
            <a href="{{ route('services.index', ['type' => 'homeopathy']) }}" 
               class="px-6 py-3 rounded-lg font-medium transition {{ request('type') == 'homeopathy' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Homeopathy
            </a>
        </div>

        <!-- Psychology Services -->
        @if(!request('type') || request('type') == 'psychology')
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class='bx bx-brain text-3xl text-blue-600'></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Psychology Services</h2>
                    <p class="text-gray-600">Professional mental health and counseling services</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                               class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No psychology services available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Homeopathy Services -->
        @if(!request('type') || request('type') == 'homeopathy')
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class='bx bx-leaf text-3xl text-green-600'></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Homeopathy Services</h2>
                    <p class="text-gray-600">Natural and holistic treatment approaches</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                               class="block w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No homeopathy services available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Ready to Book an Appointment?</h3>
            <p class="text-blue-100 mb-6">Choose from our psychology or homeopathy services</p>
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

