@extends('layouts.admin')

@section('title', 'Package Details')
@section('page-title', 'Package Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-package text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $package->name }}</h1>
                        <p class="text-purple-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-link'></i>
                            <span class="font-mono text-sm">{{ $package->slug }}</span>
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($package->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($package->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-400/30">
                                    <i class='bx bx-pause-circle mr-1'></i> Inactive
                                </span>
                            @endif
                            @if($package->discount_percentage)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-tag mr-1'></i> {{ $package->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$package->trashed())
                        <a href="{{ route('admin.packages.edit', $package->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit Package
                        </a>
                    @else
                        <form action="{{ route('admin.packages.restore', $package->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                                <i class='bx bx-refresh'></i>
                                Restore Package
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.packages.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pricing Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class='bx bx-money text-purple-600'></i>
                    Pricing
                </h3>
                <div class="space-y-3">
                    @if($package->original_price)
                        <div>
                            <p class="text-sm text-gray-500">Original Price</p>
                            <p class="text-xl font-semibold text-gray-400 line-through">RM{{ number_format($package->original_price, 2) }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Current Price</p>
                        <p class="text-3xl font-bold text-purple-600">RM{{ number_format($package->price, 2) }}</p>
                    </div>
                    @if($package->discount_percentage)
                        <div>
                            <p class="text-sm text-gray-500">Discount</p>
                            <p class="text-lg font-semibold text-red-600">{{ $package->discount_percentage }}% OFF</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Package Details Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class='bx bx-calendar text-purple-600'></i>
                    Package Details
                </h3>
                <div class="space-y-3">
                    @if($package->sessions)
                        <div>
                            <p class="text-sm text-gray-500">Sessions</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $package->sessions }}</p>
                        </div>
                    @endif
                    @if($package->duration)
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $package->duration }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $package->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Card -->
        @if($package->description)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class='bx bx-file-blank text-purple-600'></i>
                Description
            </h3>
            <p class="text-gray-700 leading-relaxed">{{ $package->description }}</p>
        </div>
        @endif

        <!-- Image Card -->
        @if($package->image)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class='bx bx-image text-purple-600'></i>
                Package Image
            </h3>
            <img src="{{ str_starts_with($package->image, 'http') ? $package->image : asset('storage/' . $package->image) }}" 
                 alt="{{ $package->name }}" 
                 class="w-full max-w-md rounded-xl">
        </div>
        @endif
    </div>
@endsection
