@extends('layouts.admin')

@section('title', 'Attendance Corrections')
@section('page-title', 'Correction Requests')

@section('content')
<div class="w-full space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.attendance.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-arrow-back text-2xl'></i>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Pending Requests</h2>
        </div>
        <div class="bg-yellow-100 px-4 py-2 rounded-lg">
            <span class="text-yellow-800 font-semibold">{{ $corrections->count() }} Pending</span>
        </div>
    </div>

    <!-- Corrections List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($corrections as $correction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class='bx bx-user text-blue-600'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $correction->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($correction->user->role) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $correction->attendance->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>In: {{ $correction->attendance->clock_in_time->format('h:i A') }}</div>
                                <div>Out: {{ $correction->attendance->clock_out_time ? $correction->attendance->clock_out_time->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                <div>In: {{ $correction->clock_in_time->format('h:i A') }}</div>
                                <div>Out: {{ $correction->clock_out_time ? $correction->clock_out_time->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $correction->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.attendance.corrections.approve', $correction) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition text-xs font-semibold">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.attendance.corrections.reject', $correction) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition text-xs font-semibold">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class='bx bx-check-circle text-4xl mb-2 text-green-500'></i>
                                <p>No pending correction requests!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
