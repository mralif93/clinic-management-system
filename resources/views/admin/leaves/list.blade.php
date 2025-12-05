@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-calendar-check text-primary-600 text-2xl'></i>
                    Leave Management
                </h1>
                <p class="text-sm text-gray-600">Requests for {{ $monthName }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.leaves.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-arrow-back text-base'></i>
                    Back to Months
                </a>
                <a href="{{ route('admin.leaves.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-plus-circle text-base'></i>
                    Apply Leave
                </a>
            </div>
        </div>

        <!-- Statistics Cards (Contextual) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-600">Total Requests</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                        <i class='bx bx-file text-3xl'></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="bg-yellow-50 text-yellow-700 p-4 rounded-full">
                        <i class='bx bx-time text-3xl'></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="bg-success-50 text-success-600 p-4 rounded-full">
                        <i class='bx bx-check-circle text-3xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.leaves.by-month', ['year' => $year, 'month' => $month]) }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-search'></i> Search
                        </label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by patient name, doctor, or service..."
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-info-circle'></i> Status
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="md:col-span-3">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-calendar'></i> Date
                        </label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                    </div>

                    <!-- Apply Filters Button -->
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                            <i class='bx bx-filter-alt text-base'></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Leaves List -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Leave Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Submitted</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50 transition-colors {{ $leave->trashed() ? 'opacity-60' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-primary-50 text-primary-700 rounded-full p-2 mr-3">
                                            <i class='bx bx-user'></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($leave->user->role) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $leave->type_color }}">
                                        {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $leave->start_date->format('M d') }} -
                                        {{ $leave->end_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 font-semibold">{{ $leave->total_days }}
                                        {{ Str::plural('day', $leave->total_days) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $leave->status_color }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $leave->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.leaves.show', $leave->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 text-xs"
                                            title="View Details">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>

                                        @if($leave->status === 'pending')
                                            <button onclick="approveLeave({{ $leave->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 text-xs"
                                                title="Approve">
                                                <i class='bx bx-check text-base'></i>
                                            </button>
                                            <button onclick="rejectLeave({{ $leave->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                title="Reject">
                                                <i class='bx bx-x text-base'></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('admin.leaves.edit', $leave->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-xs"
                                            title="Edit">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>

                                        <form action="{{ route('admin.leaves.destroy', $leave->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs"
                                                title="Delete">
                                                <i class='bx bx-trash text-base'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class='bx bx-calendar-x text-4xl mb-2'></i>
                                    <p>No leave requests found for this month</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>

    <script>
        // Approve Leave
        function approveLeave(leaveId) {
            Swal.fire({
                title: 'Approve Leave Request?',
                text: "This will approve the leave request.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/leaves/${leaveId}/approve`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Reject Leave
        function rejectLeave(leaveId) {
            Swal.fire({
                title: 'Reject Leave Request',
                text: 'Please provide a reason for rejecting this leave request:',
                input: 'textarea',
                inputPlaceholder: 'Enter rejection reason here...',
                inputAttributes: {
                    'aria-label': 'Rejection reason',
                    'rows': 4
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Reject Request',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to provide a reason for rejection!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/leaves/${leaveId}/reject`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const adminNotes = document.createElement('input');
                    adminNotes.type = 'hidden';
                    adminNotes.name = 'admin_notes';
                    adminNotes.value = result.value;
                    form.appendChild(adminNotes);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Delete Confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
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
@endsection