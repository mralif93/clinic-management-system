@extends('layouts.admin')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-bold select-none shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                <p class="text-blue-100 text-sm mt-0.5 flex items-center gap-2 flex-wrap">
                    <span class="flex items-center gap-1"><i class='hgi-stroke hgi-mail-01'></i> {{ $user->email }}</span>
                    <span class="px-2 py-0.5 bg-white/20 rounded-lg text-xs font-semibold">{{ ucfirst($user->role) }}</span>
                    @if($user->position)
                        <span class="text-blue-200">· {{ $user->position }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Personal Information --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <i class='hgi-stroke hgi-user text-blue-500'></i>
            <h3 class="text-sm font-semibold text-gray-900">Personal Information</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.profile.update-details') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Row 1: Name + Email --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-2.5 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-3 py-2.5 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Row 2: Phone + Position --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="+60 12 345 6789"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Position / Title</label>
                        <input type="text" name="position" value="{{ old('position', $user->position) }}"
                            placeholder="e.g. Clinic Manager"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('position')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Row 3: Date of Birth + Gender --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Date of Birth</label>
                        <input type="date" name="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                            max="{{ now()->subDay()->format('Y-m-d') }}"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Gender</label>
                        <select name="gender"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— Select —</option>
                            <option value="male"   {{ old('gender', $user->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other"  {{ old('gender', $user->gender) === 'other'  ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Address</label>
                    <textarea name="address" rows="2"
                        placeholder="Street, City, Postcode"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('address', $user->address) }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Bio / Notes</label>
                    <textarea name="bio" rows="3"
                        placeholder="Short description about yourself…"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/25">
                        <i class='hgi-stroke hgi-floppy-disk'></i> Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <i class='hgi-stroke hgi-lock-01 text-red-500'></i>
            <h3 class="text-sm font-semibold text-gray-900">Change Password</h3>
        </div>
        <div class="p-6 space-y-4">
            <form action="{{ route('admin.profile.update-password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="current_password" required
                        class="w-full px-3 py-2.5 border @error('current_password') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-3 py-2.5 border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Min. 8 characters">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Smaller, inline-aligned info notice --}}
                <p class="flex items-center gap-1.5 text-xs text-amber-600">
                    <i class='hgi-stroke hgi-information-circle text-sm'></i>
                    You must enter your current password before setting a new one.
                </p>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-500/25">
                        <i class='hgi-stroke hgi-lock-open-01'></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Account Info (read-only) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <i class='hgi-stroke hgi-information-circle text-gray-400'></i>
            <h3 class="text-sm font-semibold text-gray-900">Account Information</h3>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Role</dt>
                    <dd>
                        <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                            {{ ucfirst($user->role) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Email Verified</dt>
                    <dd>
                        @if($user->email_verified_at)
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold flex items-center gap-1 w-fit">
                                <i class='hgi-stroke hgi-checkmark-circle-02'></i> Verified
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs font-semibold">Not Verified</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Member Since</dt>
                    <dd class="text-gray-700 text-xs">{{ $user->created_at->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Last Updated</dt>
                    <dd class="text-gray-700 text-xs">{{ $user->updated_at->format('d M Y, h:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

</div>
@endsection
