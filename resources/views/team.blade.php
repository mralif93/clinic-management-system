@extends('layouts.public')

@section('title', 'Our Team - Clinic Management System')

@section('content')
<div class="bg-gradient-to-b from-white via-indigo-50/60 to-white min-h-screen">
    <!-- Hero -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="text-center max-w-3xl mx-auto">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3">Our Team</p>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-4">
                {{ $teamHeroTitle }}
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                {{ $teamHeroSubtitle }}
            </p>
        </div>
    </div>

    <!-- Leadership -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8 md:p-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Leadership</h2>
                    <p class="text-gray-600">Guiding clinical standards, safety, and patient experience.</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">
                    <i class='bx bx-badge-check mr-2'></i>Board-certified team
                </span>
            </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($leadership as $leader)
                            <div class="p-6 rounded-xl border border-gray-100 bg-gray-50/60 hover:shadow-md transition text-center">
                                @php
                                    $photo = $leader['photo'] ?? null;
                                @endphp
                                <div class="w-20 h-20 mx-auto mb-4 relative">
                                    @php
                                        $initial = strtoupper(substr($leader['name'] ?? '', 0, 1));
                                        if (!$initial) {
                                            $initial = 'A';
                                        }
                                    @endphp
                                    <div class="avatar-fallback absolute inset-0 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold shadow-sm">
                                        {{ $initial }}
                                    </div>
                                    @if($photo)
                                        <img src="{{ $photo }}" alt="{{ $leader['name'] }}"
                                            class="w-20 h-20 rounded-full object-cover border-2 border-indigo-100 shadow-sm"
                                            onerror="this.style.display='none'; this.previousElementSibling.classList.remove('hidden');"
                                            onload="this.previousElementSibling.classList.add('hidden');">
                                    @endif
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $leader['name'] }}</h3>
                                <p class="text-sm font-medium text-indigo-700 mb-1">{{ $leader['role'] }}</p>
                                <p class="text-xs text-gray-500 mb-3">{{ $leader['focus'] }}</p>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $leader['bio'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Care Teams -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($careTeams as $team)
                <div class="p-6 rounded-2xl border {{ $team['color'] }} shadow-sm bg-white/70">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $team['title'] }}</h3>
                        <span class="w-10 h-10 rounded-full bg-white/80 border border-white flex items-center justify-center text-indigo-600">
                            <i class='bx bx-group text-lg'></i>
                        </span>
                    </div>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        @foreach($team['members'] as $member)
                            <li class="flex items-start">
                                <i class='bx bx-check-shield text-indigo-600 mr-2 mt-0.5 text-base'></i>
                                <span>{{ $member }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-gray-900 sticky bottom-0 z-40 shadow-2xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-gray-300 mb-2">Ready to connect</p>
                    <h3 class="text-3xl font-bold mb-2">{{ $teamCtaTitle }}</h3>
                    <p class="text-gray-300 text-sm max-w-2xl">
                        {{ $teamCtaSubtitle }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @php
                        $teamPrimaryHref = $teamCtaPrimaryLink ?: '#';
                        $teamSecondaryHref = $teamCtaSecondaryLink ?: '#';
                    @endphp
                    <a href="{{ $teamPrimaryHref }}" class="bg-white text-gray-900 px-5 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
                        {{ $teamCtaPrimaryLabel }}
                    </a>
                    <a href="{{ $teamSecondaryHref }}" class="bg-gray-800 border border-white/20 text-white px-5 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                        {{ $teamCtaSecondaryLabel }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

