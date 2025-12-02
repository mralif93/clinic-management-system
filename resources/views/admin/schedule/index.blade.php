@extends('layouts.admin')

@section('title', 'Doctor Schedule Management')
@section('page-title', 'Doctor Schedule Management')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Doctor Schedule Management</h1>
                <p class="text-gray-600 mt-1">Manage working schedules for all doctors</p>
            </div>
        </div>

        <!-- Doctors List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Select Doctor</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($doctors as $doctor)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $doctor->user->name }}</h3>
                                    <p class="text-sm text-gray-600">ID: {{ $doctor->doctor_id }}</p>
                                    <p class="text-sm text-gray-600">{{ $doctor->specialization }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.schedules.manage', $doctor->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg">
                                    <i class='bx bx-edit mr-2'></i> Manage Schedule
                                </a>
                                <a href="{{ route('admin.schedules.view', $doctor->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                                    <i class='bx bx-show mr-2'></i> View Schedule
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
