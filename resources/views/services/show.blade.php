@extends('layouts.public')

@section('title', $service->name . ' - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <x-ui.breadcrumb :items="breadcrumb([
            ['label' => 'Services', 'url' => route('services.index')],
            ['label' => $service->name]
        ])" />

        <div class="service-detail bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 sm:p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-100 p-4 rounded-full mr-4">
                            <i class='bx {{ $service->type == 'psychology' ? 'bx-brain' : 'bx-leaf' }} text-4xl text-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-600'></i>
                        </div>
                        <div>
                            <span class="bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-100 text-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-800 text-sm font-semibold px-3 py-1 rounded-full">
                                {{ ucfirst($service->type) }}
                            </span>
                        </div>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">{{ $service->name }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 sm:gap-6 mb-6 text-gray-600">
                    <div class="flex items-center">
                        <i class='bx bx-time mr-2'></i>
                        <span>{{ $service->duration_minutes }} minutes</span>
                    </div>
                    <div class="flex items-center">
                        <i class='bx bx-dollar mr-2'></i>
                        <span class="text-2xl font-bold text-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-600">${{ number_format($service->price, 2) }}</span>
                    </div>
                </div>

                <div class="prose max-w-none mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $service->description }}</p>
                </div>

                <div class="border-t pt-6">
                    @auth
                        <a href="{{ route('patient.dashboard') }}" 
                           class="block w-full text-center bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-600 text-white py-3 rounded-lg hover:bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-700 active:bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-800 transition font-semibold min-h-[44px] flex items-center justify-center">
                            Book Appointment
                        </a>
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4 text-sm sm:text-base">Please login or register to book an appointment</p>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                                <a href="{{ route('login') }}" 
                                   class="px-5 sm:px-6 py-2.5 sm:py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 active:bg-gray-800 transition font-medium min-h-[44px] flex items-center justify-center">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="px-5 sm:px-6 py-2.5 sm:py-3 bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-600 text-white rounded-lg hover:bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-700 active:bg-{{ $service->type == 'psychology' ? 'blue' : 'green' }}-800 transition font-medium min-h-[44px] flex items-center justify-center">
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

