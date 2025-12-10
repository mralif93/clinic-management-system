@extends('layouts.public')

@section('title', 'About Us - Clinic Management System')

@section('content')
<div class="bg-gradient-to-b from-white via-blue-50/60 to-white pb-40">
    <!-- Hero -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-3">About Us</p>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                    {{ $aboutHeroTitle }}
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    {{ $aboutHeroSubtitle }}
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <i class='bx bx-shield-alt-2 mr-2 text-base'></i>Secure by design
                    </span>
                    <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
                        <i class='bx bx-pulse mr-2 text-base'></i>Patient-first workflows
                    </span>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -left-6 -top-6 w-24 h-24 bg-blue-100 rounded-full blur-2xl"></div>
                <div class="absolute -right-8 -bottom-10 w-28 h-28 bg-emerald-100 rounded-full blur-2xl"></div>
                <div class="bg-white shadow-2xl rounded-2xl p-6 border border-blue-50 relative z-10">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-blue-50">
                            <p class="text-sm text-blue-700 font-semibold mb-1">Appointments</p>
                            <p class="text-3xl font-bold text-gray-900">+{{ number_format((float) get_setting('stat_users', 1000)) }}</p>
                            <p class="text-xs text-gray-500">Managed with real-time availability</p>
                        </div>
                        <div class="p-4 rounded-xl bg-emerald-50">
                            <p class="text-sm text-emerald-700 font-semibold mb-1">Care Teams</p>
                            <p class="text-3xl font-bold text-gray-900">24/7</p>
                            <p class="text-xs text-gray-500">Secure collaboration & updates</p>
                        </div>
                        <div class="p-4 rounded-xl bg-indigo-50">
                            <p class="text-sm text-indigo-700 font-semibold mb-1">Outcomes</p>
                            <p class="text-3xl font-bold text-gray-900">99.9%</p>
                            <p class="text-xs text-gray-500">Platform uptime for critical care</p>
                        </div>
                        <div class="p-4 rounded-xl bg-rose-50">
                            <p class="text-sm text-rose-700 font-semibold mb-1">Community</p>
                            <p class="text-3xl font-bold text-gray-900">+15k</p>
                            <p class="text-xs text-gray-500">Patients supported across programs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission & Values -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8 md:p-10">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Our mission</h2>
                    <p class="text-gray-600">
                        Deliver reliable, empathetic care while removing friction for patients and clinicians.
                    </p>
                </div>
                <div class="md:col-span-2 grid sm:grid-cols-2 gap-6">
                    @foreach($values as $value)
                        <div class="p-5 rounded-xl border border-gray-100 bg-gray-50/60">
                            <div class="flex items-center mb-3">
                                <span class="w-10 h-10 rounded-lg flex items-center justify-center {{ $value['accent'] }}">
                                    <i class='bx {{ $value['icon'] }} text-xl'></i>
                                </span>
                                <h3 class="ml-3 text-lg font-semibold text-gray-900">{{ $value['title'] }}</h3>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $value['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Milestones</p>
            <h2 class="text-3xl font-bold text-gray-900">How we got here</h2>
            <p class="text-gray-600 text-sm mt-2">Progress built on safety, patient trust, and continuous improvement.</p>
        </div>

        <div class="relative">
            <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-blue-200 via-gray-200 to-emerald-200"></div>
            <div class="space-y-10">
                @foreach($timeline as $index => $item)
                    <div class="relative">
                        <div class="hidden md:grid md:grid-cols-[1fr_auto_1fr] md:items-center gap-8">
                            @if($index % 2 === 0)
                                <div class="md:pr-8">
                                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold">
                                                {{ $item['year'] }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-600">{{ $item['title'] }}</span>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed text-sm">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-white border-2 border-blue-500 shadow-sm flex items-center justify-center">
                                        <i class='bx bx-check-circle text-blue-500 text-xl'></i>
                                    </div>
                                </div>
                                <div></div>
                            @else
                                <div></div>
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-white border-2 border-blue-500 shadow-sm flex items-center justify-center">
                                        <i class='bx bx-check-circle text-blue-500 text-xl'></i>
                                    </div>
                                </div>
                                <div class="md:pl-8">
                                    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold">
                                                {{ $item['year'] }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-600">{{ $item['title'] }}</span>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed text-sm">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Mobile layout -->
                        <div class="md:hidden">
                            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold">
                                        {{ $item['year'] }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-600">{{ $item['title'] }}</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed text-sm">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 fixed inset-x-0 bottom-0 z-40 shadow-2xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-blue-100 mb-2">Next step</p>
                    <h3 class="text-3xl font-bold mb-2">{{ $aboutCtaTitle }}</h3>
                    <p class="text-blue-100 text-sm max-w-2xl">
                        {{ $aboutCtaSubtitle }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @php
                        $primaryHref = $aboutCtaPrimaryLink ?: '#';
                        $secondaryHref = $aboutCtaSecondaryLink ?: '#';
                    @endphp
                    <a href="{{ $primaryHref }}" class="bg-white text-blue-700 px-5 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
                        {{ $aboutCtaPrimaryLabel }}
                    </a>
                    <a href="{{ $secondaryHref }}" class="bg-blue-500/20 border border-white/40 text-white px-5 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
                        {{ $aboutCtaSecondaryLabel }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

