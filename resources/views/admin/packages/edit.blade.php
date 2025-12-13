@extends('layouts.admin')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-cog text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $package->name }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-white/20 mt-1">
                            Slug: {{ $package->slug }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.packages.show', $package->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-show'></i>
                        View Details
                    </a>
                    <a href="{{ route('admin.packages.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Package Details Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-info-circle text-purple-600'></i>
                        Package Details
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Package Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('name') border-red-500 @enderror"
                                placeholder="Enter package name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Slug will be automatically updated if name changes</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <label
                                class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-money text-purple-600'></i>
                        Pricing
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Original Price -->
                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Original Price
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">{{ get_setting('currency', 'RM') }}</span>
                                <input type="number" id="original_price" name="original_price" value="{{ old('original_price', $package->original_price) }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('original_price') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('original_price')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">{{ get_setting('currency', 'RM') }}</span>
                                <input type="number" id="price" name="price" value="{{ old('price', $package->price) }}" step="0.01" min="0"
                                    required
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('price') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('price')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Package Details Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-calendar text-purple-600'></i>
                        Package Details
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sessions -->
                        <div>
                            <label for="sessions" class="block text-sm font-medium text-gray-700 mb-2">
                                Sessions
                            </label>
                            <input type="text" id="sessions" name="sessions" value="{{ old('sessions', $package->sessions) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('sessions') border-red-500 @enderror"
                                placeholder="e.g., 2X SESSIONS">
                            @error('sessions')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Duration
                            </label>
                            <input type="text" id="duration" name="duration" value="{{ old('duration', $package->duration) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('duration') border-red-500 @enderror"
                                placeholder="e.g., 1 HOUR PER SESSION">
                            @error('duration')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-image text-purple-600'></i>
                        Package Image
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Image URL
                    </label>
                    <input type="text" id="image" name="image" value="{{ old('image', $package->image) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('image') border-red-500 @enderror"
                        placeholder="Enter image URL or file path">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                            {{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-file-blank text-purple-600'></i>
                        Description
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <textarea id="description" name="description" rows="5"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm"
                        placeholder="Enter package description...">{{ old('description', $package->description) }}</textarea>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.packages.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='bx bx-x'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-all text-sm shadow-lg shadow-purple-600/20">
                        <i class='bx bx-save'></i>
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
