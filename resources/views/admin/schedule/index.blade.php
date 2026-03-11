@extends('layouts.admin')

@section('title', 'Doctor Schedule Management')
@section('page-title', 'Doctor Schedule Management')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-clock-02 text-2xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Doctor Schedule Management</h1>
                        <p class="text-indigo-100 text-sm mt-1">Manage weekly working hours and slot durations</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div
                        class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 shadow-inner">
                        <span class="text-2xl font-bold text-white">{{ $doctors->count() }}</span>
                        <span class="text-[10px] text-indigo-100 font-bold uppercase tracking-wider whitespace-nowrap">Total
                            Doctors</span>
                    </div>
                </div>
            </div>
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
                                <i class='hgi-stroke hgi-calendar-03 text-lg text-emerald-500'></i>
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
                                <i class='hgi-stroke hgi-eye text-lg'></i>
                            </a>
                            <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-full hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                <i class='hgi-stroke hgi-pencil-edit-01'></i>
                                Manage
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i class='hgi-stroke hgi-user-block-01 text-4xl text-gray-400'></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">No Doctors Found</h3>
                        <p class="text-gray-500">Add doctors to the system to manage their schedules.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection