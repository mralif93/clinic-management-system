@extends('layouts.staff')

@section('title', 'Staff Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-yellow-100">Manage appointments and assist patients from here.</p>
                    @if(Auth::user()->staff)
                        <p class="text-yellow-100 mt-2">ID: {{ Auth::user()->staff->staff_id }} | Position:
                            {{ Auth::user()->staff->position ?? 'N/A' }}
                        </p>
                    @endif
                </div>
                <div class="text-6xl opacity-20">
                    <i class='bx bx-group'></i>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Appointments -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAppointments }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class='bx bx-calendar text-3xl text-blue-600'></i>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayAppointments }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class='bx bx-time text-3xl text-green-600'></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingAppointments }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class='bx bx-calendar-check text-3xl text-purple-600'></i>
                    </div>
                </div>
            </div>

            <!-- Total Patients -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPatients }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class='bx bx-user text-3xl text-yellow-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance & Tasks Side by Side -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Widget -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900"><i class='bx bx-time-five mr-2'></i>Attendance</h3>
                </div>
                <div class="p-6">
                    @if($todayAttendance)
                        @if($todayAttendance->isClockedIn())
                            <!-- Clocked In State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-300">
                                        <i class='bx bx-check-circle mr-2'></i> Clocked In
                                    </span>
                                </div>
                                <div class="space-y-2 mb-6">
                                    <p class="text-sm text-gray-600">Clock In: <span class="font-semibold text-gray-900">{{ $todayAttendance->clock_in_time->format('h:i A') }}</span></p>
                                    <p class="text-sm text-gray-600">Duration: <span class="font-semibold text-gray-900">{{ $todayAttendance->getWorkDuration() }}</span></p>
                                    @if($todayAttendance->isOnBreak())
                                        <p class="text-sm text-yellow-600 font-semibold"><i class='bx bx-coffee'></i> On Break</p>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    @if($todayAttendance->isOnBreak())
                                        <form id="breakEndForm" action="{{ route('staff.attendance.break-end') }}" method="POST" onsubmit="confirmBreakEnd(event)">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                                <i class='bx bx-play-circle'></i> End Break
                                            </button>
                                        </form>
                                    @else
                                        <form id="breakStartForm" action="{{ route('staff.attendance.break-start') }}" method="POST" onsubmit="confirmBreakStart(event)">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-semibold">
                                                <i class='bx bx-coffee'></i> Start Break
                                            </button>
                                        </form>
                                    @endif
                                    <form id="clockOutForm" action="{{ route('staff.attendance.clock-out') }}" method="POST" onsubmit="confirmClockOut(event)">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                                            <i class='bx bx-log-out'></i> Clock Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Clocked Out State -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">
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
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                    <i class='bx bx-time mr-2'></i> Not Clocked In
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-6">{{ now()->format('l, F d, Y') }}</p>
                            <form action="{{ route('staff.attendance.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition font-bold text-lg shadow-lg">
                                    <i class='bx bx-time-five text-2xl mr-2'></i> CLOCK IN
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-4">Tap to start your day</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- My Tasks Widget -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900"><i class='bx bx-task mr-2'></i>My Tasks</h3>
                    <a href="{{ route('staff.todos.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                        View All <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
                <div class="p-6">
                    @if($todos->count() > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($todos as $todo)
                                <div class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    <!-- Checkbox -->
                                    <div class="flex-shrink-0 pt-0.5">
                                        <input type="checkbox"
                                               id="todo-{{ $todo->id }}"
                                               data-todo-id="{{ $todo->id }}"
                                               {{ $todo->status === 'completed' ? 'checked' : '' }}
                                               onchange="updateTodoStatus({{ $todo->id }}, this.checked)"
                                               class="w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500 cursor-pointer">
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
                                           class="text-yellow-600 hover:text-yellow-700">
                                            <i class='bx bx-chevron-right text-xl'></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            <i class='bx bx-task text-4xl mb-2'></i><br>
                            No pending tasks
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Today's Appointments</h3>
            </div>
            <div class="p-6">
                @if($todayAppointmentsList->count() > 0)
                    <div class="space-y-4">
                        @foreach($todayAppointmentsList as $appointment)
                            <div
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <i class='bx bx-user text-yellow-600 text-xl'></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->patient->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-600">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }} -
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class='bx bx-time mr-1'></i>{{ $appointment->appointment_time }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                                            @if($appointment->status === 'completed') bg-green-100 text-green-700
                                                            @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-700
                                                            @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-700
                                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                                            @else bg-gray-100 text-gray-700
                                                            @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                                        class="text-yellow-600 hover:text-yellow-700">
                                        <i class='bx bx-chevron-right text-2xl'></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No appointments scheduled for today.</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('staff.appointments.index') }}"
                        class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 hover:shadow-md transition-all duration-200">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-3">
                            <i class='bx bx-calendar-plus text-3xl text-yellow-600'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Appointments</span>
                    </a>
                    <a href="{{ route('staff.patients.index') }}"
                        class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 hover:shadow-md transition-all duration-200">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                            <i class='bx bx-user text-3xl text-blue-600'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Manage Patients</span>
                    </a>
                    <a href="{{ route('staff.profile.show') }}"
                        class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 hover:shadow-md transition-all duration-200">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-3">
                            <i class='bx bx-user-circle text-3xl text-purple-600'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">My Profile</span>
                    </a>
                    <a href="{{ route('staff.reports.index') }}"
                        class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 hover:shadow-md transition-all duration-200">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-3">
                            <i class='bx bx-file text-3xl text-orange-600'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Reports</span>
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