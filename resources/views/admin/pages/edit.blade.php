@extends('layouts.admin')

@section('title', 'Pages')
@section('page-title', $mode === 'team' ? 'Edit Team Page' : ($mode === 'packages' ? 'Edit Packages Page' : 'Edit About Page'))

@section('content')
    <div class="max-w-6xl mx-auto">
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            @include('admin.settings.partials.pages', ['mode' => $mode])
        </div>
    </div>
@endsection

@push('scripts')
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
@endpush
