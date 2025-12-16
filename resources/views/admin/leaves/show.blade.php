@extends('layouts.admin')

@section('title', 'Leave Details')
@section('page-title', 'Leave Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-violet-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                    <i class='bx bx-calendar-minus text-4xl'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Leave Request</h1>
                    <p class="text-violet-100 flex items-center gap-2 mt-1">
                        <i class='bx bx-user'></i>
                        {{ $leave->user->name }} • {{ ucfirst($leave->user->role) }}
                    </p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-400/30',
                                'approved' => 'bg-green-400/30',
                                'rejected' => 'bg-red-400/30',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusColors[$leave->status] ?? 'bg-gray-400/30' }}">
                            @if($leave->status === 'approved')
                                <i class='bx bx-check-circle mr-1'></i>
                            @elseif($leave->status === 'rejected')
                                <i class='bx bx-x-circle mr-1'></i>
                            @else
                                <i class='bx bx-time mr-1'></i>
                            @endif
                            {{ ucfirst($leave->status) }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $leave->type_color }} bg-white/20">
                            {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] ?? ucfirst($leave->leave_type) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if($leave->status === 'pending')
                    <button onclick="approveLeave({{ $leave->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-green-600 rounded-full font-semibold hover:bg-green-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-check-circle text-lg'></i>
                        Approve
                    </button>
                    <button onclick="rejectLeave({{ $leave->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-red-600 rounded-full font-semibold hover:bg-red-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-x-circle text-lg'></i>
                        Reject
                    </button>
                    
                    <div class="w-px h-8 bg-white/30 mx-1"></div>
                @endif
                
                <a href="{{ route('admin.leaves.edit', $leave->id) }}" title="Edit Leave"
                   class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-violet-600 hover:bg-violet-50 hover:scale-105 transition-all shadow-lg">
                    <i class='bx bx-edit text-xl'></i>
                </a>
                <a href="{{ route('admin.leaves.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur text-white rounded-full font-medium hover:bg-white/30 transition-all">
                    <i class='bx bx-arrow-back'></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
                    <i class='bx bx-calendar text-2xl text-violet-600'></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Start Date</p>
                    <p class="text-lg font-bold text-gray-900">{{ $leave->start_date->format('M d') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                    <i class='bx bx-calendar-check text-2xl text-purple-600'></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">End Date</p>
                    <p class="text-lg font-bold text-gray-900">{{ $leave->end_date->format('M d') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class='bx bx-time text-2xl text-blue-600'></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Duration</p>
                    <p class="text-lg font-bold text-gray-900">{{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                @php
                    $statusStyle = [
                        'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                        'approved' => ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
                        'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-600'],
                    ];
                    $style = $statusStyle[$leave->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                @endphp
                <div class="w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center">
                    <i class='bx bx-badge-check text-2xl {{ $style['text'] }}'></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-lg font-bold {{ $style['text'] }}">{{ ucfirst($leave->status) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-violet-600'></i>
                        Employee Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-violet-50 flex items-center justify-center">
                            <span class="text-2xl font-bold text-violet-600">{{ strtoupper(substr($leave->user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $leave->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ ucfirst($leave->user->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-sm border-l-4 overflow-hidden {{ $leave->status === 'approved' ? 'border-green-500' : ($leave->status === 'rejected' ? 'border-red-500' : 'border-yellow-500') }}">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-info-circle text-violet-600'></i>
                        Request Status
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Status</p>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="text-2xl font-bold {{ $leave->status === 'approved' ? 'text-green-600' : ($leave->status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                                @if($leave->status === 'approved')
                                    <i class='bx bx-check-circle text-2xl text-green-500'></i>
                                @elseif($leave->status === 'rejected')
                                    <i class='bx bx-x-circle text-2xl text-red-500'></i>
                                @else
                                    <i class='bx bx-time text-2xl text-yellow-500'></i>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Submitted On</p>
                            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->created_at->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $leave->created_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    @if($leave->status !== 'pending' && $leave->reviewer)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Reviewed By</p>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-sm font-bold text-gray-600">{{ strtoupper(substr($leave->reviewer->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $leave->reviewer->name }}</p>
                                    <p class="text-sm text-gray-500">on {{ $leave->reviewed_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            @if($leave->admin_notes)
                                <div class="mt-4 bg-gray-50 p-4 rounded-xl">
                                    <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                                    <p class="text-gray-600 italic">"{{ $leave->admin_notes }}"</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-detail text-violet-600'></i>
                        Request Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Leave Type</p>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium border {{ $leave->type_color }}">
                                {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] ?? ucfirst($leave->leave_type) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Total Duration</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Start Date</p>
                            <div class="flex items-center gap-2">
                                <i class='bx bx-calendar text-gray-400'></i>
                                <p class="font-medium text-gray-800">{{ $leave->start_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">End Date</p>
                            <div class="flex items-center gap-2">
                                <i class='bx bx-calendar text-gray-400'></i>
                                <p class="font-medium text-gray-800">{{ $leave->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Reason</p>
                        <div class="bg-gray-50 p-4 rounded-xl text-gray-700 leading-relaxed">
                            {{ $leave->reason }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Attachment Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-paperclip text-violet-600'></i>
                        Attachment
                    </h3>
                </div>
                <div class="p-6">
                    @if($leave->attachment)
                        <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-200">
                            @php
                                $extension = pathinfo($leave->attachment, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                <i class='bx bx-image text-4xl text-blue-500 mb-2'></i>
                            @else
                                <i class='bx bx-file-pdf text-4xl text-red-500 mb-2'></i>
                            @endif
                            <p class="text-sm font-medium text-gray-700 mb-4">Proof Document</p>
                            <a href="{{ Storage::url($leave->attachment) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class='bx bx-download'></i> Download
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class='bx bx-file-blank text-4xl mb-2'></i>
                            <p>No attachment provided</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-git-branch text-violet-600'></i>
                        Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-4 border-l-2 border-gray-200 space-y-6">
                        <div class="relative">
                            <div class="absolute -left-[21px] bg-blue-500 h-4 w-4 rounded-full border-2 border-white"></div>
                            <p class="text-sm font-bold text-gray-800">Request Submitted</p>
                            <p class="text-xs text-gray-500">{{ $leave->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($leave->status !== 'pending')
                            <div class="relative">
                                <div class="absolute -left-[21px] {{ $leave->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }} h-4 w-4 rounded-full border-2 border-white"></div>
                                <p class="text-sm font-bold text-gray-800">Request {{ ucfirst($leave->status) }}</p>
                                <p class="text-xs text-gray-500">{{ $leave->reviewed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @else
                            <div class="relative">
                                <div class="absolute -left-[21px] bg-gray-300 h-4 w-4 rounded-full border-2 border-white"></div>
                                <p class="text-sm font-medium text-gray-400">Awaiting Review</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
                <div class="p-6 border-b border-red-100 bg-red-50/50">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                        <i class='bx bx-error-circle text-red-600'></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.leaves.destroy', $leave->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm">
                            <i class='bx bx-trash'></i>
                            Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
@endpush

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