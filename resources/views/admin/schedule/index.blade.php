@extends('layouts.admin')

@section('title', 'Doctor Schedule Management')
@section('page-title', 'Doctor Schedule Management')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-time text-primary-600 text-2xl'></i>
                    Doctor Schedule Management
                </h1>
                <p class="text-sm text-gray-600">Manage working schedules for all doctors</p>
            </div>
        </div>

        <!-- Doctors List -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Select Doctor</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($doctors as $doctor)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-semibold text-lg shadow-sm">
                                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $doctor->user->name }}</h3>
                                    <p class="text-sm text-gray-600">ID: {{ $doctor->doctor_id }}</p>
                                    <p class="text-sm text-gray-600">{{ $doctor->specialization }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 shadow-sm">
                                    <i class='bx bx-edit'></i> Manage Schedule
                                </a>
                                <a href="{{ route('admin.schedules.view', $doctor->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                                    <i class='bx bx-show'></i> View Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <i class='bx bx-user-x text-4xl text-gray-400 mb-3'></i>
                        <p class="text-gray-600">No doctors found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
