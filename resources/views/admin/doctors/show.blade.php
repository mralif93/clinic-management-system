@extends('layouts.admin')

@section('title', 'Doctor Details')
@section('page-title', 'Doctor Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Doctor Information</h3>
            <div class="flex space-x-2">
                @if(!$doctor->trashed())
                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-edit mr-2'></i> Edit
                </a>
                @else
                <form action="{{ route('admin.doctors.restore', $doctor->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-refresh mr-2'></i> Restore
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Doctor Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-600 font-bold text-2xl">{{ strtoupper(substr($doctor->first_name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $doctor->full_name }}</h2>
                            <p class="text-green-600 font-semibold">{{ $doctor->doctor_id ?? 'N/A' }}</p>
                            @if($doctor->qualification)
                                <p class="text-gray-600">{{ $doctor->qualification }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($doctor->trashed())
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Deleted
                                </span>
                            @elseif($doctor->is_available)
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Available
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Unavailable
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($doctor->user)
                    <div>
                        <label class="text-sm font-medium text-gray-500">User Account</label>
                        <p class="mt-1 text-gray-900">
                            <a href="{{ route('admin.users.show', $doctor->user->id) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $doctor->user->email }}
                            </a>
                        </p>
                    </div>
                    @else
                    <div>
                        <label class="text-sm font-medium text-gray-500">User Account</label>
                        <p class="mt-1 text-gray-500">Not linked to any user account</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-gray-500">Type</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($doctor->type) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="mt-1 text-gray-900">{{ $doctor->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $doctor->phone ?? 'N/A' }}</p>
                </div>
                @if($doctor->specialization)
                <div>
                    <label class="text-sm font-medium text-gray-500">Specialization</label>
                    <p class="mt-1 text-gray-900">{{ $doctor->specialization }}</p>
                </div>
                @endif
            </div>

            <!-- Bio -->
            @if($doctor->bio)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bio</h3>
                <p class="text-gray-900">{{ $doctor->bio }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-8 border-t pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $doctor->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $doctor->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($doctor->trashed())
                <div>
                    <label class="text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-gray-900">{{ $doctor->deleted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

