@extends('layouts.public')

@section('title', $package->name . ' - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <a href="{{ route('packages.index') }}" class="text-purple-600 hover:text-purple-800 mb-6 inline-block">
            <i class='bx bx-arrow-back mr-2'></i> Back to Packages
        </a>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-4 rounded-full mr-4">
                            <i class='bx bx-package text-4xl text-purple-600'></i>
                        </div>
                        <div>
                            @if($package->discount_percentage)
                                <span class="bg-red-100 text-red-800 text-sm font-semibold px-3 py-1 rounded-full">
                                    {{ $package->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $package->name }}</h1>
                
                <div class="flex items-center space-x-6 mb-6 text-gray-600">
                    @if($package->sessions)
                        <div class="flex items-center">
                            <i class='bx bx-calendar-check mr-2'></i>
                            <span>{{ $package->sessions }}</span>
                        </div>
                    @endif
                    @if($package->duration)
                        <div class="flex items-center">
                            <i class='bx bx-time mr-2'></i>
                            <span>{{ $package->duration }}</span>
                        </div>
                    @endif
                </div>

                <!-- Pricing -->
                <div class="mb-6">
                    @if($package->original_price)
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-2xl text-gray-400 line-through">RM{{ number_format($package->original_price, 0) }}</span>
                        </div>
                    @endif
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-purple-600">RM{{ number_format($package->price, 0) }}</span>
                    </div>
                </div>

                @if($package->description)
                <div class="prose max-w-none mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $package->description }}</p>
                </div>
                @endif

                @if($package->image)
                <div class="mb-8">
                    <img src="{{ str_starts_with($package->image, 'http') ? $package->image : asset('storage/' . $package->image) }}" 
                         alt="{{ $package->name }}" 
                         class="w-full max-w-md rounded-lg">
                </div>
                @endif

                <div class="border-t pt-6">
                    @auth
                        <a href="{{ route('patient.dashboard') }}" 
                           class="block w-full text-center bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                            Book Package
                        </a>
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Please login or register to book a package</p>
                            <div class="flex space-x-4 justify-center">
                                <a href="{{ route('login') }}" 
                                   class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                    Register
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
