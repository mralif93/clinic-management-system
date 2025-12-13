@extends('layouts.public')

@section('title', 'Our Team - Clinic Management System')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-slate-50 to-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 lg:py-24">
            <div class="text-center max-w-3xl mx-auto">
                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-4">Our Team</p>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-5">
                    {{ $teamHeroTitle }}
                </h1>
                <p class="text-base md:text-lg text-gray-600 leading-relaxed">
                    {{ $teamHeroSubtitle }}
                </p>
            </div>
        </div>
    </section>

    <!-- Team Members Section -->
    <section class="py-16 md:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($teamMembers as $member)
                    @php
                        $photo = $member['photo'] ?? null;
                        $initial = strtoupper(substr($member['name'] ?? '', 0, 1));
                        if (!$initial) {
                            $initial = 'A';
                        }
                    @endphp
                    <div class="group bg-white border border-gray-200 rounded-2xl p-6 lg:p-7 hover:shadow-xl hover:border-indigo-200 transition-all duration-300">
                        <!-- Profile Header -->
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-16 h-16 flex-shrink-0 relative">
                                <div class="avatar-fallback absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                    {{ $initial }}
                                </div>
                                @if($photo)
                                    <img src="{{ $photo }}" alt="{{ $member['name'] }}"
                                        class="w-16 h-16 rounded-full object-cover border-3 border-indigo-50 shadow-lg group-hover:border-indigo-100 transition-colors duration-300"
                                        onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';"
                                        onload="this.previousElementSibling.style.display='none';">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors duration-300">
                                    {{ $member['name'] }}
                                </h3>
                                <p class="text-sm md:text-base text-gray-600 font-medium">
                                    {{ $member['title'] }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Bio Section -->
                        @if(!empty($member['bio']))
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-600 leading-relaxed text-justify">
                                    {{ $member['bio'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Clinic Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
@endsection

