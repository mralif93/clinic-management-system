@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white rounded-3xl shadow-2xl p-8 mb-8 border border-white/10 relative overflow-hidden group">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
 
            <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
                <div class="flex items-center gap-6">
                    <div class="shrink-0 w-16 h-16 md:w-20 md:h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-white/20 transform hover:scale-105 transition-all duration-300">
                        <i class='hgi-stroke hgi-hospital-01 text-4xl md:text-5xl text-white'></i>
                    </div>
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur-md">
                            <i class='hgi-stroke hgi-sun-01 text-yellow-400 animate-pulse'></i>
                            <span class="text-[10px] font-bold tracking-wider uppercase opacity-90">{{ now()->format('l, F j, Y') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-primary-200">Dr. {{ Auth::user()->name }}!</span>
                        </h2>
                        <p class="text-sm text-primary-100/80 max-w-md font-medium leading-relaxed">
                            You have <span class="text-white font-bold">{{ $todayAppointments }} appointments</span> scheduled today.
                        </p>
                        @if(Auth::user()->doctor)
                            <span class="inline-flex items-center gap-2 mt-1 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-[10px] font-bold uppercase tracking-wider">
                                <i class='hgi-stroke hgi-identity-card'></i>{{ Auth::user()->doctor->doctor_id }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('doctor.appointments.index') }}"
                       class="relative w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-0 transition-all duration-500 hover:bg-white/20 group/icon">
                        <i class='hgi-stroke hgi-calendar-03 text-4xl md:text-5xl text-white opacity-90 group-hover/icon:opacity-100'></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Appointments -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-primary-50 text-primary-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-calendar-03 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Total Appointments</p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($totalAppointments) }}</h4>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-calendar-add-01 text-gray-300 group-hover:text-primary-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-primary-50 text-primary-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-clock-02 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Today</p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($todayAppointments) }}</h4>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-time-04 text-gray-300 group-hover:text-primary-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-primary-50 text-primary-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-calendar-03 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Upcoming</p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($upcomingAppointments) }}</h4>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-arrow-up-right-01 text-gray-300 group-hover:text-primary-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-primary-50 text-primary-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-checkmark-circle-02 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Completed</p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($completedAppointments) }}</h4>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-checkmark-badge-01 text-gray-300 group-hover:text-primary-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <a href="{{ route('doctor.appointments.index', ['status' => 'completed', 'approved' => '0']) }}"
               class="group bg-white {{ $pendingApprovalCount > 0 ? 'border-amber-200 ring-2 ring-amber-300/50' : 'border-gray-100' }} rounded-3xl shadow-sm hover:shadow-premium border p-6 transition-all duration-300 hover:-translate-y-1 block">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-shield-02 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] {{ $pendingApprovalCount > 0 ? 'text-amber-600' : 'text-gray-400' }}">Pending Approvals</p>
                            <h4 class="text-3xl font-black {{ $pendingApprovalCount > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ number_format($pendingApprovalCount) }}</h4>
                        </div>
                        @if($pendingApprovalCount > 0)
                            <p class="text-xs text-amber-500 font-bold">Records need review</p>
                        @else
                            <p class="text-xs text-gray-400 font-medium">All records up to date</p>
                        @endif
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-amber-50 transition-colors {{ $pendingApprovalCount > 0 ? 'animate-pulse' : '' }}">
                        <i class='hgi-stroke hgi-shield-01 text-xl text-gray-300 group-hover:text-amber-400 transition-colors'></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Attendance & Tasks Side by Side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Widget -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
                <div class="p-6 border-b border-primary-100/60 bg-gradient-to-br from-primary-50/80 to-primary-100/30">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class='hgi-stroke hgi-clock-02 text-primary-500'></i>Attendance
                    </h3>
                </div>
                <div class="p-6">
                    @if($todayAttendance)
                        @if($todayAttendance->isClockedIn())
                            <!-- Clocked In State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                                        <i class='hgi-stroke hgi-checkmark-circle-02 mr-2'></i> Clocked In
                                    </span>
                                </div>
                                <div class="space-y-2 mb-6">
                                    <p class="text-sm text-gray-600">Clock In: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_in_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Duration: <span class="font-semibold text-gray-900">{{ $todayAttendance->getWorkDuration() }}</span></p>
                                    @if($todayAttendance->isOnBreak())
                                        <p class="text-sm text-amber-600 font-semibold"><i class='hgi-stroke hgi-coffee-01'></i> On Break</p>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    @if($todayAttendance->isOnBreak())
                                        <form action="{{ route('doctor.attendance.break-end') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-semibold">
                                                <i class='hgi-stroke hgi-play-circle mr-1'></i> End Break
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('doctor.attendance.break-start') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition font-semibold">
                                                <i class='hgi-stroke hgi-coffee-01 mr-1'></i> Start Break
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('doctor.attendance.clock-out') }}" method="POST" onsubmit="return confirm('Are you sure you want to clock out?')">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-semibold">
                                            <i class='hgi-stroke hgi-logout-01 mr-1'></i> Clock Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Clocked Out State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                        <i class='hgi-stroke hgi-checkmark-circle-02 mr-2'></i> Clocked Out
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
                                    <i class='hgi-stroke hgi-clock-02 mr-2'></i> Not Clocked In
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-6">{{ now()->format('l, F d, Y') }}</p>
                            <form action="{{ route('doctor.attendance.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition font-bold text-lg shadow-lg shadow-primary-500/30">
                                    <i class='hgi-stroke hgi-clock-02 text-2xl mr-2'></i> CLOCK IN
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-4">Tap to start your day</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Tasks Widget -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
                <div class="p-6 border-b border-primary-100/60 bg-gradient-to-br from-primary-50/80 to-primary-100/30 flex justify-between items-center">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class='hgi-stroke hgi-task-01 text-primary-500'></i>My Tasks
                    </h3>
                    <a href="{{ route('doctor.todos.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                        View All <i class='hgi-stroke hgi-arrow-right-01'></i>
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
                                               class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500 cursor-pointer">
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
                                                        <i class='hgi-stroke hgi-calendar-03'></i> {{ $todo->due_date->format('M d') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>

                                    <!-- View Button -->
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('doctor.todos.show', $todo->id) }}"
                                           class="text-primary-600 hover:text-primary-700">
                                            <i class='hgi-stroke hgi-arrow-right-01 text-xl'></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class='hgi-stroke hgi-task-01 text-3xl text-gray-400'></i>
                            </div>
                            <p class="text-gray-500 font-medium">No pending tasks</p>
                            <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
            <div class="p-6 border-b border-primary-100/60 bg-gradient-to-br from-primary-50/80 to-primary-100/30">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <i class='hgi-stroke hgi-calendar-03 text-primary-500'></i>Today's Appointments
                </h3>
            </div>
            <div class="p-6">
                @if($todayAppointmentsList->count() > 0)
                    <div class="space-y-4">
                        @foreach($todayAppointmentsList as $appointment)
                            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:bg-gray-50/50 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($appointment->patient->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->patient->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $appointment->service->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class='hgi-stroke hgi-clock-01 mr-1'></i>{{ $appointment->appointment_time }}
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
                                        class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 flex items-center justify-center transition">
                                        <i class='hgi-stroke hgi-arrow-right-01 text-xl'></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class='hgi-stroke hgi-calendar-03 text-3xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-500 font-medium">No appointments scheduled for today</p>
                        <p class="text-gray-400 text-sm mt-1">Enjoy your free time!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
            <div class="p-6 border-b border-primary-100/60 bg-gradient-to-br from-primary-50/80 to-primary-100/30">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <i class='hgi-stroke hgi-energy-ellipse text-primary-500'></i>Quick Actions
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('doctor.schedule.index') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform">
                            <i class='hgi-stroke hgi-calendar-03 text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">View Schedule</span>
                    </a>
                    <a href="{{ route('doctor.patients.index') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform">
                            <i class='hgi-stroke hgi-user text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">My Patients</span>
                    </a>
                    <a href="{{ route('doctor.profile.show') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform">
                            <i class='hgi-stroke hgi-user-circle text-2xl text-white'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">My Profile</span>
                    </a>
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="group flex flex-col items-center p-5 bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-100 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform">
                            <i class='hgi-stroke hgi-calendar-03 text-2xl text-white'></i>
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
                                        <i class="hgi-stroke hgi-task-01 text-3xl text-gray-400"></i>
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
