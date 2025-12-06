@extends('layouts.staff')

@section('title', 'Patients')
@section('page-title', 'Patients')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-group text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Patients</h1>
                        <p class="text-blue-100 text-sm mt-1">View and manage all patients</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-user mr-1'></i>
                        {{ $patients->total() }} Total Patients
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $maleCount = \App\Models\Patient::where('gender', 'male')->count();
                $femaleCount = \App\Models\Patient::where('gender', 'female')->count();
                $newThisMonth = \App\Models\Patient::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-group text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $patients->total() }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-male text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $maleCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Male</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-female text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $femaleCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Female</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user-plus text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $newThisMonth }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">New This Month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 rounded-2xl border border-blue-100/50 p-5">
            <form method="GET" action="{{ route('staff.patients.index') }}">
                <!-- Search Bar -->
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class='bx bx-search text-blue-500 text-xl'></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, email, phone, or patient ID..."
                        class="w-full pl-12 pr-4 py-3 bg-white border-0 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 text-gray-700 placeholder-gray-400">
                </div>

                <!-- Filter Options -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-blue-700 flex items-center gap-1.5">
                        <i class='bx bx-filter-alt'></i> Filters:
                    </span>

                    <select name="gender"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 text-sm text-gray-700">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>👨 Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>👩 Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>⚪ Other</option>
                    </select>

                    <div class="flex items-center gap-2 ml-auto">
                        @if(request()->hasAny(['search', 'gender']))
                            <a href="{{ route('staff.patients.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                                <i class='bx bx-x mr-1'></i> Clear
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-blue-600 hover:to-indigo-600 transition-all">
                            <i class='bx bx-search mr-1.5'></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Patients Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-blue-500'></i>
                        Patients List
                    </h3>
                    <span class="text-sm text-gray-500">Showing {{ $patients->firstItem() ?? 0 }} - {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} patients</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold">
                                        <i class='bx bx-id-card mr-1'></i>
                                        {{ $patient->patient_id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-sm">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name ?? '', 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">{{ $patient->full_name }}</div>
                                            <div class="text-xs text-gray-500">Registered {{ $patient->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($patient->email)
                                            <div class="flex items-center gap-1.5 text-sm text-gray-700">
                                                <i class='bx bx-envelope text-gray-400'></i>
                                                {{ $patient->email }}
                                            </div>
                                        @endif
                                        @if($patient->phone)
                                            <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                                <i class='bx bx-phone text-gray-400'></i>
                                                {{ $patient->phone }}
                                            </div>
                                        @endif
                                        @if(!$patient->email && !$patient->phone)
                                            <span class="text-gray-400 text-sm">No contact info</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($patient->gender)
                                        @php
                                            $genderConfig = [
                                                'male' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'icon' => 'bx-male'],
                                                'female' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'icon' => 'bx-female'],
                                                'other' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-user'],
                                            ];
                                            $config = $genderConfig[$patient->gender] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-user'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                            <i class='bx {{ $config['icon'] }}'></i>
                                            {{ ucfirst($patient->gender) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($patient->date_of_birth)
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $patient->date_of_birth->age }}</span>
                                            <span class="text-xs text-gray-500">years</span>
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $patient->date_of_birth->format('M d, Y') }}</div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <a href="{{ route('staff.patients.show', $patient->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm hover:shadow" title="View Details">
                                            <i class='bx bx-show text-sm'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class='bx bx-user-x text-3xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No patients found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($patients->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection