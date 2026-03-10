@extends('layouts.admin')

@section('title', 'Digital Cards')
@section('page-title', 'Digital Cards')

@section('content')
    <div class="space-y-6">
        {{-- Page Header with Stats --}}
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-identity-card text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Digital ID Cards</h2>
                        <p class="text-indigo-100 text-sm mt-1">View and manage all staff and doctor identity cards</p>
                    </div>
                </div>
                <a href="{{ route('admin.digital-card.self') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-900/20">
                    <i class='hgi-stroke hgi-user-circle text-xl'></i>
                    My Card
                </a>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <p class="text-2xl font-bold text-gray-900">{{ $doctors->count() }}</p>
                    <p class="text-sm text-gray-500 font-medium mt-1">Doctors</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <p class="text-2xl font-bold text-gray-900">{{ $staff->count() }}</p>
                    <p class="text-sm text-gray-500 font-medium mt-1">Staff</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <p class="text-2xl font-bold text-gray-900">{{ $doctors->count() + $staff->count() }}</p>
                    <p class="text-sm text-gray-500 font-medium mt-1">Total Cards</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    @php
                        $expiredDoctors = $doctors->filter(fn($d) => $d->card_expiry_at && $d->card_expiry_at->isPast())->count();
                        $expiredStaff = $staff->filter(fn($s) => $s->card_expiry_at && $s->card_expiry_at->isPast())->count();
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">{{ $expiredDoctors + $expiredStaff }}</p>
                    <p class="text-sm text-gray-500 font-medium mt-1">Expired</p>
                </div>
            </div>
        </div>

        {{-- Filters Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <i class='hgi-stroke hgi-filter text-gray-500'></i>
                    <h3 class="font-semibold text-gray-700">Filter Cards</h3>
                </div>
            </div>
            <div class="p-5">
                <form method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Search --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class='hgi-stroke hgi-search-01'></i>
                                </span>
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Search by name, ID, specialization..."
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                            </div>
                        </div>

                        {{-- Type Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Type</label>
                            <select name="filter"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm bg-white">
                                <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="doctor" {{ $filter === 'doctor' ? 'selected' : '' }}>Doctors Only</option>
                                <option value="staff" {{ $filter === 'staff' ? 'selected' : '' }}>Staff Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all text-sm">
                            <i class='hgi-stroke hgi-search-01'></i>
                            Search
                        </button>
                        @if($search || $filter !== 'all')
                            <a href="{{ route('admin.digital-cards.index') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                                <i class='hgi-stroke hgi-cancel-circle'></i>
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Combined Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Person</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role / Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Card Issued</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{-- Doctors --}}
                        @if($filter === 'all' || $filter === 'doctor')
                            @foreach($doctors as $doctor)
                                @php
                                    $isExpired = $doctor->card_expiry_at && $doctor->card_expiry_at->isPast();
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                                @if($doctor->profile_photo)
                                                    <img src="{{ asset('storage/' . $doctor->profile_photo) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $doctor->full_name }}</p>
                                                <p class="text-xs text-gray-500 font-mono">{{ $doctor->doctor_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700">
                                            <i class='hgi-stroke hgi-doctor-01'></i>
                                            {{ $doctor->specialization ?? ucfirst($doctor->type ?? 'Doctor') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">{{ $doctor->phone ?? 'N/A' }}</p>
                                        @if($doctor->clinic_location)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $doctor->clinic_location }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($doctor->card_issued_at)
                                            <p class="text-sm text-gray-700">{{ $doctor->card_issued_at->format('d M Y') }}</p>
                                            @if($doctor->card_expiry_at)
                                                <p class="text-xs {{ $isExpired ? 'text-red-500' : 'text-gray-400' }} mt-0.5">
                                                    Exp: {{ $doctor->card_expiry_at->format('d M Y') }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-400">Not issued</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($isExpired)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-inset ring-red-500/20">
                                                <i class='hgi-stroke hgi-alert-circle'></i> Expired
                                            </span>
                                        @elseif($doctor->card_issued_at)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-inset ring-green-500/20">
                                                <i class='hgi-stroke hgi-checkmark-circle-02'></i> Valid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                                                <i class='hgi-stroke hgi-minus-sign'></i> No Card
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.digital-cards.show', ['type' => 'doctor', 'id' => $doctor->id]) }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-200 hover:scale-110 transition-all"
                                                title="View Card">
                                                <i class='hgi-stroke hgi-identity-card text-lg'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- Staff --}}
                        @if($filter === 'all' || $filter === 'staff')
                            @foreach($staff as $member)
                                @php
                                    $isExpired = $member->card_expiry_at && $member->card_expiry_at->isPast();
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-sm">
                                                @if($member->profile_photo)
                                                    <img src="{{ asset('storage/' . $member->profile_photo) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($member->first_name ?? 'S', 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $member->full_name }}</p>
                                                <p class="text-xs text-gray-500 font-mono">{{ $member->staff_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                            <i class='hgi-stroke hgi-user-list'></i>
                                            {{ $member->position ?? 'Staff' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">{{ $member->phone ?? 'N/A' }}</p>
                                        @if($member->department)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $member->department }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($member->card_issued_at)
                                            <p class="text-sm text-gray-700">{{ $member->card_issued_at->format('d M Y') }}</p>
                                            @if($member->card_expiry_at)
                                                <p class="text-xs {{ $isExpired ? 'text-red-500' : 'text-gray-400' }} mt-0.5">
                                                    Exp: {{ $member->card_expiry_at->format('d M Y') }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-400">Not issued</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($isExpired)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-inset ring-red-500/20">
                                                <i class='hgi-stroke hgi-alert-circle'></i> Expired
                                            </span>
                                        @elseif($member->card_issued_at)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-inset ring-green-500/20">
                                                <i class='hgi-stroke hgi-checkmark-circle-02'></i> Valid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                                                <i class='hgi-stroke hgi-minus-sign'></i> No Card
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.digital-cards.show', ['type' => 'staff', 'id' => $member->id]) }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all"
                                                title="View Card">
                                                <i class='hgi-stroke hgi-identity-card text-lg'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- Empty State --}}
                        @if($doctors->count() === 0 && $staff->count() === 0)
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class='hgi-stroke hgi-identity-card text-4xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No cards found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection