@extends('layouts.admin')

@section('title', 'Team Management')
@section('page-title', 'Team Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-group text-2xl'></i>
                    </div>
                    Team Members
                </h1>
                <p class="mt-2 text-indigo-100">Manage your clinic team members</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Add New Member
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalMembers = $teamMembers->total();
                $activeMembers = \App\Models\TeamMember::where('is_active', true)->count();
                $deletedMembers = \App\Models\TeamMember::onlyTrashed()->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalMembers }}</p>
                <p class="text-sm text-indigo-200">Total Members</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $activeMembers }}</p>
                <p class="text-sm text-indigo-200">Active</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $deletedMembers }}</p>
                <p class="text-sm text-indigo-200">Deleted</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalMembers - $activeMembers }}</p>
                <p class="text-sm text-indigo-200">Inactive</p>
            </div>
        </div>
    </div>

    <!-- Module Visibility Control -->
    @if($modulePage)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-toggle-left text-indigo-600'></i>
                <h3 class="font-semibold text-gray-700">Module Visibility & Order</h3>
            </div>
            <p class="text-sm text-gray-500 mt-1">Control whether the Team module appears on the public website</p>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Visibility Toggle -->
                <div class="border border-gray-200 rounded-xl p-4 {{ $modulePage->is_published ? 'bg-green-50/50 border-green-200' : 'bg-gray-50/50' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i class='bx bx-group text-xl text-indigo-600'></i>
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
                    <form action="{{ route('admin.team.toggle-visibility') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                            class="w-full px-3 py-2 rounded-lg text-sm font-medium transition-all
                            {{ $modulePage->is_published ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            <i class='bx {{ $modulePage->is_published ? 'bx-hide' : 'bx-show' }} mr-1'></i>
                            {{ $modulePage->is_published ? 'Hide' : 'Show' }} Module
                        </button>
                    </form>
                </div>

                <!-- Order Control -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class='bx bx-sort text-xl text-indigo-600'></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Display Order</h4>
                            <p class="text-xs text-gray-500">Navigation position</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.team.update-order') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="number" name="order" value="{{ $modulePage->order }}" min="0" 
                            class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                        <button type="submit" 
                            class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-200 transition">
                            <i class='bx bx-check mr-1'></i> Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Team Members</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.team.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by name, title, or bio..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.team.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif
                    @if(request('deleted') == '1')
                        <a href="{{ route('admin.team.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Show Active
                        </a>
                    @else
                        <a href="{{ route('admin.team.index', ['deleted' => '1']) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-100 text-red-700 rounded-xl font-medium hover:bg-red-200 transition-all text-sm">
                            <i class='bx bx-trash'></i>
                            Show Deleted
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Team Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teamMembers as $member)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all group {{ $member->trashed() ? 'opacity-60' : '' }}">
                <!-- Card Header -->
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 flex-shrink-0 relative">
                                @php
                                    $photo = $member->photo;
                                    $initial = strtoupper(substr($member->name ?? '', 0, 1));
                                    if (!$initial) {
                                        $initial = 'A';
                                    }
                                @endphp
                                <div class="avatar-fallback absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-lg">
                                    {{ $initial }}
                                </div>
                                @if($photo)
                                    <img src="{{ str_starts_with($photo, 'data:') ? $photo : (str_starts_with($photo, 'http') ? $photo : asset('storage/' . $photo)) }}" 
                                        alt="{{ $member->name }}"
                                        class="w-16 h-16 rounded-full object-cover border-2 border-indigo-50 shadow-lg relative z-10"
                                        onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';"
                                        onload="this.previousElementSibling.style.display='none';">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $member->name }}</h3>
                                @if($member->title)
                                    <p class="text-sm text-gray-500">{{ $member->title }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        @if($member->trashed())
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700">Deleted</span>
                        @elseif($member->is_active)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    @if($member->bio)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ Str::limit($member->bio, 120) }}</p>
                    @endif
                    
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500">Display Order</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $member->order }}</p>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    @if($member->trashed())
                        <form action="{{ route('admin.team.restore', $member->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Restore this team member?')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                <i class='bx bx-undo text-lg'></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.team.force-delete', $member->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Permanently delete this team member? This cannot be undone!')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                <i class='bx bx-x-circle text-lg'></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.team.show', $member->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                            <i class='bx bx-show text-lg'></i>
                        </a>
                        <a href="{{ route('admin.team.edit', $member->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                            <i class='bx bx-edit text-lg'></i>
                        </a>
                        <form action="{{ route('admin.team.destroy', $member->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this team member?')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                <i class='bx bx-trash text-lg'></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-group text-4xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No team members found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new team member</p>
                    <a href="{{ route('admin.team.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm mt-4">
                        <i class='bx bx-plus'></i>
                        Add New Member
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($teamMembers->hasPages())
        <div class="flex justify-center">
            {{ $teamMembers->links() }}
        </div>
    @endif
</div>
@endsection
