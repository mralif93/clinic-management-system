@extends('layouts.staff')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">My Leave Requests</h1>
                <p class="text-gray-600 mt-1">Manage your leave applications</p>
            </div>
            <a href="{{ route('staff.leaves.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus-circle text-xl'></i>
                Apply for Leave
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Leaves -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Leaves</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-calendar text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Pending</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-time-five text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Approved</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-check-circle text-3xl'></i>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Rejected</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $stats['rejected'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class='bx bx-x-circle text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('staff.leaves.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                    <select name="leave_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach(\App\Models\Leave::getLeaveTypes() as $key => $label)
                            <option value="{{ $key }}" {{ request('leave_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('staff.leaves.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Leaves List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($leaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Leave Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Duration</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Days</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Submitted</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($leaves as $leave)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $leave->type_color }}">
                                            {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $leave->status_color }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $leave->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('staff.leaves.show', $leave->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm"
                                                title="View">
                                                <i class='bx bx-show text-base'></i>
                                            </a>
                                            @if($leave->status === 'pending')
                                                <a href="{{ route('staff.leaves.edit', $leave->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm"
                                                    title="Edit">
                                                    <i class='bx bx-pencil text-base'></i>
                                                </a>
                                                <form action="{{ route('staff.leaves.destroy', $leave->id) }}" method="POST"
                                                    class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                        title="Cancel">
                                                        <i class='bx bx-trash text-base'></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $leaves->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-calendar-x text-6xl text-gray-300'></i>
                    <p class="text-gray-500 mt-4 text-lg">No leave requests found</p>
                    <a href="{{ route('staff.leaves.create') }}"
                        class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Apply for Leave
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Delete Confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to cancel this leave request?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection