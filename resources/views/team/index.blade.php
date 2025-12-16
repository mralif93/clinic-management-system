@extends('layouts.public')

@section('title', 'Our Team - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Our Team</h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                Meet the multidisciplinary team that delivers continuous, connected care—combining psychology, homeopathy, and coordinated support.
            </p>
        </div>

        <!-- Team Members Grid -->
        @if($teamMembers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($teamMembers as $member)
                    @php
                        $photo = $member->photo;
                        $initial = strtoupper(substr($member->name ?? '', 0, 1));
                        if (!$initial) {
                            $initial = 'A';
                        }
                    @endphp
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-hover">
                        <div class="p-5 md:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-16 h-16 flex-shrink-0 relative">
                                    <div class="avatar-fallback absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-lg">
                                        {{ $initial }}
                                    </div>
                                    @if($photo)
                                        <img src="{{ str_starts_with($photo, 'data:') ? $photo : (str_starts_with($photo, 'http') ? $photo : asset('storage/' . $photo)) }}" 
                                            alt="{{ $member->name }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-indigo-50 shadow-lg relative z-10"
                                            onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';"
                                            onload="this.previousElementSibling.style.display='none';">
                                    @endif
                                </div>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">Team Member</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member->name }}</h3>
                            @if($member->title)
                                <p class="text-gray-600 text-sm mb-4 font-medium">{{ $member->title }}</p>
                            @endif
                            
                            @if($member->bio)
                                <p class="text-gray-600 text-sm leading-relaxed text-justify">
                                    {{ Str::limit($member->bio, 120) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui.empty-state
                icon="bx bx-group"
                title="No Team Members"
                description="We're currently updating our team information. Please check back soon or contact us for more information."
                variant="no-results"
            />
        @endif

        <!-- CTA Section -->
        <div class="cta-section mt-8 md:mt-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-5 sm:p-6 md:p-8 text-white text-center">
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-3 md:mb-4">Ready to Book an Appointment?</h3>
            <p class="text-indigo-100 mb-4 md:mb-6 text-xs sm:text-sm md:text-base">Connect with our experienced team members</p>
            @auth
                <a href="{{ route('patient.dashboard') }}" class="bg-white text-indigo-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block min-h-[44px] flex items-center justify-center mx-auto">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 active:bg-gray-200 transition inline-block min-h-[44px] flex items-center justify-center mx-auto">
                    Get Started
                </a>
            @endauth
        </div>
        </div>
    </div>
</div>
@endsection
