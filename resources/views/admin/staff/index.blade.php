@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page-title', 'Staff Management')

@section('content')
    <div class="space-y-6">
        <!-- Page Header with Stats -->
        <div
            class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-identity-card text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Staff Management</h2>
                        <p class="text-cyan-100 text-sm mt-1">Manage staff profiles and information</p>
                    </div>
                </div>
                <a href="{{ route('admin.staff.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 backdrop-blur-sm text-white rounded-xl font-semibold hover:bg-white/25 active:bg-white/30 transition-all border border-white/30 shadow-lg shadow-black/10">
                    <span class="flex items-center justify-center w-6 h-6 bg-white/30 rounded-lg">
                        <i class='hgi-stroke hgi-plus-sign text-sm'></i>
                    </span>
                    Add New Staff
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $staff->total() }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Total Staff</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $staff->filter(fn($s) => !$s->trashed())->count() }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Active</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $staff->unique('department')->count() }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Departments</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">
                    {{ $staff->filter(fn($s) => $s->created_at >= now()->subDays(30))->count() }}
                </p>
                <p class="text-sm text-gray-500 font-medium mt-1">New (30 days)</p>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <i class='hgi-stroke hgi-filter text-gray-500'></i>
                    <h3 class="font-semibold text-gray-700">Filter Staff</h3>
                </div>
            </div>
            <div class="p-5">
                <form method="GET" action="{{ route('admin.staff.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class='hgi-stroke hgi-search-01'></i>
                                </span>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Search by name, position, or department..."
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm">
                            </div>
                        </div>

                        <!-- Department Filter -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-600 mb-2">Department</label>
                            <input type="text" id="department" name="department" value="{{ request('department') }}"
                                placeholder="Filter by department..."
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm bg-white">
                                <option value="active" {{ request('status') != 'deleted' ? 'selected' : '' }}>Active</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-600 text-white rounded-xl font-medium hover:bg-cyan-700 transition-all text-sm">
                            <i class='hgi-stroke hgi-search-01'></i>
                            Search
                        </button>
                        @if(request()->hasAny(['search', 'department', 'status']))
                            <a href="{{ route('admin.staff.index') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                                <i class='hgi-stroke hgi-cancel-circle'></i>
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span>Show</span>
                        <form method="GET" id="perPageForm" class="inline">
                            @foreach(request()->except(['per_page','page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="per_page" onchange="document.getElementById('perPageForm').submit()"
                                class="px-2 py-1 rounded-lg border border-gray-200 text-sm bg-white focus:border-indigo-400 focus:ring-1 focus:ring-indigo-300 transition-all cursor-pointer">
                                @foreach([10, 15, 25, 50, 100] as $limit)
                                    <option value="{{ $limit }}" {{ $perPage == $limit ? 'selected' : '' }}>{{ $limit }}</option>
                                @endforeach
                            </select>
                        </form>
                        <span>entries</span>
                    </div>
                    <p class="text-xs text-gray-500">
                        Showing {{ $staff->firstItem() ?? 0 }} &ndash; {{ $staff->lastItem() ?? 0 }} of {{ $staff->total() }} results
                    </p>
                </div>
        <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Staff</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Position</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staff as $staffMember)
                            <tr
                                class="hover:bg-gray-50/50 transition-colors {{ $staffMember->trashed() ? 'bg-red-50/30' : '' }}">
                                <!-- Staff Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($staffMember->first_name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name) }}
                                            </p>
                                            @if($staffMember->user)
                                                <p class="text-xs text-gray-500">{{ $staffMember->user->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Contact -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $staffMember->phone ?? 'N/A' }}</p>
                                </td>

                                <!-- Position -->
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-cyan-50 text-cyan-700">
                                        {{ $staffMember->position ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Department -->
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                        <i class='hgi-stroke hgi-building-01'></i>
                                        {{ $staffMember->department ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @if($staffMember->trashed())
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-inset ring-red-500/20">
                                            <i class='hgi-stroke hgi-delete-01'></i> Deleted
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-inset ring-green-500/20">
                                            <i class='hgi-stroke hgi-checkmark-circle-02'></i> Active
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($staffMember->trashed())
                                            <button
                                                onclick="restoreStaff({{ $staffMember->id }}, '{{ addslashes($staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name)) }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all"
                                                title="Restore">
                                                <i class='hgi-stroke hgi-undo text-lg'></i>
                                            </button>
                                            <button
                                                onclick="forceDeleteStaff({{ $staffMember->id }}, '{{ addslashes($staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name)) }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all"
                                                title="Delete Permanently">
                                                <i class='hgi-stroke hgi-cancel-circle text-lg'></i>
                                            </button>
                                        @else
                                            <a href="{{ route('admin.staff.show', $staffMember->id) }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all"
                                                title="View">
                                                <i class='hgi-stroke hgi-eye text-lg'></i>
                                            </a>
                                            <a href="{{ route('admin.staff.edit', $staffMember->id) }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all"
                                                title="Edit">
                                                <i class='hgi-stroke hgi-pencil-edit-01 text-lg'></i>
                                            </a>
                                            <button
                                                onclick="deleteStaff({{ $staffMember->id }}, '{{ addslashes($staffMember->full_name ?? ($staffMember->first_name . ' ' . $staffMember->last_name)) }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all"
                                                title="Delete">
                                                <i class='hgi-stroke hgi-delete-01 text-lg'></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class='hgi-stroke hgi-identity-card text-4xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No staff found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new staff
                                            member</p>
                                        <a href="{{ route('admin.staff.create') }}"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-600 text-white rounded-xl font-medium hover:bg-cyan-700 transition-all text-sm mt-4">
                                            <i class='hgi-stroke hgi-plus-sign'></i>
                                            Add New Staff
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($staff->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-sm text-gray-600">
                        Showing {{ $staff->firstItem() }} to {{ $staff->lastItem() }} of {{ $staff->total() }} results
                    </p>
                    <div>
                        {{ $staff->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function deleteStaff(id, name) {
                Swal.fire({
                    title: 'Delete Staff?',
                    html: `Are you sure you want to delete <strong>${name}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="hgi-stroke hgi-delete-01 mr-1"></i> Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/staff/${id}`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function restoreStaff(id, name) {
                Swal.fire({
                    title: 'Restore Staff?',
                    html: `Are you sure you want to restore <strong>${name}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="hgi-stroke hgi-undo mr-1"></i> Restore',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Restoring...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/staff/${id}/restore`;
                        form.innerHTML = `@csrf`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function forceDeleteStaff(id, name) {
                Swal.fire({
                    title: 'Permanently Delete?',
                    html: `<div class="text-left">
                                                <p>Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${name}</strong>?</p>
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                                    <p class="text-sm text-red-700"><i class='hgi-stroke hgi-alert-circle mr-1'></i> This cannot be undone!</p>
                                                </div>
                                            </div>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Delete Permanently',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/staff/${id}/force-delete`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
@endsection