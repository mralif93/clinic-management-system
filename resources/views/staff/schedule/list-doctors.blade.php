@extends('layouts.staff')

@section('title', 'Doctor Schedules')
@section('page-title', 'Doctor Schedules')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-cyan-500 via-cyan-600 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-user-circle text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Doctor Schedules</h1>
                        <p class="text-cyan-100 text-sm mt-1">View working schedules to help arrange appointments</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-group mr-1'></i>
                        {{ $doctors->total() }} Doctors
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $doctors->total() }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Doctors</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-category text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ count($specializations) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Specializations</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $doctors->where('user.is_active', true)->count() }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Active</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ now()->format('l') }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Today</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-gradient-to-r from-cyan-50 via-teal-50 to-cyan-50 rounded-2xl border border-cyan-100/50 p-5">
            <form method="GET" action="{{ route('staff.schedule.doctors') }}">
                <!-- Search Bar -->
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class='bx bx-search text-cyan-500 text-xl'></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, email, or specialization..."
                        class="w-full pl-12 pr-4 py-3 bg-white border-0 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 text-gray-700 placeholder-gray-400">
                </div>

                <!-- Filter Options -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-cyan-700 flex items-center gap-1.5">
                        <i class='bx bx-filter-alt'></i> Filters:
                    </span>

                    <select name="specialization"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-cyan-500 text-sm text-gray-700 min-w-[160px]">
                        <option value="">All Specializations</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                        @endforeach
                    </select>

                    <div class="flex items-center gap-2 ml-auto">
                        @if(request()->hasAny(['search', 'specialization']))
                            <a href="{{ route('staff.schedule.doctors') }}"
                                class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                                <i class='bx bx-x mr-1'></i> Clear
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-cyan-500 to-teal-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-cyan-600 hover:to-teal-600 transition-all">
                            <i class='bx bx-search mr-1.5'></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Doctors Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-cyan-500'></i>
                        Doctors List
                    </h3>
                    <span class="text-sm text-gray-500">{{ $doctors->total() }} doctors found</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Doctor ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Specialization</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($doctors as $doctor)
                            <tr class="hover:bg-cyan-50/30 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-700 text-sm font-semibold">
                                        {{ $doctor->doctor_id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($doctor->user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">Dr. {{ $doctor->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $doctor->qualification }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        <i class='bx bx-briefcase-alt-2'></i>
                                        {{ $doctor->specialization }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 flex items-center gap-1">
                                        <i class='bx bx-envelope text-gray-400'></i>
                                        {{ $doctor->user->email ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                        <i class='bx bx-phone text-gray-400'></i>
                                        {{ $doctor->phone ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('staff.doctors.show', $doctor->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-600 transition shadow-sm hover:shadow"
                                            title="View Details">
                                            <i class='bx bx-show text-sm'></i>
                                        </a>
                                        <a href="{{ route('staff.schedule.view-doctor', $doctor->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-amber-500 text-white rounded-full hover:bg-amber-600 transition shadow-sm hover:shadow"
                                            title="View Schedule">
                                            <i class='bx bx-calendar text-sm'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class='bx bx-user-x text-3xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No doctors found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($doctors->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $doctors->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection