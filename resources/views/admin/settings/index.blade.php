@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Clinic Settings</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage your clinic configuration and preferences</p>
                </div>
                <div id="saveStatus"
                    class="hidden items-center gap-2 px-4 py-2 rounded-lg bg-green-50 border border-green-200">
                    <i class='bx bx-check-circle text-green-600'></i>
                    <span class="text-sm font-medium text-green-700">All changes saved</span>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div x-data="{ activeTab: 'general' }" class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100">
                    <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
                        <button @click="activeTab = 'general'"
                            :class="activeTab === 'general' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 px-6 py-4 border-b-2 text-sm font-medium transition-all whitespace-nowrap">
                            <i class='bx bx-cog text-lg'></i>
                            General
                        </button>
                        <button @click="activeTab = 'branding'"
                            :class="activeTab === 'branding' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 px-6 py-4 border-b-2 text-sm font-medium transition-all whitespace-nowrap">
                            <i class='bx bx-palette text-lg'></i>
                            Branding
                        </button>
                        <button @click="activeTab = 'payment'"
                            :class="activeTab === 'payment' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 px-6 py-4 border-b-2 text-sm font-medium transition-all whitespace-nowrap">
                            <i class='bx bx-credit-card text-lg'></i>
                            Payment
                        </button>
                        <button @click="activeTab = 'payroll'"
                            :class="activeTab === 'payroll' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 px-6 py-4 border-b-2 text-sm font-medium transition-all whitespace-nowrap">
                            <i class='bx bx-money text-lg'></i>
                            Payroll
                        </button>
                        <button @click="activeTab = 'email'"
                            :class="activeTab === 'email' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 px-6 py-4 border-b-2 text-sm font-medium transition-all whitespace-nowrap">
                            <i class='bx bx-envelope text-lg'></i>
                            Email
                        </button>
                        <button @click="activeTab = 'hours'"
                            :class="activeTab === 'hours' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 px-6 py-4 border-b-2 text-sm font-medium transition-all whitespace-nowrap">
                            <i class='bx bx-time-five text-lg'></i>
                            Hours
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- General Settings Tab -->
                    <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="space-y-1 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
                            <p class="text-sm text-gray-500">Configure basic clinic information and preferences</p>
                        </div>

                        @if(isset($groupedSettings['general']))
                            @php
                                $generalSettings = $groupedSettings['general']->filter(function ($setting) {
                                    return !in_array($setting->key, ['clinic_logo', 'operating_hours_start', 'operating_hours_end']);
                                });
                            @endphp
                            <div class="space-y-5">
                                @foreach($generalSettings as $setting)
                                    <div
                                        class="group p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200">
                                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                                            <div class="md:w-1/3">
                                                <label for="setting_{{ $setting->key }}"
                                                    class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                                    @if($setting->key === 'clinic_name')
                                                        <i class='bx bx-building-house text-blue-500'></i>
                                                    @elseif($setting->key === 'clinic_email')
                                                        <i class='bx bx-envelope text-blue-500'></i>
                                                    @elseif($setting->key === 'clinic_phone')
                                                        <i class='bx bx-phone text-blue-500'></i>
                                                    @elseif($setting->key === 'clinic_address')
                                                        <i class='bx bx-map text-blue-500'></i>
                                                    @else
                                                        <i class='bx bx-cog text-blue-500'></i>
                                                    @endif
                                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                                </label>
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-400 mt-1 ml-6">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                            <div class="md:w-2/3">
                                                @if($setting->type === 'boolean')
                                                    <div class="flex items-center gap-3">
                                                        <button type="button" onclick="toggleSetting('{{ $setting->key }}', this)"
                                                            data-value="{{ $setting->value }}"
                                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $setting->value == '1' ? 'bg-blue-600' : 'bg-gray-200' }}">
                                                            <span class="sr-only">Toggle {{ $setting->key }}</span>
                                                            <span
                                                                class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $setting->value == '1' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                        </button>
                                                        <span
                                                            class="text-sm text-gray-600">{{ $setting->value == '1' ? 'Enabled' : 'Disabled' }}</span>
                                                    </div>
                                                @elseif($setting->type === 'textarea')
                                                    <textarea name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}"
                                                        data-key="{{ $setting->key }}" rows="3"
                                                        onchange="autoSave('{{ $setting->key }}', this.value)"
                                                        class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                                                        placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}...">{{ $setting->value }}</textarea>
                                                @else
                                                    <input type="text" name="settings[{{ $setting->key }}]"
                                                        id="setting_{{ $setting->key }}" data-key="{{ $setting->key }}"
                                                        value="{{ $setting->value }}"
                                                        onchange="autoSave('{{ $setting->key }}', this.value)"
                                                        class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                                        placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}...">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class='bx bx-cog text-4xl text-gray-300 mb-3'></i>
                                <p class="text-gray-500">No general settings found</p>
                            </div>
                        @endif
                    </div>

                    <!-- Branding Tab (Logo) -->
                    <div x-show="activeTab === 'branding'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="space-y-1 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Branding</h3>
                            <p class="text-sm text-gray-500">Customize your clinic's visual identity</p>
                        </div>

                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                            id="logoForm">
                            @csrf
                            @method('PUT')

                            @php
                                $logoSetting = isset($groupedSettings['general']) ? $groupedSettings['general']->firstWhere('key', 'clinic_logo') : null;
                                $logoPath = $logoSetting ? $logoSetting->value : null;
                                if ($logoPath && str_starts_with($logoPath, 'data:')) {
                                    $logoUrl = $logoPath;
                                } elseif ($logoPath) {
                                    $logoUrl = asset('storage/' . $logoPath);
                                } else {
                                    $logoUrl = null;
                                }
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Current Logo -->
                                <div class="p-6 rounded-xl border border-gray-100 bg-gray-50/50">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                        <i class='bx bx-image text-blue-500'></i>
                                        Current Logo
                                    </h4>
                                    <div
                                        class="bg-white rounded-xl border border-gray-200 p-8 flex items-center justify-center min-h-[200px]">
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" alt="Clinic Logo" id="currentLogoPreview"
                                                class="max-h-32 max-w-full object-contain">
                                        @else
                                            <div class="text-center">
                                                <div
                                                    class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                                    <i class='bx bx-image text-3xl text-gray-400'></i>
                                                </div>
                                                <p class="text-sm font-medium text-gray-600">No logo uploaded</p>
                                                <p class="text-xs text-gray-400 mt-1">Upload a logo on the right</p>
                                            </div>
                                        @endif
                                    </div>
                                    @if($logoUrl)
                                        <button type="button" onclick="removeLogo()"
                                            class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-all">
                                            <i class='bx bx-trash'></i>
                                            Remove Logo
                                        </button>
                                    @endif
                                </div>

                                <!-- Upload New Logo -->
                                <div class="p-6 rounded-xl border border-gray-100 bg-gray-50/50">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                        <i class='bx bx-upload text-blue-500'></i>
                                        Upload New Logo
                                    </h4>

                                    <div id="dropZone"
                                        class="relative bg-white rounded-xl border-2 border-dashed border-gray-300 hover:border-blue-400 transition-all cursor-pointer">
                                        <input type="file" name="logo" id="logo"
                                            accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                            onchange="previewLogo(this); validateFileSize(this)"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <div class="p-8 text-center">
                                            <div
                                                class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-4">
                                                <i class='bx bx-cloud-upload text-3xl text-blue-500'></i>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-700">Drop your logo here or <span
                                                    class="text-blue-600">browse</span></p>
                                            <p class="text-xs text-gray-400 mt-2">PNG, JPG, SVG or WEBP (max 2MB)</p>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div id="newLogoPreview" class="hidden mt-4">
                                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                            <div class="flex items-center gap-4">
                                                <img id="logoPreviewImage" src="" alt="Preview"
                                                    class="h-16 w-auto object-contain bg-white rounded-lg p-2 border border-gray-200">
                                                <div class="flex-1">
                                                    <p id="fileName" class="text-sm font-medium text-gray-700 truncate"></p>
                                                    <p class="text-xs text-gray-500 mt-1">Ready to upload</p>
                                                </div>
                                                <button type="button" onclick="clearFileInput()"
                                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                                    <i class='bx bx-x text-xl'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error -->
                                    <div id="fileSizeError" class="hidden mt-4">
                                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <i class='bx bx-error-circle text-red-500 text-xl'></i>
                                                <div>
                                                    <p class="text-sm font-medium text-red-700">File too large</p>
                                                    <p class="text-xs text-red-500">Maximum file size is 2MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" id="uploadBtn"
                                        class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                        <i class='bx bx-upload'></i>
                                        Upload Logo
                                    </button>

                                    <p class="mt-3 text-xs text-gray-400 text-center">
                                        <i class='bx bx-info-circle mr-1'></i>
                                        Use a square logo (256×256px) with transparent background for best results
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Settings Tab -->
                    <div x-show="activeTab === 'payment'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="space-y-1 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Payment Settings</h3>
                            <p class="text-sm text-gray-500">Configure payment methods and transaction preferences</p>
                        </div>

                        @if(isset($groupedSettings['payment']))
                            <div class="space-y-5">
                                @foreach($groupedSettings['payment'] as $setting)
                                    <div
                                        class="group p-4 rounded-xl border border-gray-100 hover:border-green-200 hover:bg-green-50/30 transition-all duration-200">
                                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                                            <div class="md:w-1/3">
                                                <label for="setting_{{ $setting->key }}"
                                                    class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                                    <i class='bx bx-dollar-circle text-green-500'></i>
                                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                                </label>
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-400 mt-1 ml-6">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                            <div class="md:w-2/3">
                                                @if($setting->type === 'boolean')
                                                    <div class="flex items-center gap-3">
                                                        <button type="button" onclick="toggleSetting('{{ $setting->key }}', this)"
                                                            data-value="{{ $setting->value }}"
                                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 {{ $setting->value == '1' ? 'bg-green-600' : 'bg-gray-200' }}">
                                                            <span
                                                                class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $setting->value == '1' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                        </button>
                                                        <span
                                                            class="text-sm text-gray-600">{{ $setting->value == '1' ? 'Enabled' : 'Disabled' }}</span>
                                                    </div>
                                                @elseif($setting->type === 'number')
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium">{{ get_currency_symbol() }}</span>
                                                        <input type="number" step="0.01" name="settings[{{ $setting->key }}]"
                                                            id="setting_{{ $setting->key }}" data-key="{{ $setting->key }}"
                                                            value="{{ $setting->value }}"
                                                            onchange="autoSave('{{ $setting->key }}', this.value)"
                                                            class="auto-save-input w-full rounded-lg border border-gray-200 pl-8 pr-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all"
                                                            placeholder="0.00">
                                                    </div>
                                                @else
                                                    <input type="text" name="settings[{{ $setting->key }}]"
                                                        id="setting_{{ $setting->key }}" data-key="{{ $setting->key }}"
                                                        value="{{ $setting->value }}"
                                                        onchange="autoSave('{{ $setting->key }}', this.value)"
                                                        class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all"
                                                        placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}...">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class='bx bx-credit-card text-4xl text-gray-300 mb-3'></i>
                                <p class="text-gray-500">No payment settings found</p>
                            </div>
                        @endif
                    </div>

                    <!-- Payroll Settings Tab -->
                    <div x-show="activeTab === 'payroll'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="space-y-1 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Payroll Settings</h3>
                            <p class="text-sm text-gray-500">Configure payroll calculation rates and deductions</p>
                        </div>

                        @if(isset($groupedSettings['payroll']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($groupedSettings['payroll'] as $setting)
                                    <div
                                        class="group p-4 rounded-xl border border-gray-100 hover:border-amber-200 hover:bg-amber-50/30 transition-all duration-200">
                                        <label for="setting_{{ $setting->key }}"
                                            class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-3">
                                            <i class='bx bx-calculator text-amber-500'></i>
                                            {{ ucwords(str_replace(['payroll_', '_'], ['', ' '], $setting->key)) }}
                                        </label>
                                        @if($setting->description)
                                            <p class="text-xs text-gray-400 mb-3">{{ $setting->description }}</p>
                                        @endif
                                        <div class="relative">
                                            <input type="number" step="0.01" name="settings[{{ $setting->key }}]"
                                                id="setting_{{ $setting->key }}" data-key="{{ $setting->key }}"
                                                value="{{ $setting->value }}" onchange="autoSave('{{ $setting->key }}', this.value)"
                                                class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
                                                placeholder="0.00">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class='bx bx-money text-4xl text-gray-300 mb-3'></i>
                                <p class="text-gray-500">No payroll settings found</p>
                            </div>
                        @endif
                    </div>

                    <!-- Email Settings Tab -->
                    <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="space-y-1 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Email Settings</h3>
                            <p class="text-sm text-gray-500">Configure email notifications and SMTP settings</p>
                        </div>

                        @if(isset($groupedSettings['email']))
                            <div class="space-y-5">
                                @foreach($groupedSettings['email'] as $setting)
                                    <div
                                        class="group p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all duration-200">
                                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                                            <div class="md:w-1/3">
                                                <label for="setting_{{ $setting->key }}"
                                                    class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                                    <i class='bx bx-at text-indigo-500'></i>
                                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                                </label>
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-400 mt-1 ml-6">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                            <div class="md:w-2/3">
                                                <input type="text" name="settings[{{ $setting->key }}]"
                                                    id="setting_{{ $setting->key }}" data-key="{{ $setting->key }}"
                                                    value="{{ $setting->value }}"
                                                    onchange="autoSave('{{ $setting->key }}', this.value)"
                                                    class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                                    placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}...">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class='bx bx-envelope text-4xl text-gray-300 mb-3'></i>
                                <p class="text-gray-500">No email settings found</p>
                            </div>
                        @endif
                    </div>

                    <!-- Operating Hours Tab -->
                    <div x-show="activeTab === 'hours'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="space-y-1 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Operating Hours</h3>
                            <p class="text-sm text-gray-500">Set your clinic's working hours</p>
                        </div>

                        @php
                            $startTimeSetting = isset($groupedSettings['general']) ? $groupedSettings['general']->firstWhere('key', 'operating_hours_start') : null;
                            $endTimeSetting = isset($groupedSettings['general']) ? $groupedSettings['general']->firstWhere('key', 'operating_hours_end') : null;
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Opening Time -->
                            <div
                                class="p-6 rounded-xl border border-gray-100 bg-gradient-to-br from-orange-50 to-yellow-50">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                        <i class='bx bx-sun text-2xl text-orange-500'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700">Opening Time</h4>
                                        <p class="text-xs text-gray-400">When the clinic opens</p>
                                    </div>
                                </div>
                                <input type="time" name="settings[operating_hours_start]" id="operating_hours_start"
                                    data-key="operating_hours_start" value="{{ $startTimeSetting->value ?? '09:00' }}"
                                    onchange="autoSave('operating_hours_start', this.value)"
                                    class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-3 text-lg font-semibold text-gray-700 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-center">
                            </div>

                            <!-- Closing Time -->
                            <div
                                class="p-6 rounded-xl border border-gray-100 bg-gradient-to-br from-indigo-50 to-purple-50">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class='bx bx-moon text-2xl text-indigo-500'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700">Closing Time</h4>
                                        <p class="text-xs text-gray-400">When the clinic closes</p>
                                    </div>
                                </div>
                                <input type="time" name="settings[operating_hours_end]" id="operating_hours_end"
                                    data-key="operating_hours_end" value="{{ $endTimeSetting->value ?? '17:00' }}"
                                    onchange="autoSave('operating_hours_end', this.value)"
                                    class="auto-save-input w-full rounded-lg border border-gray-200 px-4 py-3 text-lg font-semibold text-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-center">
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <i class='bx bx-info-circle text-blue-500 text-xl mt-0.5'></i>
                                <div>
                                    <p class="text-sm font-medium text-blue-700">About Operating Hours</p>
                                    <p class="text-xs text-blue-600 mt-1">These hours will be displayed to patients when
                                        booking appointments and used for schedule management.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-save configuration
            const AUTO_SAVE_URL = '{{ route("admin.settings.auto-save") }}';
            const CSRF_TOKEN = '{{ csrf_token() }}';
            let saveTimeout = null;
            let pendingSaves = new Set();

            // Auto-save function with debounce
            function autoSave(key, value) {
                // Show saving indicator
                showSaveStatus('saving');

                // Clear any existing timeout for this key
                if (saveTimeout) {
                    clearTimeout(saveTimeout);
                }

                pendingSaves.add(key);

                // Debounce the save
                saveTimeout = setTimeout(() => {
                    performSave(key, value);
                }, 500);
            }

            // Perform the actual save
            async function performSave(key, value) {
                try {
                    const response = await fetch(AUTO_SAVE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ key, value }),
                    });

                    const data = await response.json();

                    pendingSaves.delete(key);

                    if (data.success) {
                        if (pendingSaves.size === 0) {
                            showSaveStatus('saved');
                        }
                    } else {
                        showSaveStatus('error');
                        showError('Failed to save: ' + (data.message || 'Unknown error'));
                    }
                } catch (error) {
                    pendingSaves.delete(key);
                    showSaveStatus('error');
                    showError('Failed to save setting. Please try again.');
                }
            }

            // Toggle boolean settings
            function toggleSetting(key, button) {
                const currentValue = button.getAttribute('data-value');
                const newValue = currentValue === '1' ? '0' : '1';

                // Update button appearance
                button.setAttribute('data-value', newValue);
                button.classList.toggle('bg-blue-600', newValue === '1');
                button.classList.toggle('bg-green-600', newValue === '1');
                button.classList.toggle('bg-gray-200', newValue === '0');

                // Update toggle position
                const toggle = button.querySelector('span:last-child');
                toggle.classList.toggle('translate-x-5', newValue === '1');
                toggle.classList.toggle('translate-x-0', newValue === '0');

                // Update label
                const label = button.nextElementSibling;
                if (label) {
                    label.textContent = newValue === '1' ? 'Enabled' : 'Disabled';
                }

                // Auto-save
                autoSave(key, newValue);
            }

            // Show save status indicator
            function showSaveStatus(status) {
                const statusEl = document.getElementById('saveStatus');
                statusEl.classList.remove('hidden');
                statusEl.classList.add('flex');

                const icon = statusEl.querySelector('i');
                const text = statusEl.querySelector('span');

                switch (status) {
                    case 'saving':
                        statusEl.className = 'flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 border border-blue-200';
                        icon.className = 'bx bx-loader-alt animate-spin text-blue-600';
                        text.className = 'text-sm font-medium text-blue-700';
                        text.textContent = 'Saving...';
                        break;
                    case 'saved':
                        statusEl.className = 'flex items-center gap-2 px-4 py-2 rounded-lg bg-green-50 border border-green-200';
                        icon.className = 'bx bx-check-circle text-green-600';
                        text.className = 'text-sm font-medium text-green-700';
                        text.textContent = 'All changes saved';
                        // Auto-hide after 3 seconds
                        setTimeout(() => {
                            statusEl.classList.add('hidden');
                            statusEl.classList.remove('flex');
                        }, 3000);
                        break;
                    case 'error':
                        statusEl.className = 'flex items-center gap-2 px-4 py-2 rounded-lg bg-red-50 border border-red-200';
                        icon.className = 'bx bx-error-circle text-red-600';
                        text.className = 'text-sm font-medium text-red-700';
                        text.textContent = 'Failed to save';
                        break;
                }
            }

            // File size validation
            const MAX_FILE_SIZE = 2 * 1024 * 1024;

            function validateFileSize(input) {
                const fileSizeError = document.getElementById('fileSizeError');
                const newLogoPreview = document.getElementById('newLogoPreview');
                const dropZone = document.getElementById('dropZone');

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    if (file.size > MAX_FILE_SIZE) {
                        fileSizeError.classList.remove('hidden');
                        newLogoPreview.classList.add('hidden');
                        input.value = '';
                        dropZone.classList.add('border-red-400', 'bg-red-50/50');
                        dropZone.classList.remove('border-gray-300');
                        return false;
                    } else {
                        fileSizeError.classList.add('hidden');
                        dropZone.classList.remove('border-red-400', 'bg-red-50/50');
                        dropZone.classList.add('border-blue-400', 'bg-blue-50/50');
                        return true;
                    }
                }
                return false;
            }

            function previewLogo(input) {
                const preview = document.getElementById('newLogoPreview');
                const previewImage = document.getElementById('logoPreviewImage');
                const fileName = document.getElementById('fileName');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        fileName.textContent = file.name;
                        preview.classList.remove('hidden');
                    };

                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('hidden');
                }
            }

            function clearFileInput() {
                const input = document.getElementById('logo');
                const preview = document.getElementById('newLogoPreview');
                const fileSizeError = document.getElementById('fileSizeError');
                const dropZone = document.getElementById('dropZone');

                input.value = '';
                preview.classList.add('hidden');
                fileSizeError.classList.add('hidden');
                dropZone.classList.remove('border-blue-400', 'bg-blue-50/50', 'border-red-400', 'bg-red-50/50');
                dropZone.classList.add('border-gray-300');
            }

            // Drag and drop
            document.addEventListener('DOMContentLoaded', function () {
                const dropZone = document.getElementById('dropZone');
                const fileInput = document.getElementById('logo');

                if (dropZone && fileInput) {
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, preventDefaults, false);
                        document.body.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropZone.addEventListener(eventName, () => {
                            dropZone.classList.add('border-blue-500', 'bg-blue-50/50');
                            dropZone.classList.remove('border-gray-300');
                        }, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, () => {
                            if (!dropZone.classList.contains('border-blue-400')) {
                                dropZone.classList.remove('border-blue-500', 'bg-blue-50/50');
                                dropZone.classList.add('border-gray-300');
                            }
                        }, false);
                    });

                    dropZone.addEventListener('drop', function (e) {
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            fileInput.files = files;
                            if (validateFileSize(fileInput)) {
                                previewLogo(fileInput);
                            }
                        }
                    }, false);
                }
            });

            function removeLogo() {
                Swal.fire({
                    title: 'Remove Logo?',
                    text: 'Are you sure you want to remove the logo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Remove',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Removing...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        fetch('{{ route("admin.settings.remove-logo") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams({ '_method': 'DELETE' })
                        })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Removed!',
                                    text: 'Logo removed successfully',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => window.location.reload());
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to remove logo'
                                });
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection