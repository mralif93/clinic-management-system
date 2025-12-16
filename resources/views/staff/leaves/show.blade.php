@extends('layouts.staff')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.leaves.index') }}"
                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition">
                            <i class='bx bx-arrow-back text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Leave Details</h1>
                            <p class="text-purple-100 text-sm mt-1">View leave request information</p>
                        </div>
                    </div>

                    @if($leave->status === 'pending')
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('staff.leaves.edit', $leave->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                                <i class='bx bx-edit mr-2'></i> Edit
                            </a>
                            <form action="{{ route('staff.leaves.destroy', $leave->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-500 text-white font-medium rounded-xl hover:bg-red-600 transition shadow-lg">
                                    <i class='bx bx-trash mr-2'></i> Cancel
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Status Card -->
                    <div
                        class="bg-white rounded-xl shadow-lg p-6 border-l-4 {{ $leave->status === 'approved' ? 'border-green-500' : ($leave->status === 'rejected' ? 'border-red-500' : 'border-yellow-500') }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Status</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span
                                        class="text-2xl font-bold {{ $leave->status === 'approved' ? 'text-green-600' : ($leave->status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
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
                                <p class="mt-1 text-lg font-semibold text-gray-800">
                                    {{ $leave->created_at->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $leave->created_at->format('h:i A') }}</p>
                            </div>
                        </div>

                        @if($leave->status !== 'pending' && $leave->reviewer)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Reviewed By</p>
                                <div class="mt-2 flex items-center gap-3">
                                    <div class="bg-gray-100 p-2 rounded-full">
                                        <i class='bx bx-user text-xl text-gray-600'></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $leave->reviewer->name }}</p>
                                        <p class="text-sm text-gray-500">on {{ $leave->reviewed_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                @if($leave->admin_notes)
                                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                                        <p class="text-gray-600 italic">"{{ $leave->admin_notes }}"</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Details Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Request Details</h3>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Leave Type</p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $leave->type_color }}">
                                    {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Duration</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $leave->total_days }}
                                    {{ Str::plural('day', $leave->total_days) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Start Date</p>
                                <div class="flex items-center gap-2">
                                    <i class='bx bx-calendar text-gray-400'></i>
                                    <p class="font-medium text-gray-800">{{ $leave->start_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">End Date</p>
                                <div class="flex items-center gap-2">
                                    <i class='bx bx-calendar text-gray-400'></i>
                                    <p class="font-medium text-gray-800">{{ $leave->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Reason</p>
                            <div class="bg-gray-50 p-4 rounded-lg text-gray-700 leading-relaxed">
                                {{ $leave->reason }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Attachment Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Attachment</h3>
                        @if($leave->attachment)
                            <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
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
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
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

                    <!-- Timeline Card (Placeholder for future) -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Timeline</h3>
                        <div class="relative pl-4 border-l-2 border-gray-200 space-y-6">
                            <!-- Created -->
                            <div class="relative">
                                <div class="absolute -left-[21px] bg-blue-500 h-4 w-4 rounded-full border-2 border-white">
                                </div>
                                <p class="text-sm font-bold text-gray-800">Request Submitted</p>
                                <p class="text-xs text-gray-500">{{ $leave->created_at->format('M d, Y h:i A') }}</p>
                            </div>

                            <!-- Reviewed -->
                            @if($leave->status !== 'pending')
                                <div class="relative">
                                    <div
                                        class="absolute -left-[21px] {{ $leave->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }} h-4 w-4 rounded-full border-2 border-white">
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">Request {{ ucfirst($leave->status) }}</p>
                                    <p class="text-xs text-gray-500">{{ $leave->reviewed_at->format('M d, Y h:i A') }}</p>
                                </div>
                            @else
                                <div class="relative">
                                    <div class="absolute -left-[21px] bg-gray-300 h-4 w-4 rounded-full border-2 border-white">
                                    </div>
                                    <p class="text-sm font-medium text-gray-400">Awaiting Review</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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
@endsection