@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="space-y-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Clinic Logo Section -->
            @php
                $logoSetting = isset($groupedSettings['general']) ? $groupedSettings['general']->firstWhere('key', 'clinic_logo') : null;
            @endphp
            @if($logoSetting)
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center shadow-sm">
                                    <i class='bx bx-image text-xl text-primary-700'></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Clinic Logo</h3>
                                <p class="text-sm text-gray-600 mt-0.5">Upload and manage your clinic's logo image</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        @php
                            $logoPath = $logoSetting->value;
                            // Check if logo is base64 (for Vercel) or file path (for local)
                            if ($logoPath && str_starts_with($logoPath, 'data:')) {
                                $logoUrl = $logoPath; // Base64 data URI
                            } elseif ($logoPath) {
                                $logoUrl = asset('storage/' . $logoPath); // File path
                            } else {
                                $logoUrl = null;
                            }
                        @endphp

                        <!-- Side-by-Side Layout -->
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <!-- Left: Current Logo (1/4) -->
                            <div class="lg:col-span-1 w-full">
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Current
                                        Logo</label>
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 h-full flex flex-col items-center justify-center gap-3">
                                        @if($logoUrl && $logoPath)
                                            <div class="w-full flex items-center justify-center bg-white border border-gray-200 rounded-lg p-3">
                                                <img src="{{ $logoUrl }}" alt="Clinic Logo" id="currentLogoPreview"
                                                    class="max-h-20 max-w-full object-contain">
                                            </div>
                                            <button type="button" onclick="removeLogo()"
                                                class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition">
                                                <i class='bx bx-trash text-sm'></i>
                                                Remove
                                            </button>
                                        @else
                                            <div class="w-full flex flex-col items-center justify-center text-center bg-white border border-dashed border-gray-300 rounded-lg p-4">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                                                    <i class='bx bx-image text-lg text-gray-400'></i>
                                                </div>
                                                <p class="text-sm font-medium text-gray-700">No logo yet</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Upload a logo on the right</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Preview Section -->
                                    <div id="newLogoPreview" class="hidden mt-4">
                                        <label
                                            class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Preview</label>
                                        <div class="bg-primary-50 rounded-lg border border-primary-100 p-2">
                                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                <div class="flex items-center justify-center min-h-[60px]">
                                                    <img id="logoPreviewImage" src="" alt="Logo Preview"
                                                        class="max-h-16 w-auto object-contain">
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2 text-center">
                                                <i class='bx bx-info-circle mr-1'></i>
                                                Preview shown above. Save settings to apply changes.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Upload Area (3/4) -->
                            <div class="lg:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Upload New
                                    Logo</label>

                                <!-- Compact Upload Zone -->
                                <div class="relative">
                                    <div id="dropZone"
                                        class="relative bg-white rounded-lg border border-dashed border-gray-300 transition-all duration-200 cursor-pointer hover:border-primary-300 hover:bg-primary-50/60">
                                        <input type="file" name="logo" id="logo"
                                            accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                            onchange="previewLogo(this); validateFileSize(this)"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <div class="p-4">
                                            <div class="flex flex-col sm:flex-row items-center gap-4">
                                                <!-- Icon -->
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center border-2 border-dashed border-primary-200">
                                                        <i class='bx bx-upload text-xl text-primary-600'></i>
                                                    </div>
                                                </div>

                                                <!-- Text Content -->
                                                <div class="flex-1 text-center sm:text-left">
                                                    <p class="text-sm font-semibold text-gray-900 mb-0.5">
                                                        <span class="text-gray-700">Click to upload</span> or drag and drop
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        PNG, JPG, SVG, or WEBP (max 2MB)
                                                    </p>
                                                </div>

                                                <!-- Browse Button -->
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-xs font-semibold rounded-md shadow-sm pointer-events-none">
                                                        <i class='bx bx-folder-open mr-1.5 text-sm'></i>
                                                        Browse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Name Display -->
                                    <div id="fileNameDisplay" class="hidden mt-2">
                                        <div class="flex items-center gap-2 p-2 bg-primary-50 border border-primary-100 rounded-lg">
                                            <i class='bx bx-file text-primary-600 text-base flex-shrink-0'></i>
                                            <span id="fileName" class="text-xs text-gray-700 truncate flex-1"></span>
                                            <button type="button" onclick="clearFileInput()"
                                                class="text-primary-500 hover:text-red-600 transition-colors">
                                                <i class='bx bx-x text-lg'></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- File Size Error -->
                                    <div id="fileSizeError" class="hidden mt-2">
                                        <div class="flex items-start gap-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                            <i class='bx bx-error-circle text-red-600 text-base flex-shrink-0 mt-0.5'></i>
                                            <div class="flex-1">
                                                <p class="text-xs font-medium text-red-900">File too large</p>
                                                <p class="text-xs text-red-700 mt-0.5">Maximum file size is 2MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Tip: Use a square logo (at least 256×256) with a transparent background for best results.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- General Settings -->
            @if(isset($groupedSettings['general']))
                @php
                    $generalSettings = $groupedSettings['general']->filter(function ($setting) {
                        return $setting->key !== 'clinic_logo';
                    });
                @endphp
                @if($generalSettings->count() > 0)
                    <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center shadow-sm">
                                        <i class='bx bx-cog text-xl text-primary-700'></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-gray-900">General Settings</h3>
                                    <p class="text-sm text-gray-600 mt-0.5">Configure basic clinic information and preferences</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-5">
                                @foreach($generalSettings as $setting)
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start py-3 border-b border-gray-100 last:border-0">
                                        <div class="md:col-span-1">
                                            <label for="setting_{{ $setting->key }}"
                                                class="flex items-start text-sm font-semibold text-gray-700 pt-2">
                                                <i class='bx bx-info-circle text-primary-600 mr-2 text-base mt-0.5 flex-shrink-0'></i>
                                                <div>
                                                    <div>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</div>
                                                    @if($setting->description)
                                                        <p class="text-xs text-gray-500 font-normal mt-1">{{ $setting->description }}</p>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>

                                        <div class="md:col-span-2">
                                            @if($setting->type === 'boolean')
                                                <div class="relative">
                                                    <select name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}"
                                                        class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 appearance-none cursor-pointer">
                                                        <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled</option>
                                                        <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled</option>
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                        <i class='bx bx-chevron-down text-lg'></i>
                                                    </div>
                                                </div>
                                            @elseif($setting->type === 'number')
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class='bx bx-hash text-gray-400'></i>
                                                    </div>
                                                    <input type="number" name="settings[{{ $setting->key }}]"
                                                        id="setting_{{ $setting->key }}" value="{{ $setting->value }}"
                                                        class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                                </div>
                                            @elseif($setting->type === 'textarea')
                                                <div class="relative">
                                                    <textarea name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" rows="4"
                                                        placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}..."
                                                        class="w-full rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 resize-none">{{ $setting->value }}</textarea>
                                                </div>
                                            @else
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class='bx bx-text text-gray-400'></i>
                                                    </div>
                                                    <input type="text" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}"
                                                        value="{{ $setting->value }}"
                                                        placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}..."
                                                        class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Payment Settings -->
            @if(isset($groupedSettings['payment']))
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                    <div
                                    class="w-10 h-10 rounded-lg bg-success-100 flex items-center justify-center shadow-sm">
                                    <i class='bx bx-credit-card text-xl text-success-700'></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Payment Settings</h3>
                                <p class="text-sm text-gray-600 mt-0.5">Manage payment methods and transaction preferences</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-5">
                            @foreach($groupedSettings['payment'] as $setting)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start py-3 border-b border-gray-100 last:border-0">
                                    <div class="md:col-span-1">
                                        <label for="setting_{{ $setting->key }}"
                                            class="flex items-start text-sm font-semibold text-gray-700 pt-2">
                                            <i class='bx bx-info-circle text-success-600 mr-2 text-base mt-0.5 flex-shrink-0'></i>
                                            <div>
                                                <div>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</div>
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-500 font-normal mt-1">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    </div>

                                    <div class="md:col-span-2">
                                        @if($setting->type === 'boolean')
                                            <div class="relative">
                                                <select name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}"
                                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 appearance-none cursor-pointer">
                                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled</option>
                                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                    <i class='bx bx-chevron-down text-lg'></i>
                                                </div>
                                            </div>
                                        @elseif($setting->type === 'number')
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class='bx bx-dollar text-gray-400'></i>
                                                </div>
                                                <input type="number" name="settings[{{ $setting->key }}]"
                                                    id="setting_{{ $setting->key }}" value="{{ $setting->value }}" step="0.01"
                                                    placeholder="0.00"
                                                    class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                            </div>
                                        @else
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class='bx bx-text text-gray-400'></i>
                                                </div>
                                                <input type="text" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}"
                                                    value="{{ $setting->value }}"
                                                    placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}..."
                                                    class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payroll Settings -->
            @if(isset($groupedSettings['payroll']))
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                    <div
                                    class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shadow-sm">
                                    <i class='bx bx-money text-xl text-amber-700'></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Payroll Settings</h3>
                                <p class="text-sm text-gray-600 mt-0.5">Configure payroll calculation rates and deductions</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-5">
                            @foreach($groupedSettings['payroll'] as $setting)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start py-3 border-b border-gray-100 last:border-0">
                                    <div class="md:col-span-1">
                                        <label for="setting_{{ $setting->key }}"
                                                class="flex items-start text-sm font-semibold text-gray-700 pt-2">
                                            <i class='bx bx-info-circle text-amber-600 mr-2 text-base mt-0.5 flex-shrink-0'></i>
                                            <div>
                                                <div>{{ ucwords(str_replace(['payroll_', '_'], ['', ' '], $setting->key)) }}</div>
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-500 font-normal mt-1">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class='bx bx-hash text-gray-400'></i>
                                            </div>
                                            <input type="number" step="0.01" name="settings[{{ $setting->key }}]"
                                                id="setting_{{ $setting->key }}" value="{{ $setting->value }}"
                                                placeholder="Enter {{ strtolower(str_replace(['payroll_', '_'], ['', ' '], $setting->key)) }}..."
                                                class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Email Settings -->
            @if(isset($groupedSettings['email']))
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                    <div
                                    class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center shadow-sm">
                                    <i class='bx bx-envelope text-xl text-indigo-700'></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Email Settings</h3>
                                <p class="text-sm text-gray-600 mt-0.5">Configure email notifications and SMTP settings</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-5">
                            @foreach($groupedSettings['email'] as $setting)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start py-3 border-b border-gray-100 last:border-0">
                                    <div class="md:col-span-1">
                                        <label for="setting_{{ $setting->key }}"
                                            class="flex items-start text-sm font-semibold text-gray-700 pt-2">
                                            <i class='bx bx-info-circle text-indigo-600 mr-2 text-base mt-0.5 flex-shrink-0'></i>
                                            <div>
                                                <div>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</div>
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-500 font-normal mt-1">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class='bx bx-at text-gray-400'></i>
                                            </div>
                                            <input type="text" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}"
                                                value="{{ $setting->value }}"
                                                placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}..."
                                                class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Operating Hours -->
            @php
                $operatingHoursStart = $groupedSettings['general'] ?? collect();
                $operatingHoursEnd = $groupedSettings['general'] ?? collect();
                $startTimeSetting = $operatingHoursStart->firstWhere('key', 'operating_hours_start');
                $endTimeSetting = $operatingHoursEnd->firstWhere('key', 'operating_hours_end');
            @endphp
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center shadow-sm">
                                <i class='bx bx-time-five text-xl text-primary-700'></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Operating Hours</h3>
                            <p class="text-sm text-gray-600 mt-0.5">Configure clinic operating hours</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Time -->
                        <div>
                            <label for="operating_hours_start"
                                class="flex items-start text-sm font-semibold text-gray-700 mb-2">
                                <i class='bx bx-sun text-primary-600 mr-2 text-base mt-0.5 flex-shrink-0'></i>
                                <div>
                                    <div>Opening Time</div>
                                    <p class="text-xs text-gray-500 font-normal mt-1">Clinic opening time</p>
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-time text-gray-400'></i>
                                </div>
                                <input type="time" name="settings[operating_hours_start]" id="operating_hours_start"
                                    value="{{ $startTimeSetting->value ?? '09:00' }}"
                                    class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            </div>
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="operating_hours_end"
                                class="flex items-start text-sm font-semibold text-gray-700 mb-2">
                                <i class='bx bx-moon text-primary-600 mr-2 text-base mt-0.5 flex-shrink-0'></i>
                                <div>
                                    <div>Closing Time</div>
                                    <p class="text-xs text-gray-500 font-normal mt-1">Clinic closing time</p>
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-time text-gray-400'></i>
                                </div>
                                <input type="time" name="settings[operating_hours_end]" id="operating_hours_end"
                                    value="{{ $endTimeSetting->value ?? '17:00' }}"
                                    class="w-full rounded-md border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            </div>
                        </div>
                    </div>

                    <!-- Info Message -->
                    <div class="mt-4 flex items-center gap-2 p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <i class='bx bx-info-circle text-slate-500 text-base flex-shrink-0'></i>
                        <p class="text-xs text-slate-700">
                            These operating hours will be displayed to patients when booking appointments and used for
                            schedule
                            management.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="sticky bottom-0 bg-gradient-to-t from-gray-50 to-transparent pt-8 pb-4 mx-2">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class='bx bx-info-circle text-primary-600 mr-2 text-lg'></i>
                            <span>Changes will be applied immediately after saving</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="window.location.reload()"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-gray-700 border border-gray-300 rounded-md bg-white hover:bg-gray-50 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                <i class='bx bx-refresh text-lg'></i>
                                Reset
                            </button>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-2.5 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                                <i class='bx bx-save text-lg'></i>
                                Save All Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // File size validation (2MB = 2 * 1024 * 1024 bytes)
            const MAX_FILE_SIZE = 2 * 1024 * 1024;

            function validateFileSize(input) {
                const fileSizeError = document.getElementById('fileSizeError');
                const fileNameDisplay = document.getElementById('fileNameDisplay');
                const fileName = document.getElementById('fileName');
                const dropZone = document.getElementById('dropZone');

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    if (file.size > MAX_FILE_SIZE) {
                        fileSizeError.classList.remove('hidden');
                        input.value = '';
                        fileNameDisplay.classList.add('hidden');
                        dropZone.classList.remove('border-primary-300', 'bg-primary-50/50');
                        dropZone.classList.add('border-red-400', 'bg-red-50/50');
                        return false;
                    } else {
                        fileSizeError.classList.add('hidden');
                        dropZone.classList.remove('border-red-400', 'bg-red-50/50');
                        dropZone.classList.add('border-primary-300', 'bg-primary-50/50');

                        // Show file name
                        fileName.textContent = file.name;
                        fileNameDisplay.classList.remove('hidden');

                        previewLogo(input);
                        return true;
                    }
                }
                return false;
            }

            function previewLogo(input) {
                const preview = document.getElementById('newLogoPreview');
                const previewImage = document.getElementById('logoPreviewImage');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        preview.classList.remove('hidden');
                    };

                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.classList.add('hidden');
                }
            }

            function clearFileInput() {
                const input = document.getElementById('logo');
                const preview = document.getElementById('newLogoPreview');
                const fileNameDisplay = document.getElementById('fileNameDisplay');
                const fileSizeError = document.getElementById('fileSizeError');
                const dropZone = document.getElementById('dropZone');

                input.value = '';
                preview.classList.add('hidden');
                fileNameDisplay.classList.add('hidden');
                fileSizeError.classList.add('hidden');
                dropZone.classList.remove('border-primary-300', 'bg-primary-50/50', 'border-red-400', 'bg-red-50/50');
                dropZone.classList.add('border-gray-300');
            }

            // Drag and drop functionality
            document.addEventListener('DOMContentLoaded', function () {
                const dropZone = document.getElementById('dropZone');
                const fileInput = document.getElementById('logo');

                if (dropZone && fileInput) {
                    // Prevent default drag behaviors
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, preventDefaults, false);
                        document.body.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    // Highlight drop zone when item is dragged over it
                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropZone.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, unhighlight, false);
                    });

                    function highlight(e) {
                        dropZone.classList.add('border-primary-500', 'bg-primary-50/50');
                        dropZone.classList.remove('border-gray-300');
                    }

                    function unhighlight(e) {
                        dropZone.classList.remove('border-primary-500', 'bg-primary-50/50');
                        if (!dropZone.classList.contains('border-primary-300') && !dropZone.classList.contains('border-red-400')) {
                            dropZone.classList.add('border-gray-300');
                        }
                    }

                    // Handle dropped files
                    dropZone.addEventListener('drop', handleDrop, false);

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;

                        if (files.length > 0) {
                            fileInput.files = files;
                            validateFileSize(fileInput);
                        }
                    }
                }
            });

            function removeLogo() {
                Swal.fire({
                    title: 'Remove Logo?',
                    text: 'Are you sure you want to remove the logo? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Remove It',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Removing...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Create form data
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'DELETE');

                        // Make AJAX request
                        fetch('{{ route("admin.settings.remove-logo") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                            .then(response => {
                                if (response.ok) {
                                    return response.json().catch(() => response.text());
                                }
                                throw new Error('Network response was not ok');
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Logo removed successfully!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload page to reflect changes
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to remove logo. Please try again.'
                                });
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection