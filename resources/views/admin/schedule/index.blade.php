@extends('layouts.admin')

@section('title', 'Doctor Schedule Management')
@section('page-title', 'Doctor Schedule Management')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-white/20 p-2 backdrop-blur-sm">
                            <i class='bx bx-time text-2xl text-white'></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Doctor Schedule Management</h1>
                    </div>
                    <p class="text-indigo-100 max-w-xl">Manage weekly working hours, break times, and slot durations for all
                        doctors.</p>
                </div>

                <!-- Quick Stats -->
                <div class="flex gap-4">
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur-sm border border-white/10">
                        <p class="text-2xl font-bold text-white">{{ $doctors->count() }}</p>
                        <p class="text-xs text-indigo-100 font-medium">Total Doctors</p>
                    </div>
                </div>
            </div>

            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Doctors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($doctors as $doctor)
                <div
                    class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $doctor->user->name }}</h3>
                                    <p class="text-sm text-indigo-600 font-medium">{{ $doctor->specialization }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-500">Doctor ID</span>
                                <span class="font-semibold text-gray-900">#{{ $doctor->doctor_id }}</span>
                            </div>

                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <i class='bx bx-calendar-check text-lg text-emerald-500'></i>
                                <span>Schedule Configured</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-medium">Actions</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.schedules.view', $doctor->id) }}"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all"
                                title="View Schedule">
                                <i class='bx bx-show text-lg'></i>
                            </a>
                            <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-full hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                <i class='bx bx-edit'></i>
                                Manage
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-user-x text-4xl text-gray-400'></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">No Doctors Found</h3>
                        <p class="text-gray-500">Add doctors to the system to manage their schedules.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection