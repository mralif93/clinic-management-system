@extends('layouts.doctor')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <a href="{{ route('doctor.leaves.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                        <i class='bx bx-arrow-back'></i> Back to Leave Requests
                    </a>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-calendar-check text-xl'></i>
                        </div>
                        Leave Details
                    </h1>
                </div>
                @if($leave->status === 'pending')
                    <div class="flex gap-2">
                        <a href="{{ route('doctor.leaves.edit', $leave->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition">
                            <i class='bx bx-edit mr-2'></i> Edit
                        </a>
                        <form action="{{ route('doctor.leaves.destroy', $leave->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-500/80 backdrop-blur text-white font-medium rounded-xl hover:bg-red-600 transition">
                                <i class='bx bx-trash mr-2'></i> Cancel
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-info-circle text-emerald-500'></i> Status Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Status</p>
                                <div class="flex items-center gap-2">
                                    @if($leave->status === 'approved')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-lg font-bold bg-emerald-100 text-emerald-700">
                                            <i class='bx bx-check-circle mr-2'></i> Approved
                                        </span>
                                    @elseif($leave->status === 'rejected')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-lg font-bold bg-red-100 text-red-700">
                                            <i class='bx bx-x-circle mr-2'></i> Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-lg font-bold bg-amber-100 text-amber-700">
                                            <i class='bx bx-time mr-2'></i> Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Submitted On</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $leave->created_at->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $leave->created_at->format('h:i A') }}</p>
                            </div>
                        </div>

                        @if($leave->status !== 'pending' && $leave->reviewer)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Reviewed By</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                        <i class='bx bx-user text-xl text-gray-600'></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $leave->reviewer->name }}</p>
                                        <p class="text-sm text-gray-500">on {{ $leave->reviewed_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                @if($leave->admin_notes)
                                    <div class="mt-4 bg-gray-50 p-4 rounded-xl">
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Admin Notes:</p>
                                        <p class="text-gray-600 italic">"{{ $leave->admin_notes }}"</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Details Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-detail text-blue-500'></i> Request Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Leave Type</p>
                                <span class="px-3 py-1.5 rounded-lg text-sm font-semibold {{ $leave->type_color }}">
                                    {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Total Duration</p>
                                <p class="text-xl font-bold text-gray-800">{{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Start Date</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <i class='bx bx-calendar text-emerald-600'></i>
                                    </div>
                                    <p class="font-medium text-gray-800">{{ $leave->start_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">End Date</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                                        <i class='bx bx-calendar text-red-600'></i>
                                    </div>
                                    <p class="font-medium text-gray-800">{{ $leave->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Reason</p>
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
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-paperclip text-purple-500'></i> Attachment
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($leave->attachment)
                            <div class="text-center p-6 bg-gray-50 rounded-xl border border-gray-200">
                                @php
                                    $extension = pathinfo($leave->attachment, PATHINFO_EXTENSION);
                                @endphp

                                <div class="w-14 h-14 rounded-xl {{ in_array(strtolower($extension), ['jpg', 'jpeg', 'png']) ? 'bg-blue-100' : 'bg-red-100' }} flex items-center justify-center mx-auto mb-3">
                                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                        <i class='bx bx-image text-2xl text-blue-600'></i>
                                    @else
                                        <i class='bx bx-file-pdf text-2xl text-red-600'></i>
                                    @endif
                                </div>

                                <p class="text-sm font-medium text-gray-700 mb-4">Proof Document</p>
                                <a href="{{ Storage::url($leave->attachment) }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                    <i class='bx bx-download'></i> Download
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                    <i class='bx bx-file-blank text-2xl text-gray-400'></i>
                                </div>
                                <p class="text-gray-500 text-sm">No attachment provided</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-time text-amber-500'></i> Timeline
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="relative pl-6 border-l-2 border-gray-200 space-y-6">
                            <!-- Created -->
                            <div class="relative">
                                <div class="absolute -left-[25px] w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow"></div>
                                <p class="text-sm font-semibold text-gray-800">Request Submitted</p>
                                <p class="text-xs text-gray-500">{{ $leave->created_at->format('M d, Y h:i A') }}</p>
                            </div>

                            <!-- Reviewed -->
                            @if($leave->status !== 'pending')
                                <div class="relative">
                                    <div class="absolute -left-[25px] w-4 h-4 rounded-full {{ $leave->status === 'approved' ? 'bg-emerald-500' : 'bg-red-500' }} border-2 border-white shadow"></div>
                                    <p class="text-sm font-semibold text-gray-800">Request {{ ucfirst($leave->status) }}</p>
                                    <p class="text-xs text-gray-500">{{ $leave->reviewed_at->format('M d, Y h:i A') }}</p>
                                </div>
                            @else
                                <div class="relative">
                                    <div class="absolute -left-[25px] w-4 h-4 rounded-full bg-gray-300 border-2 border-white shadow"></div>
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