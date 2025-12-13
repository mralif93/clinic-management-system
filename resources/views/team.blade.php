@extends('layouts.public')

@section('title', 'Our Team - Clinic Management System')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <div class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $teamHeroTitle ?: 'Our Team' }}</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $teamHeroSubtitle ?: 'Meet the multidisciplinary team that delivers continuous, connected care—combining psychology, homeopathy, and coordinated support.' }}
            </p>
        </div>

        <!-- Team Members Grid -->
        @if(!empty($teamMembers))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teamMembers as $member)
                    @php
                        $photo = $member['photo'] ?? null;
                        $initial = strtoupper(substr($member['name'] ?? '', 0, 1));
                        if (!$initial) {
                            $initial = 'A';
                        }
                    @endphp
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-16 h-16 flex-shrink-0 relative">
                                    <div class="avatar-fallback absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-lg">
                                        {{ $initial }}
                                    </div>
                                    @if($photo)
                                        <img src="{{ str_starts_with($photo, 'data:') ? $photo : (str_starts_with($photo, 'http') ? $photo : asset('storage/' . $photo)) }}" 
                                            alt="{{ $member['name'] }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-indigo-50 shadow-lg relative z-10"
                                            onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';"
                                            onload="this.previousElementSibling.style.display='none';">
                                    @endif
                                </div>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">Team Member</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member['name'] ?? 'Team Member' }}</h3>
                            <p class="text-gray-600 text-sm mb-4 font-medium">{{ $member['title'] ?? '' }}</p>
                            
                            @if(!empty($member['bio']))
                                <p class="text-gray-600 text-sm leading-relaxed text-justify">
                                    {{ Str::limit($member['bio'], 120) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-group text-3xl text-indigo-400'></i>
                </div>
                <p class="text-gray-500 text-lg">No team members available at the moment.</p>
                <p class="text-gray-400 text-sm mt-2">Please check back later or contact us for more information.</p>
            </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Ready to Book an Appointment?</h3>
            <p class="text-indigo-100 mb-6">Connect with our experienced team members</p>
            @auth
                <a href="{{ route('patient.dashboard') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
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
