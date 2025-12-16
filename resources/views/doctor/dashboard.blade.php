@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-plus-circle text-2xl'></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">Welcome back, Dr. {{ Auth::user()->name }}!</h1>
                            <p class="text-emerald-100 text-sm">{{ now()->format('l, F d, Y') }}</p>
                        </div>
                    </div>
                    <p class="text-emerald-100 mt-2">Manage your appointments and patients efficiently.</p>
                    @if(Auth::user()->doctor)
                        <span class="inline-flex items-center gap-1 mt-3 px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm">
                            <i class='bx bx-id-card'></i> {{ Auth::user()->doctor->doctor_id }}
                        </span>
                    @endif
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('doctor.appointments.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white text-emerald-600 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg">
                        <i class='bx bx-calendar-check'></i>
                        <span>My Appointments</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Appointments -->
            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Appointments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAppointments) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-calendar text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($todayAppointments) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-time text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming -->
            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($upcomingAppointments) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-calendar-check text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Completed</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($completedAppointments) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                        <i class='bx bx-check-circle text-xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance & Tasks Side by Side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Widget -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-time text-orange-500'></i>Attendance
                    </h3>
                </div>
                <div class="p-6">
                    @if($todayAttendance)
                        @if($todayAttendance->isClockedIn())
                            <!-- Clocked In State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                                        <i class='bx bx-check-circle mr-2'></i> Clocked In
                                    </span>
                                </div>
                                <div class="space-y-2 mb-6">
                                    <p class="text-sm text-gray-600">Clock In: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_in_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Duration: <span class="font-semibold text-gray-900">{{ $todayAttendance->getWorkDuration() }}</span></p>
                                    @if($todayAttendance->isOnBreak())
                                        <p class="text-sm text-amber-600 font-semibold"><i class='bx bx-coffee'></i> On Break</p>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    @if($todayAttendance->isOnBreak())
                                        <form action="{{ route('doctor.attendance.break-end') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-semibold">
                                                <i class='bx bx-play-circle mr-1'></i> End Break
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('doctor.attendance.break-start') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition font-semibold">
                                                <i class='bx bx-coffee mr-1'></i> Start Break
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('doctor.attendance.clock-out') }}" method="POST" onsubmit="return confirm('Are you sure you want to clock out?')">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-semibold">
                                            <i class='bx bx-log-out mr-1'></i> Clock Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Clocked Out State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                        <i class='bx bx-check-circle mr-2'></i> Clocked Out
                                    </span>
                                </div>
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-600">Clock In: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_in_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Clock Out: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_out_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Total Hours: <span class="font-semibold text-emerald-600">{{ $todayAttendance->total_hours }}h</span></p>
                                </div>
                                <p class="text-xs text-gray-500">You have completed your shift for today</p>
                            </div>
                        @endif
                    @else
                        <!-- Not Clocked In Yet -->
                        <div class="text-center">
                            <div class="mb-4">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                    <i class='bx bx-time mr-2'></i> Not Clocked In
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-6">{{ now()->format('l, F d, Y') }}</p>
                            <form action="{{ route('doctor.attendance.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl hover:from-emerald-600 hover:to-teal-700 transition font-bold text-lg shadow-lg shadow-emerald-500/30">
                                    <i class='bx bx-time text-2xl mr-2'></i> CLOCK IN
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-4">Tap to start your day</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Tasks Widget -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-task text-pink-500'></i>My Tasks
                    </h3>
                    <a href="{{ route('doctor.todos.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1">
                        View All <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
                <div class="p-6">
                    @if($todos->count() > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($todos as $todo)
                                <div class="flex items-start gap-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                                    <!-- Checkbox -->
                                    <div class="flex-shrink-0 pt-0.5">
                                        <input type="checkbox"
                                               id="todo-{{ $todo->id }}"
                                               data-todo-id="{{ $todo->id }}"
                                               {{ $todo->status === 'completed' ? 'checked' : '' }}
                                               onchange="updateTodoStatus({{ $todo->id }}, this.checked)"
                                               class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                    </div>

                                    <!-- Task Info -->
                                    <div class="flex-1 min-w-0">
                                        <label for="todo-{{ $todo->id }}" class="cursor-pointer">
                                            <h4 class="font-medium text-gray-900 text-sm {{ $todo->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                                                {{ $todo->title }}
                                            </h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <!-- Priority Badge -->
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full border {{ $todo->priority_color }}">
                                                    {{ ucfirst($todo->priority) }}
                                                </span>

                                                <!-- Due Date -->
                                                @if($todo->due_date)
                                                    <span class="text-xs {{ $todo->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                                        <i class='bx bx-calendar'></i> {{ $todo->due_date->format('M d') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>

                                    <!-- View Button -->
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('doctor.todos.show', $todo->id) }}"
                                           class="text-emerald-600 hover:text-emerald-700">
                                            <i class='bx bx-chevron-right text-xl'></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class='bx bx-task text-3xl text-gray-400'></i>
                            </div>
                            <p class="text-gray-500 font-medium">No pending tasks</p>
                            <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-calendar-event text-emerald-500'></i>Today's Appointments
                </h3>
            </div>
            <div class="p-6">
                @if($todayAppointmentsList->count() > 0)
                    <div class="space-y-4">
                        @foreach($todayAppointmentsList as $appointment)
                            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50/50 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($appointment->patient->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->patient->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $appointment->service->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class='bx bx-time mr-1'></i>{{ $appointment->appointment_time }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($appointment->status === 'completed') bg-emerald-100 text-emerald-700
                                        @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-700
                                        @elseif($appointment->status === 'pending') bg-amber-100 text-amber-700
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                        class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition">
                                        <i class='bx bx-chevron-right text-xl'></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-500 font-medium">No appointments scheduled for today</p>
                        <p class="text-gray-400 text-sm mt-1">Enjoy your free time!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-zap text-yellow-500'></i>Quick Actions
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('doctor.schedule.index') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                            <i class='bx bx-calendar text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">View Schedule</span>
                    </a>
                    <a href="{{ route('doctor.patients.index') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                            <i class='bx bx-user text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">My Patients</span>
                    </a>
                    <a href="{{ route('doctor.profile.show') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                            <i class='bx bx-user-circle text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">My Profile</span>
                    </a>
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                            <i class='bx bx-calendar-check text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Appointments</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateTodoStatus(todoId, isCompleted) {
        const status = isCompleted ? 'completed' : 'pending';

        fetch(`{{ url('doctor/todos') }}/${todoId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const checkbox = document.getElementById(`todo-${todoId}`);
                const label = checkbox.closest('.flex').querySelector('h4');

                if (isCompleted) {
                    label.classList.add('line-through', 'text-gray-500');
                    setTimeout(() => {
                        checkbox.closest('.flex.items-start').remove();
                        const taskContainer = document.querySelector('.space-y-3.max-h-80');
                        if (taskContainer && taskContainer.children.length === 0) {
                            taskContainer.innerHTML = `
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="bx bx-task text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No pending tasks</p>
                                    <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
                                </div>`;
                        }
                    }, 500);
                } else {
                    label.classList.remove('line-through', 'text-gray-500');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById(`todo-${todoId}`).checked = !isCompleted;
        });
    }
</script>
@endpush