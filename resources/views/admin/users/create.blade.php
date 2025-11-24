@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user-plus text-blue-600 mr-3'></i>
                    Create New User
                </h2>
                <p class="text-sm text-gray-600 mt-1">Add a new user to the system</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class='bx bx-arrow-back mr-2 text-base'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class='bx bx-user mr-1 text-sm text-blue-600'></i>
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       required
                       autofocus
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('name') border-red-500 ring-red-200 @enderror"
                       placeholder="Enter full name">
                @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class='bx bx-error-circle mr-1'></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class='bx bx-envelope mr-1 text-sm text-blue-600'></i>
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 ring-red-200 @enderror"
                       placeholder="Enter email address">
                @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class='bx bx-error-circle mr-1'></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class='bx bx-lock-alt mr-1 text-sm text-blue-600'></i>
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           minlength="8"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter password (min 8 characters)">
                    <button type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i class='bx bx-... text-base' id="passwordToggle"></i>
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    <i class='bx bx-info-circle mr-1'></i>
                    Password must be at least 8 characters long
                </p>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class='bx bx-error-circle mr-1'></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class='bx bx-lock mr-1 text-sm text-blue-600'></i>
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           minlength="8"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Confirm password">
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i class='bx bx-... text-base' id="passwordConfirmationToggle"></i>
                    </button>
                </div>
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class='bx bx-shield-quarter mr-1 text-sm text-blue-600'></i>
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role" 
                        name="role" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('role') border-red-500 ring-red-200 @enderror">
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
                <p class="mt-2 text-xs text-gray-500">
                    <i class='bx bx-info-circle mr-1'></i>
                    Select the user's role to determine their permissions
                </p>
                @error('role')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class='bx bx-error-circle mr-1'></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class='bx bx-x mr-2 text-base'></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                    <i class='bx bx-save mr-2 text-base'></i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const toggle = document.getElementById(fieldId === 'password' ? 'passwordToggle' : 'passwordConfirmationToggle');
        
        if (field.type === 'password') {
            field.type = 'text';
            toggle.classList.remove('bx-hide');
            toggle.classList.add('bx-show');
        } else {
            field.type = 'password';
            toggle.classList.remove('bx-show');
            toggle.classList.add('bx-hide');
        }
    }

    // Form validation
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirmation) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and confirmation do not match.',
                width: '450px'
            });
            return false;
        }
    });
</script>
@endpush
@endsection
