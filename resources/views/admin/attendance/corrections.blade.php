@extends('layouts.admin')

@section('title', 'Attendance Corrections')
@section('page-title', 'Correction Requests')

@section('content')
<div class="w-full space-y-6">
    <div class="bg-gradient-to-r from-teal-600 to-cyan-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                    <i class='hgi-stroke hgi-calendar-03 text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Correction Requests</h2>
                    <p class="text-teal-100 text-sm mt-1">Manage pending attendance correction requests</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white/20 backdrop-blur px-4 py-2 rounded-xl border border-white/20">
                    <span class="text-white font-semibold">{{ $corrections->count() }} Pending</span>
                </div>
                <a href="{{ route('admin.attendance.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='hgi-stroke hgi-arrow-left-01'></i>
                    Back to List
                </a>
            </div>
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
                                        <i class='hgi-stroke hgi-user text-blue-600'></i>
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <form action="{{ route('admin.attendance.corrections.approve', $correction) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm" title="Approve">
                                            <i class='hgi-stroke hgi-checkmark-circle-02 text-base'></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.attendance.corrections.reject', $correction) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm" title="Reject">
                                            <i class='hgi-stroke hgi-cancel-circle text-base'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class='hgi-stroke hgi-checkmark-circle-02 text-4xl mb-2 text-green-500'></i>
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
