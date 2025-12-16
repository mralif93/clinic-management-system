@extends('layouts.staff')

@section('title', 'Staff Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 via-orange-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium mb-1">{{ now()->format('l, F d, Y') }}</p>
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-amber-100">Manage appointments and assist patients from here.</p>
                    @if(Auth::user()->staff)
                        <div class="flex items-center gap-3 mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                                <i class='bx bx-id-card mr-1'></i> {{ Auth::user()->staff?->staff_id ?? 'Staff' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                                <i class='bx bx-briefcase mr-1'></i> {{ Auth::user()->staff?->position ?? 'Staff' }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class='bx bx-id-card text-5xl text-white/80'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Appointments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Appointments</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAppointments }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-calendar text-2xl text-blue-500'></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today's Appointments</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayAppointments }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <i class='bx bx-time text-2xl text-green-500'></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Upcoming</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $upcomingAppointments }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class='bx bx-calendar-check text-2xl text-purple-500'></i>
                    </div>
                </div>
            </div>

            <!-- Total Patients -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Patients</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPatients }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class='bx bx-user text-2xl text-amber-500'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance & Tasks Side by Side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Widget -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                        <i class='bx bx-time text-xl text-green-500'></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Attendance</h3>
                </div>
                <div class="p-5">
                    @if($todayAttendance)
                        @if($todayAttendance->isClockedIn())
                            <!-- Clocked In State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700">
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
                                        <form id="breakEndForm" action="{{ route('staff.attendance.break-end') }}" method="POST" onsubmit="confirmBreakEnd(event)">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold">
                                                <i class='bx bx-play-circle'></i> End Break
                                            </button>
                                        </form>
                                    @else
                                        <form id="breakStartForm" action="{{ route('staff.attendance.break-start') }}" method="POST" onsubmit="confirmBreakStart(event)">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition font-semibold">
                                                <i class='bx bx-coffee'></i> Start Break
                                            </button>
                                        </form>
                                    @endif
                                    <form id="clockOutForm" action="{{ route('staff.attendance.clock-out') }}" method="POST" onsubmit="confirmClockOut(event)">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition font-semibold">
                                            <i class='bx bx-log-out'></i> Clock Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Clocked Out State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                                        <i class='bx bx-check-circle mr-2'></i> Clocked Out
                                    </span>
                                </div>
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-600">Clock In: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_in_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Clock Out: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_out_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Total Hours: <span class="font-semibold text-green-600">{{ $todayAttendance->total_hours }}h</span></p>
                                </div>
                                <p class="text-xs text-gray-500">You have completed your shift for today</p>
                            </div>
                        @endif
                    @else
                        <!-- Not Clocked In Yet -->
                        <div class="text-center">
                            <div class="mb-4">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                                    <i class='bx bx-time mr-2'></i> Not Clocked In
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-6">{{ now()->format('l, F d, Y') }}</p>
                            <form action="{{ route('staff.attendance.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 transition font-bold text-lg shadow-lg">
                                    <i class='bx bx-time text-2xl mr-2'></i> CLOCK IN
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-4">Tap to start your day</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Tasks Widget -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center">
                            <i class='bx bx-task text-xl text-pink-500'></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">My Tasks</h3>
                    </div>
                    <a href="{{ route('staff.todos.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium flex items-center gap-1">
                        View All <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
                <div class="p-5">
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
                                               class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500 cursor-pointer">
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
                                        <a href="{{ route('staff.todos.show', $todo->id) }}"
                                           class="text-amber-500 hover:text-amber-600">
                                            <i class='bx bx-chevron-right text-xl'></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <i class='bx bx-task text-3xl text-gray-400'></i>
                            </div>
                            <p class="text-gray-500">No pending tasks</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class='bx bx-calendar text-xl text-blue-500'></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Today's Appointments</h3>
            </div>
            <div class="p-5">
                @if($todayAppointmentsList->count() > 0)
                    <div class="space-y-3">
                        @foreach($todayAppointmentsList as $appointment)
                            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                                            <i class='bx bx-user text-amber-500 text-xl'></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $appointment->patient->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-500">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }} -
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class='bx bx-time mr-1'></i>{{ $appointment->appointment_time }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-lg
                                                            @if($appointment->status === 'completed') bg-green-100 text-green-700
                                                            @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-700
                                                            @elseif($appointment->status === 'pending') bg-amber-100 text-amber-700
                                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                                            @else bg-gray-100 text-gray-700
                                                            @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                                        class="text-amber-500 hover:text-amber-600">
                                        <i class='bx bx-chevron-right text-2xl'></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-calendar text-3xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-500">No appointments scheduled for today.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                    <i class='bx bx-rocket text-xl text-purple-500'></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('staff.appointments.index') }}"
                        class="group flex flex-col items-center p-5 bg-gray-50 rounded-xl hover:bg-amber-50 hover:shadow-md transition-all duration-200">
                        <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class='bx bx-calendar-plus text-2xl text-amber-600'></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Appointments</span>
                    </a>
                    <a href="{{ route('staff.patients.index') }}"
                        class="group flex flex-col items-center p-5 bg-gray-50 rounded-xl hover:bg-blue-50 hover:shadow-md transition-all duration-200">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class='bx bx-user text-2xl text-blue-600'></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Patients</span>
                    </a>
                    <a href="{{ route('staff.profile.show') }}"
                        class="group flex flex-col items-center p-5 bg-gray-50 rounded-xl hover:bg-purple-50 hover:shadow-md transition-all duration-200">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class='bx bx-user-circle text-2xl text-purple-600'></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">My Profile</span>
                    </a>
                    <a href="{{ route('staff.reports.index') }}"
                        class="group flex flex-col items-center p-5 bg-gray-50 rounded-xl hover:bg-orange-50 hover:shadow-md transition-all duration-200">
                        <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class='bx bx-file text-2xl text-orange-600'></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateTodoStatus(todoId, isChecked) {
            const newStatus = isChecked ? 'completed' : 'in_progress';
            
            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            formData.append('status', newStatus);
            
            // Send AJAX request
            fetch(`/staff/todos/${todoId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Update UI
                    const label = document.querySelector(`label[for="todo-${todoId}"] h4`);
                    if (isChecked) {
                        label.classList.add('line-through', 'text-gray-500');
                    } else {
                        label.classList.remove('line-through', 'text-gray-500');
                    }
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: `Task marked as ${isChecked ? 'completed' : 'in progress'}`,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    throw new Error('Failed to update');
                }
            })
            .catch(error => {
                // Revert checkbox on error
                document.getElementById(`todo-${todoId}`).checked = !isChecked;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update task status',
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }
        function confirmBreakStart(event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Start Break?',
                text: "Are you sure you want to take a break?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706', // yellow-600
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Start Break',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('breakStartForm').submit();
                }
            });
        }

        function confirmBreakEnd(event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'End Break?',
                text: "Are you ready to resume work?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // green-600
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Resume Work',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('breakEndForm').submit();
                }
            });
        }

        function confirmClockOut(event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Clock Out?',
                text: "Are you sure you want to end your shift?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Clock Out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clockOutForm').submit();
                }
            });
        }
    </script>
    @endpush
@endsection