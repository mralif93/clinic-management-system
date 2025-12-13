@extends('layouts.admin')

@section('title', 'Create Team Member')
@section('page-title', 'Create New Team Member')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-plus-circle text-2xl'></i>
                        </div>
                        Create New Team Member
                    </h1>
                    <p class="mt-2 text-indigo-100">Add a new team member to your clinic</p>
                </div>
                <a href="{{ route('admin.team.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='bx bx-arrow-back'></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.team.store') }}" method="POST">
                @csrf

                <!-- Member Details Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-info-circle text-indigo-600'></i>
                        Member Details
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('name') border-red-500 @enderror"
                                placeholder="Enter team member name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title/Position
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('title') border-red-500 @enderror"
                                placeholder="e.g., Senior Psychologist">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                Display Order
                            </label>
                            <input type="number" id="order" name="order" value="{{ old('order') }}" min="0"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('order') border-red-500 @enderror"
                                placeholder="Auto">
                            @error('order')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first. Leave empty for auto.</p>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <label
                                class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Photo Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-image text-indigo-600'></i>
                        Photo
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                        Photo URL
                    </label>
                    <input type="text" id="photo" name="photo" value="{{ old('photo') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('photo') border-red-500 @enderror"
                        placeholder="Enter photo URL or file path">
                    @error('photo')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                            {{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">You can upload images later or use external URLs</p>
                </div>

                <!-- Bio Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-file-blank text-indigo-600'></i>
                        Bio
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <textarea id="bio" name="bio" rows="5"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm"
                        placeholder="Enter team member bio...">{{ old('bio') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.team.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='bx bx-x'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-600/20">
                        <i class='bx bx-save'></i>
                        Create Team Member
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
