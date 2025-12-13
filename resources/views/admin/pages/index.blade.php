@extends('layouts.admin')

@section('title', 'Pages')
@section('page-title', 'Pages')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pages</h1>
                    <p class="mt-1 text-sm text-gray-500">Edit About Us, Our Team, and Packages page content</p>
                </div>
                <div id="saveStatus"
                    class="hidden items-center gap-2 px-4 py-2 rounded-lg bg-green-50 border border-green-200">
                    <i class='bx bx-check-circle text-green-600'></i>
                    <span class="text-sm font-medium text-green-700">All changes saved</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.pages.about') }}" class="group block p-5 rounded-xl border border-gray-100 hover:border-blue-200 hover:shadow-md transition bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-blue-600 mb-1">Page</p>
                        <h3 class="text-lg font-semibold text-gray-900">About Us</h3>
                        <p class="text-sm text-gray-500 mt-1">Hero, values, and timeline content.</p>
                    </div>
                    <span class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class='bx bx-info-circle text-lg'></i>
                    </span>
                </div>
                <div class="mt-4 flex items-center text-sm text-blue-600 font-semibold">
                    <span>Edit content</span>
                    <i class='bx bx-right-arrow-alt ml-2 text-lg transition group-hover:translate-x-1'></i>
                </div>
            </a>

            <a href="{{ route('admin.pages.team') }}" class="group block p-5 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-indigo-600 mb-1">Page</p>
                        <h3 class="text-lg font-semibold text-gray-900">Our Team</h3>
                        <p class="text-sm text-gray-500 mt-1">Hero, leadership, and care teams.</p>
                    </div>
                    <span class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class='bx bx-group text-lg'></i>
                    </span>
                </div>
                <div class="mt-4 flex items-center text-sm text-indigo-600 font-semibold">
                    <span>Edit content</span>
                    <i class='bx bx-right-arrow-alt ml-2 text-lg transition group-hover:translate-x-1'></i>
                </div>
            </a>

            <a href="{{ route('admin.pages.packages') }}" class="group block p-5 rounded-xl border border-gray-100 hover:border-purple-200 hover:shadow-md transition bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-purple-600 mb-1">Page</p>
                        <h3 class="text-lg font-semibold text-gray-900">Packages</h3>
                        <p class="text-sm text-gray-500 mt-1">Hero and special packages.</p>
                    </div>
                    <span class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class='bx bx-package text-lg'></i>
                    </span>
                </div>
                <div class="mt-4 flex items-center text-sm text-purple-600 font-semibold">
                    <span>Edit content</span>
                    <i class='bx bx-right-arrow-alt ml-2 text-lg transition group-hover:translate-x-1'></i>
                </div>
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-save configuration (reused from settings)
        const AUTO_SAVE_URL = '{{ route("admin.settings.auto-save") }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        let saveTimeout = null;
        let pendingSaves = new Set();

        function showSaveStatus(state) {
            const el = document.getElementById('saveStatus');
            if (!el) return;
            if (state === 'saving') {
                el.classList.remove('hidden');
                el.classList.remove('bg-green-50', 'border-green-200');
                el.classList.add('bg-blue-50', 'border-blue-200');
                el.querySelector('span').textContent = 'Saving...';
                el.querySelector('i').classList.remove('bx-check-circle', 'text-green-600');
                el.querySelector('i').classList.add('bx-loader', 'bx-spin', 'text-blue-600');
            } else if (state === 'saved') {
                el.classList.remove('hidden');
                el.classList.remove('bg-blue-50', 'border-blue-200');
                el.classList.add('bg-green-50', 'border-green-200');
                el.querySelector('span').textContent = 'All changes saved';
                el.querySelector('i').classList.remove('bx-loader', 'bx-spin', 'text-blue-600');
                el.querySelector('i').classList.add('bx-check-circle', 'text-green-600');
                setTimeout(() => el.classList.add('hidden'), 1500);
            }
        }

        function performSave(key, value) {
            const payload = {
                key: key,
                value: value
            };

            fetch(AUTO_SAVE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
                .then(response => response.json())
                .then(data => {
                    pendingSaves.delete(key);
                    if (pendingSaves.size === 0) {
                        showSaveStatus('saved');
                    }
                })
                .catch(() => {
                    pendingSaves.delete(key);
                });
        }

        function autoSave(key, value) {
            showSaveStatus('saving');

            if (saveTimeout) {
                clearTimeout(saveTimeout);
            }

            pendingSaves.add(key);

            saveTimeout = setTimeout(() => {
                performSave(key, value);
            }, 500);
        }
    </script>
@endpush


