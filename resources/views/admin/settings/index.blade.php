@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST">
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
                @foreach($groupedSettings['general'] as $setting)
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
@endsection

