@extends('layouts.admin')

@section('title', 'Manage Doctor Schedule')
@section('page-title', 'Manage Doctor Schedule')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.schedules.index') }}" class="rounded-xl bg-white/20 p-2 backdrop-blur-sm hover:bg-white/30 transition-colors text-white">
                            <i class='bx bx-arrow-back text-2xl'></i>
                        </a>
                        <h1 class="text-3xl font-bold text-white">Manage Schedule</h1>
                    </div>
                    <p class="text-indigo-100 max-w-xl">Configure weekly working schedule for <span class="font-semibold text-white">{{ $doctor->user->name }}</span></p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 backdrop-blur-sm border border-white/10">
                        <i class='bx bx-id-card text-indigo-200'></i>
                        <span class="font-medium">{{ $doctor->specialization }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Schedule Form -->
        <form method="POST" action="{{ route('admin.schedules.save-settings', $doctor->id) }}" class="space-y-8">
            @csrf

            <!-- Day Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($scheduleData as $dayIndex => $schedule)
                    @php
                        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $dayName = $dayNames[$dayIndex];
                        $isActive = $schedule->is_active;
                    @endphp
                    
                    <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 {{ $isActive ? 'ring-1 ring-indigo-500/20' : 'opacity-75' }}" id="day-card-{{ $dayIndex }}">
                        <!-- Card Header -->
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                <i class='bx bx-calendar-event text-indigo-500'></i>
                                {{ $dayName }}
                            </h3>
                            
                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="schedules[{{ $dayIndex }}][is_active]" value="0">
                                <input type="checkbox" 
                                    name="schedules[{{ $dayIndex }}][is_active]" 
                                    value="1"
                                    {{ $isActive ? 'checked' : '' }}
                                    class="sr-only peer day-toggle"
                                    data-day="{{ $dayIndex }}">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-5">
                            <input type="hidden" name="schedules[{{ $dayIndex }}][day_of_week]" value="{{ $dayIndex }}">

                            <!-- Time Settings -->
                            <div class="space-y-4 time-settings transition-all duration-300 {{ !$isActive ? 'opacity-50 pointer-events-none grayscale' : '' }}" id="time-settings-{{ $dayIndex }}">
                                <!-- Working Hours -->
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                        <i class='bx bx-time'></i> Working Hours
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="relative">
                                            <input type="time" 
                                                name="schedules[{{ $dayIndex }}][start_time]" 
                                                value="{{ $schedule->start_time ?? '09:00' }}"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                        </div>
                                        <div class="relative">
                                            <input type="time" 
                                                name="schedules[{{ $dayIndex }}][end_time]" 
                                                value="{{ $schedule->end_time ?? '17:00' }}"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                        </div>
                                    </div>
                                </div>

                                <!-- Break Time -->
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                        <i class='bx bx-coffee'></i> Break Time
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="relative">
                                            <input type="time" 
                                                name="schedules[{{ $dayIndex }}][break_start]" 
                                                value="{{ $schedule->break_start ?? '12:00' }}"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                        </div>
                                        <div class="relative">
                                            <input type="time" 
                                                name="schedules[{{ $dayIndex }}][break_end]" 
                                                value="{{ $schedule->break_end ?? '13:00' }}"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                        </div>
                                    </div>
                                </div>

                                <!-- Slot Duration -->
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                                        <i class='bx bx-timer'></i> Slot Duration
                                    </label>
                                    <select name="schedules[{{ $dayIndex }}][slot_duration]" 
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                        <option value="15" {{ ($schedule->slot_duration ?? 30) == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ ($schedule->slot_duration ?? 30) == 30 ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ ($schedule->slot_duration ?? 30) == 45 ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ ($schedule->slot_duration ?? 30) == 60 ? 'selected' : '' }}>60 minutes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-300 status-badge" data-day="{{ $dayIndex }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                <span class="status-text">{{ $isActive ? 'Working Day' : 'Off Day' }}</span>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Save Button -->
            <div class="fixed bottom-6 right-6 z-40">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-full shadow-xl hover:bg-indigo-700 hover:scale-105 hover:shadow-2xl transition-all duration-300">
                    <i class='bx bx-save text-xl'></i>
                    Save Schedule Changes
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.day-toggle');
            
            toggles.forEach(toggle => {
                const dayIndex = toggle.dataset.day;
                const card = document.getElementById(`day-card-${dayIndex}`);
                const timeSettings = document.getElementById(`time-settings-${dayIndex}`);
                const statusBadge = card.querySelector('.status-badge');
                const statusText = card.querySelector('.status-text');
                
                // Initial state
                updateState(toggle.checked);
                
                toggle.addEventListener('change', function() {
                    updateState(this.checked);
                });
                
                function updateState(isActive) {
                    if (isActive) {
                        // Active State
                        card.classList.remove('opacity-75');
                        card.classList.add('ring-1', 'ring-indigo-500/20');
                        
                        timeSettings.classList.remove('opacity-50', 'pointer-events-none', 'grayscale');
                        
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-300 status-badge bg-emerald-100 text-emerald-700';
                        statusText.textContent = 'Working Day';
                        
                        // Enable inputs
                        timeSettings.querySelectorAll('input, select').forEach(el => el.disabled = false);
                    } else {
                        // Inactive State
                        card.classList.add('opacity-75');
                        card.classList.remove('ring-1', 'ring-indigo-500/20');
                        
                        timeSettings.classList.add('opacity-50', 'pointer-events-none', 'grayscale');
                        
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-300 status-badge bg-gray-100 text-gray-500';
                        statusText.textContent = 'Off Day';
                        
                        // Disable inputs
                        timeSettings.querySelectorAll('input, select').forEach(el => el.disabled = true);
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
