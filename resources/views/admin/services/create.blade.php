@extends('layouts.admin')

@section('title', 'Create Service')
@section('page-title', 'Create New Service')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-r from-purple-600 to-violet-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
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
                        <h2 class="text-2xl font-bold">Create New Service</h2>
                        <p class="text-purple-100 text-sm mt-1">Add a new service to your clinic offerings</p>
                    </div>
                </div>
                <a href="{{ route('admin.services.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                    <i class='hgi-stroke hgi-arrow-left-01'></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf

                <!-- Service Details Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-information-circle text-purple-600'></i>
                        Service Details
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Service Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Service Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('name') border-red-500 @enderror"
                                placeholder="Enter service name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Slug will be automatically generated from the name</p>
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm bg-white @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="psychology" {{ old('type') == 'psychology' ? 'selected' : '' }}>Psychology
                                </option>
                                <option value="homeopathy" {{ old('type') == 'homeopathy' ? 'selected' : '' }}>Homeopathy
                                </option>
                                <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <label
                                class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Duration Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-money-bag-01 text-purple-600'></i>
                        Pricing & Duration
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">{{ get_setting('currency', 'RM') }}</span>
                                <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                    required
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('price') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('price')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                Duration (minutes) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="duration_minutes" name="duration_minutes"
                                    value="{{ old('duration_minutes', 60) }}" min="1" required
                                    class="w-full pr-16 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm @error('duration_minutes') border-red-500 @enderror"
                                    placeholder="60">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">min</span>
                            </div>
                            @error('duration_minutes')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-file-01 text-purple-600'></i>
                        Description
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <textarea id="description" name="description" rows="5"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-sm"
                        placeholder="Enter service description...">{{ old('description') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.services.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='hgi-stroke hgi-cancel-circle'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-all text-sm shadow-lg shadow-purple-600/20">
                        <i class='hgi-stroke hgi-floppy-disk'></i>
                        Create Service
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection