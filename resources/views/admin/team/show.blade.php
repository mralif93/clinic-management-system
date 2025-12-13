@extends('layouts.admin')

@section('title', 'Team Member Details')
@section('page-title', 'Team Member Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        @php
                            $photo = $teamMember->photo;
                            $initial = strtoupper(substr($teamMember->name ?? '', 0, 1));
                            if (!$initial) {
                                $initial = 'A';
                            }
                        @endphp
                        <div class="avatar-fallback absolute inset-0 rounded-full bg-white/30 flex items-center justify-center font-bold text-3xl">
                            {{ $initial }}
                        </div>
                        @if($photo)
                            <img src="{{ str_starts_with($photo, 'data:') ? $photo : (str_starts_with($photo, 'http') ? $photo : asset('storage/' . $photo)) }}" 
                                alt="{{ $teamMember->name }}"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white/30 relative z-10"
                                onerror="this.style.display='none'; this.previousElementSibling.style.display='flex';"
                                onload="this.previousElementSibling.style.display='none';">
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $teamMember->name }}</h1>
                        @if($teamMember->title)
                            <p class="text-indigo-100 mt-1">{{ $teamMember->title }}</p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($teamMember->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-trash mr-1'></i> Deleted
                                </span>
                            @elseif($teamMember->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-400/30">
                                    <i class='bx bx-check-circle mr-1'></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-400/30">
                                    <i class='bx bx-pause-circle mr-1'></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(!$teamMember->trashed())
                        <a href="{{ route('admin.team.edit', $teamMember->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg">
                            <i class='bx bx-edit'></i>
                            Edit Member
                        </a>
                    @else
                        <form action="{{ route('admin.team.restore', $teamMember->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-600 rounded-xl font-semibold hover:bg-green-50 transition-all shadow-lg">
                                <i class='bx bx-refresh'></i>
                                Restore Member
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.team.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Photo Card -->
            @if($teamMember->photo)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class='bx bx-image text-indigo-600'></i>
                    Photo
                </h3>
                <div class="flex justify-center">
                    <img src="{{ str_starts_with($teamMember->photo, 'data:') ? $teamMember->photo : (str_starts_with($teamMember->photo, 'http') ? $teamMember->photo : asset('storage/' . $teamMember->photo)) }}" 
                         alt="{{ $teamMember->name }}" 
                         class="w-48 h-48 rounded-full object-cover border-4 border-indigo-100 shadow-lg">
                </div>
            </div>
            @endif

            <!-- Member Info Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class='bx bx-info-circle text-indigo-600'></i>
                    Member Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $teamMember->name }}</p>
                    </div>
                    @if($teamMember->title)
                    <div>
                        <p class="text-sm text-gray-500">Title</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $teamMember->title }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Display Order</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $teamMember->order }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $teamMember->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio Card -->
        @if($teamMember->bio)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class='bx bx-file-blank text-indigo-600'></i>
                Bio
            </h3>
            <p class="text-gray-700 leading-relaxed">{{ $teamMember->bio }}</p>
        </div>
        @endif
    </div>
@endsection
