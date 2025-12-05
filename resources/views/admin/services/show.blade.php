@extends('layouts.admin')

@section('title', 'Service Details')
@section('page-title', 'Service Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-600 to-violet-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-cube text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $service->name }}</h1>
                        <p class="text-purple-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-link'></i>
                            <span class="font-mono text-sm">{{ $service->slug }}</span>
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 backdrop-blur">
                                <i class='bx bx-category mr-1'></i> {{ ucfirst($service->type) }}
                            </span>
                            @if($service->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($service->is_active)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-400/30">
                                    <i class='bx bx-pause-circle mr-1'></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$service->trashed())
                        <a href="{{ route('admin.services.edit', $service->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit Service
                        </a>
                    @else
                        <form action="{{ route('admin.services.restore', $service->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                                <i class='bx bx-refresh'></i>
                                Restore Service
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.services.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class='bx bx-money text-2xl text-purple-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ get_setting('currency', 'RM') }}{{ number_format($service->price, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-time text-2xl text-blue-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $service->duration_minutes }} <span
                                class="text-sm font-normal text-gray-500">min</span></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <i class='bx bx-category text-2xl text-green-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="text-2xl font-bold text-gray-900">{{ ucfirst($service->type) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Service Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-info-circle text-purple-600'></i>
                        Service Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Name</span>
                            <span class="text-sm font-medium text-gray-900">{{ $service->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Slug</span>
                            <span
                                class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $service->slug }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Type</span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                {{ ucfirst($service->type) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">Status</span>
                            @if($service->trashed())
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($service->is_active)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <i class='bx bx-pause-circle mr-1'></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-text text-purple-600'></i>
                        Description
                    </h3>
                </div>
                <div class="p-6">
                    @if($service->description)
                        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $service->description }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">No description provided.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Timestamps -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-time text-purple-600'></i>
                    Timestamps
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Created At</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $service->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $service->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $service->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $service->updated_at->format('h:i A') }}</p>
                    </div>
                    @if($service->trashed())
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <p class="text-xs text-red-500 mb-1">Deleted At</p>
                            <p class="text-sm font-semibold text-red-900">{{ $service->deleted_at->format('M d, Y') }}</p>
                            <p class="text-xs text-red-500">{{ $service->deleted_at->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if(!$service->trashed())
            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
                <div class="p-6 border-b border-red-100 bg-red-50/50">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                        <i class='bx bx-error-circle text-red-600'></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete this service</p>
                            <p class="text-sm text-gray-500">This will soft delete the service. You can restore it later.</p>
                        </div>
                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this service?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                                <i class='bx bx-trash'></i>
                                Delete Service
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection