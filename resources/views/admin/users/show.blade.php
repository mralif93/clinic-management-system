@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
            <div class="flex space-x-2">
                @if(!$user->trashed())
                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-edit mr-2'></i> Edit
                </a>
                @else
                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-refresh mr-2'></i> Restore
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <!-- User Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Avatar & Basic Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Role</label>
                        <p class="mt-1">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'patient' => 'bg-blue-100 text-blue-800',
                                    'doctor' => 'bg-green-100 text-green-800',
                                    'staff' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $roleColor }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($user->trashed())
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Deleted
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security Information -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class='bx bx-shield-alt-2 mr-2 text-blue-600'></i>
                    Security Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Account Status</label>
                        <p class="mt-1">
                            @if($user->isLocked())
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class='bx bx-lock mr-1'></i> Locked
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    (Unlocks in {{ round($user->getRemainingLockoutMinutes()) }} minutes)
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class='bx bx-check-circle mr-1'></i> Unlocked
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Failed Login Attempts</label>
                        <p class="mt-1">
                            <span class="text-lg font-semibold {{ $user->failed_login_attempts >= 3 ? 'text-red-600' : ($user->failed_login_attempts > 0 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $user->failed_login_attempts }}/5
                            </span>
                            @if($user->failed_login_attempts > 0)
                                <span class="text-sm text-gray-600 ml-2">
                                    ({{ 5 - $user->failed_login_attempts }} remaining)
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($user->locked_until)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Locked Until</label>
                        <p class="mt-1 text-gray-900">{{ $user->locked_until->format('M d, Y H:i:s') }}</p>
                    </div>
                    @endif
                </div>
                @if($user->isLocked() || $user->failed_login_attempts > 0)
                <div class="mt-4 flex space-x-2">
                    @if($user->isLocked())
                    <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class='bx bx-lock-open mr-2'></i> Unlock Account
                        </button>
                    </form>
                    @endif
                    @if($user->failed_login_attempts > 0)
                    <form action="{{ route('admin.users.reset-attempts', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class='bx bx-refresh mr-2'></i> Reset Attempts
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            </div>

            <!-- Additional Details -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Email Verified</label>
                    <p class="mt-1 text-gray-900">
                        @if($user->email_verified_at)
                            <span class="text-green-600">Yes</span> - {{ $user->email_verified_at->format('M d, Y H:i') }}
                        @else
                            <span class="text-red-600">No</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($user->trashed())
                <div>
                    <label class="text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-gray-900">{{ $user->deleted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

