@extends('layouts.admin')

@section('title', 'Team Management')
@section('page-title', 'Team Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
        <i class='hgi-stroke hgi-user-group text-2xl'></i>
    </div>
    <div>
        <h2 class="text-2xl font-bold">Team Members</h2>
        <p class="text-indigo-100 text-sm mt-1">Manage your clinic team members</p>
    </div>
</div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                    <i class='hgi-stroke hgi-plus-sign text-xl'></i>
                    Add New Member
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $totalMembers = $teamMembers->total();
                $activeMembers = \App\Models\TeamMember::where('is_active', true)->count();
                $deletedMembers = \App\Models\TeamMember::onlyTrashed()->count();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $totalMembers }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Total Members</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $activeMembers }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Active</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $deletedMembers }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Deleted</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ $totalMembers - $activeMembers }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Inactive</p>
            </div>
        </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='hgi-stroke hgi-filter text-gray-500'></i>
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
                                <i class='hgi-stroke hgi-search-01'></i>
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
                        <i class='hgi-stroke hgi-search-01'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.team.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='hgi-stroke hgi-cancel-circle'></i>
                            Clear
                        </a>
                    @endif
                    @if(request('deleted') == '1')
                        <a href="{{ route('admin.team.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='hgi-stroke hgi-cancel-circle'></i>
                            Show Active
                        </a>
                    @else
                        <a href="{{ route('admin.team.index', ['deleted' => '1']) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-100 text-red-700 rounded-xl font-medium hover:bg-red-200 transition-all text-sm">
                            <i class='hgi-stroke hgi-delete-01'></i>
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
                                <i class='hgi-stroke hgi-rotate-left-01 text-lg'></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.team.force-delete', $member->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Permanently delete this team member? This cannot be undone!')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                <i class='hgi-stroke hgi-cancel-circle text-lg'></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.team.show', $member->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                            <i class='hgi-stroke hgi-eye text-lg'></i>
                        </a>
                        <a href="{{ route('admin.team.edit', $member->id) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                            <i class='hgi-stroke hgi-pencil-edit-01 text-lg'></i>
                        </a>
                        <form action="{{ route('admin.team.destroy', $member->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this team member?')"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                <i class='hgi-stroke hgi-delete-01 text-lg'></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='hgi-stroke hgi-user-group text-4xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No team members found</p>
                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new team member</p>
                    <a href="{{ route('admin.team.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm mt-4">
                        <i class='hgi-stroke hgi-plus-sign'></i>
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
