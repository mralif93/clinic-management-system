@extends('layouts.admin')

@section('title', 'Create Announcement')
@section('page-title', 'Create New Announcement')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-plus-sign text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Create New Announcement</h2>
                        <p class="text-blue-100 text-sm mt-1">Add a new news or announcement for your homepage</p>
                    </div>
                </div>
                <a href="{{ route('admin.announcements.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='hgi-stroke hgi-arrow-left-01'></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Details Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-information-circle text-blue-600'></i>
                        Basic Details
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('title') border-red-500 @enderror"
                                placeholder="Enter announcement title">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subtitle -->
                        <div class="md:col-span-2">
                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                Subtitle
                            </label>
                            <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('subtitle') border-red-500 @enderror"
                                placeholder="Enter announcement subtitle (optional)">
                            @error('subtitle')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">A brief subtitle that appears below the title</p>
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm bg-white @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>News</option>
                                <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>
                                    Announcement</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                Display Order
                            </label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('order') border-red-500 @enderror"
                                placeholder="0">
                            @error('order')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-file-01 text-blue-600'></i>
                        Description
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <textarea id="description" name="description" rows="6"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('description') border-red-500 @enderror"
                        placeholder="Enter announcement description...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='hgi-stroke hgi-alert-circle'></i>
                            {{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-image-01 text-blue-600'></i>
                        Image/Poster
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Image
                    </label>
                    <input type="file" id="image" name="image" accept="image/*"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='hgi-stroke hgi-alert-circle'></i>
                            {{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Max file size: 5MB. Supported formats: JPG, PNG, GIF, SVG, WEBP
                    </p>
                </div>

                <!-- Link Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-link-01 text-blue-600'></i>
                        Optional Link
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Link URL -->
                        <div>
                            <label for="link_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Link URL
                            </label>
                            <input type="url" id="link_url" name="link_url" value="{{ old('link_url') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('link_url') border-red-500 @enderror"
                                placeholder="https://example.com">
                            @error('link_url')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Link Text -->
                        <div>
                            <label for="link_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Link Button Text
                            </label>
                            <input type="text" id="link_text" name="link_text" value="{{ old('link_text') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('link_text') border-red-500 @enderror"
                                placeholder="Learn More">
                            @error('link_text')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-settings-01 text-blue-600'></i>
                        Settings
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Featured -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                            <label
                                class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Show in hero carousel</span>
                            </label>
                        </div>

                        <!-- Published -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Published</label>
                            <label
                                class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Publish immediately</span>
                            </label>
                        </div>

                        <!-- Expiration Date -->
                        <div class="md:col-span-2">
                            <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Expiration Date (Optional)
                            </label>
                            <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('expires_at') border-red-500 @enderror">
                            @error('expires_at')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Announcement will automatically be hidden after this date
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.announcements.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='hgi-stroke hgi-cancel-circle'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-all text-sm shadow-lg shadow-blue-600/20">
                        <i class='hgi-stroke hgi-floppy-disk'></i>
                        Create Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection