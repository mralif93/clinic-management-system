@extends('layouts.admin')

@section('title', 'Service Details')
@section('page-title', 'Service Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Service Information</h3>
            <div class="flex space-x-2">
                @if(!$service->trashed())
                <a href="{{ route('admin.services.edit', $service->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-edit mr-2'></i> Edit
                </a>
                @else
                <form action="{{ route('admin.services.restore', $service->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-refresh mr-2'></i> Restore
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Service Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Slug: <span class="font-mono">{{ $service->slug }}</span></p>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($service->trashed())
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Deleted
                                </span>
                            @elseif($service->is_active)
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Inactive
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Type</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($service->type) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Price</label>
                    <p class="mt-1 text-gray-900 text-lg font-semibold">{{ get_setting('currency', '$') }}{{ number_format($service->price, 2) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Duration</label>
                    <p class="mt-1 text-gray-900">{{ $service->duration_minutes }} minutes</p>
                </div>
            </div>

            <!-- Description -->
            @if($service->description)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $service->description }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-8 border-t pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $service->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $service->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($service->trashed())
                <div>
                    <label class="text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-gray-900">{{ $service->deleted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

