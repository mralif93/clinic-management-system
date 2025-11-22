@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        @if(isset($groupedSettings['general']))
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class='bx bx-cog text-2xl text-blue-600 mr-3'></i>
                    General Settings
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Logo Upload Section - Always show first -->
                @php
                    $logoSetting = $groupedSettings['general']->firstWhere('key', 'clinic_logo');
                @endphp
                @if($logoSetting)
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-image mr-2 text-blue-600'></i>
                            Clinic Logo
                            <span class="text-gray-500 text-xs font-normal">({{ $logoSetting->description }})</span>
                        </label>
                        
                        <!-- Current Logo Preview -->
                        @php
                            $logoPath = $logoSetting->value;
                            $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
                        @endphp
                        
                        @if($logoUrl && $logoPath)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current Logo:</p>
                                <div class="inline-block p-3 border-2 border-gray-300 rounded-lg bg-gray-50">
                                    <img src="{{ $logoUrl }}" 
                                         alt="Clinic Logo" 
                                         id="currentLogoPreview"
                                         class="h-24 w-auto object-contain max-w-xs">
                                </div>
                            </div>
                        @else
                            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <i class='bx bx-info-circle mr-1'></i>
                                    No logo uploaded yet. Upload a logo to display it on your website.
                                </p>
                            </div>
                        @endif
                        
                        <!-- Logo Upload Input -->
                        <div class="space-y-2">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   onchange="previewLogo(this)"
                                   class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-xs text-gray-500">
                                <i class='bx bx-info-circle mr-1'></i>
                                Recommended formats: PNG, JPG, SVG, or WEBP. Maximum file size: 2MB
                            </p>
                            
                            <!-- New Logo Preview -->
                            <div id="newLogoPreview" class="hidden mt-4">
                                <p class="text-sm text-gray-600 mb-2 font-medium">New Logo Preview:</p>
                                <div class="inline-block p-3 border-2 border-blue-300 rounded-lg bg-blue-50">
                                    <img id="logoPreviewImage" 
                                         src="" 
                                         alt="Logo Preview" 
                                         class="h-24 w-auto object-contain max-w-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @foreach($groupedSettings['general'] as $setting)
                    @if($setting->key !== 'clinic_logo')
                        <div>
                            <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                @if($setting->description)
                                    <span class="text-gray-500 text-xs font-normal">({{ $setting->description }})</span>
                                @endif
                            </label>
                            @if($setting->type === 'boolean')
                                <select name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            @elseif($setting->type === 'number')
                                <input type="number" 
                                       name="settings[{{ $setting->key }}]" 
                                       id="setting_{{ $setting->key }}" 
                                       value="{{ $setting->value }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @elseif($setting->type === 'textarea')
                                <textarea name="settings[{{ $setting->key }}]" 
                                          id="setting_{{ $setting->key }}" 
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $setting->value }}</textarea>
                            @else
                                <input type="text" 
                                       name="settings[{{ $setting->key }}]" 
                                       id="setting_{{ $setting->key }}" 
                                       value="{{ $setting->value }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Payment Settings -->
        @if(isset($groupedSettings['payment']))
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class='bx bx-credit-card text-2xl text-green-600 mr-3'></i>
                    Payment Settings
                </h3>
            </div>
            <div class="p-6 space-y-6">
                @foreach($groupedSettings['payment'] as $setting)
                    <div>
                        <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            @if($setting->description)
                                <span class="text-gray-500 text-xs font-normal">({{ $setting->description }})</span>
                            @endif
                        </label>
                        @if($setting->type === 'boolean')
                            <select name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        @elseif($setting->type === 'number')
                            <input type="number" 
                                   name="settings[{{ $setting->key }}]" 
                                   id="setting_{{ $setting->key }}" 
                                   value="{{ $setting->value }}"
                                   step="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @else
                            <input type="text" 
                                   name="settings[{{ $setting->key }}]" 
                                   id="setting_{{ $setting->key }}" 
                                   value="{{ $setting->value }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Email Settings -->
        @if(isset($groupedSettings['email']))
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class='bx bx-envelope text-2xl text-purple-600 mr-3'></i>
                    Email Settings
                </h3>
            </div>
            <div class="p-6 space-y-6">
                @foreach($groupedSettings['email'] as $setting)
                    <div>
                        <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            @if($setting->description)
                                <span class="text-gray-500 text-xs font-normal">({{ $setting->description }})</span>
                            @endif
                        </label>
                        <input type="text" 
                               name="settings[{{ $setting->key }}]" 
                               id="setting_{{ $setting->key }}" 
                               value="{{ $setting->value }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center">
                <i class='bx bx-save mr-2'></i>
                Save Settings
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewLogo(input) {
        const preview = document.getElementById('newLogoPreview');
        const previewImage = document.getElementById('logoPreviewImage');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection

