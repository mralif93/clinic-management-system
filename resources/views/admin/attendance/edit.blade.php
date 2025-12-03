@extends('layouts.admin')

@section('title', 'Edit Attendance')
@section('page-title', 'Edit Attendance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.attendance.by-month', ['year' => $attendance->date->year, 'month' => $attendance->date->month]) }}"
                class="bg-white p-2 rounded-full shadow-sm hover:shadow-md transition-shadow text-gray-600">
                <i class='bx bx-arrow-back text-2xl'></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Attendance</h1>
                <p class="text-gray-600 mt-1">{{ $attendance->user->name }} - {{ $attendance->date->format('l, F d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('admin.attendance.update', $attendance) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Employee Info (Read-only) -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class='bx bx-user text-2xl text-blue-600'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Employee</p>
                        <h3 class="text-lg font-bold text-gray-800">{{ $attendance->user->name }}</h3>
                        <p class="text-gray-600">{{ ucfirst($attendance->user->role) }}</p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-sm font-medium text-gray-500">Date</p>
                        <p class="text-lg font-bold text-gray-800">{{ $attendance->date->format('M d, Y') }}</p>
                        <p class="text-gray-600">{{ $attendance->date->format('l') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Clock In Time -->
                <div>
                    <label for="clock_in_time" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-log-in-circle mr-1 text-green-600'></i> Clock In Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="clock_in_time" id="clock_in_time"
                        value="{{ old('clock_in_time', $attendance->clock_in_time ? $attendance->clock_in_time->format('H:i') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('clock_in_time') border-red-500 @enderror"
                        required>
                    @error('clock_in_time')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Clock Out Time -->
                <div>
                    <label for="clock_out_time" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-log-out-circle mr-1 text-red-600'></i> Clock Out Time
                    </label>
                    <input type="time" name="clock_out_time" id="clock_out_time"
                        value="{{ old('clock_out_time', $attendance->clock_out_time ? $attendance->clock_out_time->format('H:i') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('clock_out_time') border-red-500 @enderror">
                    @error('clock_out_time')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class='bx bx-check-circle mr-1 text-blue-600'></i> Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('status') border-red-500 @enderror"
                    required>
                    <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>
                        ✅ Present
                    </option>
                    <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>
                        ⏰ Late
                    </option>
                    <option value="half_day" {{ old('status', $attendance->status) == 'half_day' ? 'selected' : '' }}>
                        🕐 Half Day
                    </option>
                    <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>
                        ❌ Absent
                    </option>
                    <option value="on_leave" {{ old('status', $attendance->status) == 'on_leave' ? 'selected' : '' }}>
                        📅 On Leave
                    </option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class='bx bx-note mr-1 text-yellow-600'></i> Notes
                </label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('notes') border-red-500 @enderror"
                    placeholder="Optional notes about this attendance record...">{{ old('notes', $attendance->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-800 mb-2"><i class='bx bx-info-circle mr-1'></i> Current Information</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-blue-600">Total Hours:</span>
                        <span class="font-medium text-gray-800">{{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . 'h' : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-blue-600">Break Duration:</span>
                        <span class="font-medium text-gray-800">{{ $attendance->break_duration ? $attendance->break_duration . ' min' : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-blue-600">Approved:</span>
                        <span class="font-medium {{ $attendance->is_approved ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $attendance->is_approved ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-blue-600">Created:</span>
                        <span class="font-medium text-gray-800">{{ $attendance->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.attendance.show', $attendance) }}"
                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                    <i class='bx bx-save'></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

