@extends('layouts.doctor')

@section('title', 'Schedule Settings')
@section('page-title', 'Schedule Settings')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <a href="{{ route('doctor.schedule.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                        <i class='bx bx-arrow-back'></i> Back to Schedule
                    </a>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-cog text-xl'></i>
                        </div>
                        Schedule Settings
                    </h1>
                    <p class="text-emerald-100 mt-2">Configure your weekly working schedule</p>
                </div>
            </div>
        </div>

        <!-- Schedule Form -->
        <form method="POST" action="{{ route('doctor.schedule.save-settings') }}" class="space-y-6">
            @csrf

            <!-- Day Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($scheduleData as $dayIndex => $schedule)
                    @php
                        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $dayName = $dayNames[$dayIndex];
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                        <!-- Card Header with Day and Status -->
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-3 flex items-center justify-between">
                            <h3 class="text-base font-bold text-white">{{ $dayName }}</h3>
                            <!-- Status Toggle in Header -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <!-- Hidden input for unchecked state -->
                                <input type="hidden" name="schedules[{{ $dayIndex }}][is_active]" value="0">
                                <input type="checkbox"
                                    name="schedules[{{ $dayIndex }}][is_active]"
                                    value="1"
                                    {{ $schedule->is_active ? 'checked' : '' }}
                                    class="sr-only peer day-toggle"
                                    data-day="{{ $dayIndex }}">
                                <div class="w-11 h-6 bg-white/30 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-white/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-white/40"></div>
                            </label>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-4">
                            <!-- Hidden field for day_of_week -->
                            <input type="hidden" name="schedules[{{ $dayIndex }}][day_of_week]" value="{{ $dayIndex }}">

                            <!-- Time Settings -->
                            <div class="space-y-3 time-settings" id="time-settings-{{ $dayIndex }}">
                                <!-- Start Time - End Time Row -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Start Time</label>
                                        <input type="time"
                                            name="schedules[{{ $dayIndex }}][start_time]"
                                            value="{{ $schedule->start_time ?? '09:00' }}"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">End Time</label>
                                        <input type="time"
                                            name="schedules[{{ $dayIndex }}][end_time]"
                                            value="{{ $schedule->end_time ?? '17:00' }}"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                                    </div>
                                </div>

                                <!-- Break Start - Break End Row -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Break Start</label>
                                        <input type="time"
                                            name="schedules[{{ $dayIndex }}][break_start]"
                                            value="{{ $schedule->break_start ?? '12:00' }}"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Break End</label>
                                        <input type="time"
                                            name="schedules[{{ $dayIndex }}][break_end]"
                                            value="{{ $schedule->break_end ?? '13:00' }}"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                                    </div>
                                </div>

                                <!-- Slot Duration -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Slot Duration</label>
                                    <select name="schedules[{{ $dayIndex }}][slot_duration]"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                                        <option value="15" {{ ($schedule->slot_duration ?? 30) == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ ($schedule->slot_duration ?? 30) == 30 ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ ($schedule->slot_duration ?? 30) == 45 ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ ($schedule->slot_duration ?? 30) == 60 ? 'selected' : '' }}>60 minutes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer with Status Text -->
                        <div class="bg-gray-50/50 px-4 py-3 border-t border-gray-100">
                            <div class="flex items-center justify-center">
                                <span class="text-sm font-medium status-text" data-day="{{ $dayIndex }}">
                                    {{ $schedule->is_active ? 'Working' : 'Off Day' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <i class='bx bx-save mr-2'></i> Save Schedule Settings
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle toggle switches
            const toggles = document.querySelectorAll('.day-toggle');
            
            toggles.forEach(toggle => {
                const dayIndex = toggle.dataset.day;
                const timeSettings = document.getElementById(`time-settings-${dayIndex}`);
                const statusText = document.querySelector(`.status-text[data-day="${dayIndex}"]`);
                
                // Set initial state
                updateDayState(toggle, timeSettings, statusText);
                
                // Add change listener
                toggle.addEventListener('change', function() {
                    updateDayState(toggle, timeSettings, statusText);
                });
            });
            
            function updateDayState(toggle, timeSettings, statusText) {
                const isActive = toggle.checked;
                
                // Update time settings visibility and read-only state
                const inputs = timeSettings.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.readOnly = !isActive;
                    if (!isActive) {
                        input.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        input.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }
                });
                
                // Update status text in footer
                if (isActive) {
                    statusText.textContent = 'Working';
                } else {
                    statusText.textContent = 'Off Day';
                }
            }
        });
    </script>
    @endpush
@endsection
