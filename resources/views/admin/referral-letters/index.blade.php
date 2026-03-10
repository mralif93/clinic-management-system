@extends('layouts.admin')
@section('title', 'Referral Letters')
@section('page-title', 'Referral Letters')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div
            class="bg-gradient-to-r from-primary-600 via-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-medical-file text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Referral Letters</h2>
                        <p class="text-primary-100 text-sm mt-1">All referral letters across all doctors</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Stat Card -->
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-xl px-5 py-3 flex items-center gap-4 border border-white/10 shadow-sm hover:bg-white/20 transition-colors">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <i class='hgi-stroke hgi-file-01 text-xl text-primary-100'></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold leading-none">{{ $letters->total() }}</p>
                            <p class="text-[10px] text-primary-200 mt-1 uppercase tracking-widest font-bold">Total Letters
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('admin.referral-letters.index') }}" class="flex gap-3 flex-wrap items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Search
                        Patient</label>
                    <div class="relative">
                        <i class='hgi-stroke hgi-search-01 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Patient name…"
                            class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Doctor</label>
                    <select name="doctor_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                                Dr. {{ $doc->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued</option>
                    </select>
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Urgency</label>
                    <select name="urgency"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="routine" {{ request('urgency') === 'routine' ? 'selected' : '' }}>Routine</option>
                        <option value="urgent" {{ request('urgency') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="emergency" {{ request('urgency') === 'emergency' ? 'selected' : '' }}>Emergency
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                        <i class='hgi-stroke hgi-filter'></i> Filter
                    </button>
                    @if(request('search') || request('status') || request('doctor_id') || request('urgency'))
                        <a href="{{ route('admin.referral-letters.index') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($letters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ref No.
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Patient
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Referring Doctor</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Referred To</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Specialty</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Urgency
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date
                                </th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($letters as $letter)
                                @php
                                    $urgencyMeta = [
                                        'routine' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                                        'urgent' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                        'emergency' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                    ][$letter->urgency] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-blue-700">
                                        {{ $letter->referral_number }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-semibold text-gray-900">{{ $letter->patient->full_name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $letter->patient->patient_id ?? '' }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-medium text-gray-800">Dr. {{ $letter->doctor->full_name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $letter->doctor->specialization ?? '' }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-medium text-gray-800">{{ $letter->referred_to_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $letter->referred_to_facility }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-600">{{ $letter->referred_to_specialty }}</td>
                                    <td class="px-5 py-3.5">
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $urgencyMeta['bg'] }} {{ $urgencyMeta['text'] }}">
                                            {{ ucfirst($letter->urgency) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($letter->isIssued())
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 flex items-center gap-1 w-fit">
                                                <i class='hgi-stroke hgi-checkmark-circle-02'></i> Issued
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 flex items-center gap-1 w-fit">
                                                <i class='hgi-stroke hgi-edit-02'></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-gray-500">
                                        {{ $letter->issued_at ? $letter->issued_at->format('d M Y') : $letter->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.referral-letters.show', $letter->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                                title="View">
                                                <i class='hgi-stroke hgi-view'></i>
                                            </a>
                                            <form class="admin-delete-form"
                                                action="{{ route('admin.referral-letters.destroy', $letter->id) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="admin-delete-btn w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 rounded-lg transition"
                                                    title="Delete">
                                                    <i class='hgi-stroke hgi-delete-02'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($letters->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $letters->links() }}
                    </div>
                @endif

            @else
                <div class="flex flex-col items-center py-16">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class='hgi-stroke hgi-medical-file text-3xl text-gray-300'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No referral letters found</p>
                    <p class="text-gray-400 text-sm mt-1">Referral letters created by doctors will appear here</p>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.admin-delete-btn');
            if (!btn) return;
            const form = btn.closest('.admin-delete-form');
            Swal.fire({
                title: 'Delete Referral Letter?',
                text: 'This action is permanent and cannot be undone.',
                icon: 'warning',
                iconColor: '#dc2626',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    </script>
@endpush