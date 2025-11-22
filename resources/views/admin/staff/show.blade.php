@extends('layouts.admin')

@section('title', 'Staff Details')
@section('page-title', 'Staff Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Staff Information</h3>
            <div class="flex space-x-2">
                @if(!$staff->trashed())
                <a href="{{ route('admin.staff.edit', $staff->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-edit mr-2'></i> Edit
                </a>
                @else
                <form action="{{ route('admin.staff.restore', $staff->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-refresh mr-2'></i> Restore
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Staff Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full bg-yellow-100 flex items-center justify-center">
                            <span class="text-yellow-600 font-bold text-2xl">{{ strtoupper(substr($staff->first_name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $staff->full_name }}</h2>
                            <p class="text-yellow-600 font-semibold">{{ $staff->staff_id ?? 'N/A' }}</p>
                            @if($staff->position)
                                <p class="text-gray-600">{{ $staff->position }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($staff->trashed())
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
                    @if($staff->user)
                    <div>
                        <label class="text-sm font-medium text-gray-500">User Account</label>
                        <p class="mt-1 text-gray-900">{{ $staff->user->email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Staff ID</label>
                    <p class="mt-1 text-gray-900 font-semibold text-yellow-600">{{ $staff->staff_id ?? 'N/A' }}</p>
                </div>
                @if($staff->phone)
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $staff->phone }}</p>
                </div>
                @endif
                @if($staff->department)
                <div>
                    <label class="text-sm font-medium text-gray-500">Department</label>
                    <p class="mt-1 text-gray-900">{{ $staff->department }}</p>
                </div>
                @endif
                @if($staff->hire_date)
                <div>
                    <label class="text-sm font-medium text-gray-500">Hire Date</label>
                    <p class="mt-1 text-gray-900">{{ $staff->hire_date->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Notes -->
            @if($staff->notes)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $staff->notes }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-8 border-t pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $staff->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $staff->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($staff->trashed())
                <div>
                    <label class="text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-gray-900">{{ $staff->deleted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

