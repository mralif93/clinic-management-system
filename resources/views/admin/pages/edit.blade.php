@extends('layouts.admin')

@section('title', isset($page) ? 'Edit Page' : ($mode === 'team' ? 'Edit Team Page' : ($mode === 'packages' ? 'Edit Packages Page' : 'Edit About Page')))
@section('page-title', isset($page) ? 'Edit Page: ' . $page->title : ($mode === 'team' ? 'Edit Team Page' : ($mode === 'packages' ? 'Edit Packages Page' : 'Edit About Page')))

@section('content')
    @if(isset($page))
        {{-- Custom Page Edit --}}
        <div class="space-y-6">
            <!-- Page Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                                <i class='bx bx-edit text-2xl'></i>
                            </div>
                            Edit Page: {{ $page->title }}
                        </h1>
                        <p class="mt-2 text-indigo-100">Update page content and settings</p>
                    </div>
                    <a href="{{ route('admin.pages.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Page Details Section -->
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-info-circle text-indigo-600'></i>
                            Page Details
                        </h3>
                    </div>
                    <div class="p-6 border-b border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Page Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Page Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required autofocus
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('title') border-red-500 @enderror"
                                    placeholder="Enter page title">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                    Slug
                                </label>
                                <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('slug') border-red-500 @enderror"
                                    placeholder="page-slug">
                                @error('slug')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                        {{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">URL-friendly identifier</p>
                            </div>

                            <!-- Order -->
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Order
                                </label>
                                <input type="number" id="order" name="order" value="{{ old('order', $page->order) }}" min="0"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('order') border-red-500 @enderror"
                                    placeholder="0">
                                @error('order')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                        {{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <label
                                    class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                                        class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700">Published</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-file-blank text-indigo-600'></i>
                            Content
                        </h3>
                    </div>
                    <div class="p-6 border-b border-gray-100">
                        <textarea id="content" name="content" rows="10"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm font-mono"
                            placeholder='Enter JSON content structure'>{{ old('content', $page->content ? json_encode($page->content, JSON_PRETTY_PRINT) : '') }}</textarea>
                        <p class="mt-2 text-xs text-gray-500">Enter content as JSON. For custom pages, use a structured format.</p>
                    </div>

                    <!-- SEO Section -->
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-search text-indigo-600'></i>
                            SEO Settings
                        </h3>
                    </div>
                    <div class="p-6 border-b border-gray-100">
                        <div class="space-y-6">
                            <!-- Meta Title -->
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Title
                                </label>
                                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" maxlength="255"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('meta_title') border-red-500 @enderror"
                                    placeholder="SEO title (leave empty to use page title)">
                                @error('meta_title')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Description
                                </label>
                                <textarea id="meta_description" name="meta_description" rows="3" maxlength="500"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm @error('meta_description') border-red-500 @enderror"
                                    placeholder="SEO description (recommended: 150-160 characters)">{{ old('meta_description', $page->meta_description) }}</textarea>
                                @error('meta_description')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.pages.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-600/20">
                            <i class='bx bx-save'></i>
                            Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        {{-- System Page Edit (About/Team/Packages) --}}
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-blue-600 mb-1">{{ $mode === 'team' ? 'Team page' : ($mode === 'packages' ? 'Packages page' : 'About page') }}</p>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $mode === 'team' ? 'Our Team Content' : ($mode === 'packages' ? 'Packages Content' : 'About Us Content') }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Edit the {{ $mode === 'team' ? 'team hero, leadership, and care teams.' : ($mode === 'packages' ? 'hero and special packages.' : 'hero, values, and timeline.') }}</p>
                </div>
                <a href="{{ route('admin.pages.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-left-arrow-alt text-lg'></i>
                    Back to Pages
                </a>
            </div>

            @if($mode === 'about' && isset($modulePage))
            <!-- Module Visibility Control for About Page -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-2">
                        <i class='bx bx-toggle-left text-blue-600'></i>
                        <h3 class="font-semibold text-gray-700">Module Visibility & Order</h3>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Control whether the About page appears on the public website</p>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Visibility Toggle -->
                        <div class="border border-gray-200 rounded-xl p-4 {{ $modulePage->is_published ? 'bg-green-50/50 border-green-200' : 'bg-gray-50/50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class='bx bx-info-circle text-xl text-blue-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Visibility Status</h4>
                                        <p class="text-xs text-gray-500">Public website</p>
                                    </div>
                                </div>
                                @if($modulePage->is_published)
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                        <i class='bx bx-check-circle'></i> Visible
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                                        <i class='bx bx-x-circle'></i> Hidden
                                    </span>
                                @endif
                            </div>
                            <form action="{{ route('admin.pages.about.toggle-visibility') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                    class="w-full px-3 py-2 rounded-lg text-sm font-medium transition-all
                                    {{ $modulePage->is_published ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                    <i class='bx {{ $modulePage->is_published ? 'bx-hide' : 'bx-show' }} mr-1'></i>
                                    {{ $modulePage->is_published ? 'Hide' : 'Show' }} Page
                                </button>
                            </form>
                        </div>

                        <!-- Order Control -->
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class='bx bx-sort text-xl text-blue-600'></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Display Order</h4>
                                    <p class="text-xs text-gray-500">Navigation position</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.pages.about.update-order') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="number" name="order" value="{{ $modulePage->order }}" min="0" 
                                    class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                                <button type="submit" 
                                    class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                                    <i class='bx bx-check mr-1'></i> Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                @include('admin.settings.partials.pages', ['mode' => $mode])
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    @if(!isset($page))
        {{-- Auto-save scripts only for system pages --}}
        <script>
            // Auto-save configuration (shared with settings)
            const AUTO_SAVE_URL = '{{ route("admin.settings.auto-save") }}';
            const CSRF_TOKEN = '{{ csrf_token() }}';
            let saveTimeout = null;
            let pendingSaves = new Set();
            let lastSavePayload = null;

            // Status via SweetAlert toast (with fallback)
            let lastStatusToastAt = 0;
            function showStatusToast(kind) {
                const title = kind === 'saving'
                    ? 'Saving...'
                    : kind === 'error'
                        ? 'Failed to save'
                        : 'All changes saved';
                const icon = kind === 'saving'
                    ? 'info'
                    : kind === 'error'
                        ? 'error'
                        : 'success';

                const now = Date.now();
                const minGap = 500;
                const delay = kind === 'saved' && now - lastStatusToastAt < minGap
                    ? minGap - (now - lastStatusToastAt)
                    : 0;
                const fire = () => {
                    lastStatusToastAt = Date.now();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon,
                            title,
                            showConfirmButton: false,
                            timer: kind === 'saving' ? 1200 : 1600,
                            timerProgressBar: true,
                            customClass: { popup: 'swal-toast-fixed' }
                        });
                        ensureToastStyle();
                        return;
                    }

                    const toast = document.createElement('div');
                    toast.textContent = title;
                    toast.className = 'swal-toast-fallback';
                    document.body.appendChild(toast);
                    requestAnimationFrame(() => toast.classList.add('show'));
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 250);
                    }, kind === 'saving' ? 1200 : 1600);
                };

                if (delay > 0) {
                    setTimeout(fire, delay);
                } else {
                    fire();
                }
            }

            function performSave(key, value) {
                const payload = { key, value };

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
                    .then((data) => {
                        pendingSaves.delete(key);
                        showStatusToast('saved');
                    })
                    .catch(() => {
                        pendingSaves.delete(key);
                        showStatusToast('error');
                    });
            }

            function autoSave(key, value) {
                lastSavePayload = { key, value };
                pendingSaves.add(key);

                if (saveTimeout) clearTimeout(saveTimeout);

                saveTimeout = setTimeout(() => {
                    if (!lastSavePayload) return;
                    showStatusToast('saving');
                    performSave(lastSavePayload.key, lastSavePayload.value);
                }, 1200);
            }

            function ensureToastStyle() {
                const id = 'swal-toast-fixed-style';
                if (document.getElementById(id)) return;
                const style = document.createElement('style');
                style.id = id;
                style.textContent = `
                    .swal-toast-fixed { margin-top: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); }
                    .swal2-container.swal2-top-end { padding: 12px; }
                    .swal-toast-fallback {
                        position: fixed;
                        top: 16px;
                        right: 16px;
                        z-index: 9999;
                        padding: 10px 14px;
                        border-radius: 10px;
                        background: #2563eb;
                        color: #fff;
                        font-size: 14px;
                        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                        opacity: 0;
                        transition: opacity 0.2s ease;
                    }
                    .swal-toast-fallback.show { opacity: 1; }
                `;
                document.head.appendChild(style);
            }
        </script>
    @endif
@endpush
