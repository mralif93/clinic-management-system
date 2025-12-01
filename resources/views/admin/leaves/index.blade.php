@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Leave Management</h1>
                <p class="text-gray-600 mt-1">Manage and approve staff leave requests</p>
            </div>
            <a href="{{ route('admin.leaves.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus-circle text-xl'></i>
                Create Leave
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.leaves.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <i class='bx bx-search absolute left-3 top-3 text-gray-400 text-xl'></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or reason..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

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

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                    <select name="user_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Employees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('admin.leaves.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Leaves List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
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
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                                            <i class='bx bx-user text-blue-600'></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($leave->user->role) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $leave->type_color }}">
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
                                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $leave->status_color }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $leave->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.leaves.show', $leave->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm"
                                            title="View Details">
                                            <i class='bx bx-show text-base'></i>
                                        </a>

                                        @if($leave->status === 'pending')
                                            <button onclick="approveLeave({{ $leave->id }})"
                                                class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                title="Approve">
                                                <i class='bx bx-check text-base'></i>
                                            </button>
                                            <button onclick="rejectLeave({{ $leave->id }})"
                                                class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                title="Reject">
                                                <i class='bx bx-x text-base'></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('admin.leaves.edit', $leave->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-yellow-500 text-white hover:bg-yellow-600 rounded-full transition shadow-sm"
                                            title="Edit">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>

                                        <form action="{{ route('admin.leaves.destroy', $leave->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-500 text-white hover:bg-gray-600 rounded-full transition shadow-sm"
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
                                    <p>No leave requests found matching your criteria</p>
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
            form.addEventListener('submit', function(e) {
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