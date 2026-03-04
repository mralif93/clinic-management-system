@extends('layouts.doctor')
@section('title', 'Referral Letters')
@section('page-title', 'Referral Letters')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <i class='bx bx-transfer text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Referral Letters</h2>
                        <p class="text-emerald-100 text-sm">Manage patient referrals and specialist communications</p>
                    </div>
                </div>
                <a href="{{ route('doctor.referral-letters.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-emerald-700 font-semibold text-sm rounded-xl hover:bg-emerald-50 transition shadow-lg">
                    <i class='bx bx-plus'></i> New Referral Letter
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('doctor.referral-letters.index') }}" class="flex gap-3 flex-wrap items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Search
                        Patient</label>
                    <div class="relative">
                        <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Patient name…"
                            class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('doctor.referral-letters.index') }}"
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
                                    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-emerald-700">
                                        {{ $letter->referral_number }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-semibold text-gray-900">{{ $letter->patient->full_name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $letter->patient->patient_id ?? '' }}</p>
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
                                                <i class='bx bx-check-circle'></i> Issued
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 flex items-center gap-1 w-fit">
                                                <i class='bx bx-edit-alt'></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-gray-500">
                                        {{ $letter->issued_at ? $letter->issued_at->format('d M Y') : $letter->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('doctor.referral-letters.show', $letter->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition"
                                                title="View">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            @if($letter->isDraft())
                                                <a href="{{ route('doctor.referral-letters.edit', $letter->id) }}"
                                                    class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                                    title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                            @endif
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
                        <i class='bx bx-transfer text-3xl text-gray-300'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No referral letters found</p>
                    <p class="text-gray-400 text-sm mt-1 mb-5">Create your first referral letter to get started</p>
                    <a href="{{ route('doctor.referral-letters.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition">
                        <i class='bx bx-plus'></i> New Referral Letter
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection