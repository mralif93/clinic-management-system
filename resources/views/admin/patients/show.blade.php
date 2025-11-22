@extends('layouts.admin')

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Patient Information</h3>
            <div class="flex space-x-2">
                @if(!$patient->trashed())
                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-edit mr-2'></i> Edit
                </a>
                @else
                <form action="{{ route('admin.patients.restore', $patient->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-refresh mr-2'></i> Restore
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Patient Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-2xl">{{ strtoupper(substr($patient->first_name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $patient->full_name }}</h2>
                            <p class="text-blue-600 font-semibold">{{ $patient->patient_id ?? 'N/A' }}</p>
                            @if($patient->date_of_birth)
                                <p class="text-gray-600">{{ $patient->date_of_birth->age }} years old</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($patient->trashed())
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Deleted
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($patient->user)
                    <div>
                        <label class="text-sm font-medium text-gray-500">User Account</label>
                        <p class="mt-1 text-gray-900">
                            <a href="{{ route('admin.users.show', $patient->user->id) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $patient->user->email }}
                            </a>
                        </p>
                    </div>
                    @else
                    <div>
                        <label class="text-sm font-medium text-gray-500">User Account</label>
                        <p class="mt-1 text-gray-500">Not linked to any user account</p>
                    </div>
                    @endif
                    @if($patient->gender)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Gender</label>
                        <p class="mt-1 text-gray-900">{{ ucfirst($patient->gender) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="mt-1 text-gray-900">{{ $patient->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $patient->phone ?? 'N/A' }}</p>
                </div>
                @if($patient->address)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500">Address</label>
                    <p class="mt-1 text-gray-900">{{ $patient->address }}</p>
                </div>
                @endif
                @if($patient->date_of_birth)
                <div>
                    <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                    <p class="mt-1 text-gray-900">{{ $patient->date_of_birth->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Medical Information -->
            @if($patient->medical_history || $patient->allergies)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Medical Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($patient->medical_history)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Medical History</label>
                        <p class="mt-1 text-gray-900">{{ $patient->medical_history }}</p>
                    </div>
                    @endif
                    @if($patient->allergies)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Allergies</label>
                        <p class="mt-1 text-gray-900">{{ $patient->allergies }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Emergency Contact -->
            @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($patient->emergency_contact_name)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Contact Name</label>
                        <p class="mt-1 text-gray-900">{{ $patient->emergency_contact_name }}</p>
                    </div>
                    @endif
                    @if($patient->emergency_contact_phone)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Contact Phone</label>
                        <p class="mt-1 text-gray-900">{{ $patient->emergency_contact_phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-8 border-t pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $patient->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $patient->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($patient->trashed())
                <div>
                    <label class="text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-gray-900">{{ $patient->deleted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

