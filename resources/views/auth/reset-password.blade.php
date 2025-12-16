@extends('layouts.public')

@section('title', 'Reset Password - Clinic Management System')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <i class='bx bx-key text-4xl text-blue-600'></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your new password</p>
            </div>

            <!-- Info Alert -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class='bx bx-info-circle text-yellow-600 text-xl mr-3 mt-0.5'></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Email Service Not Available</p>
                        <p>This page is for demonstration purposes. Email hosting is not yet configured.</p>
                    </div>
                </div>
            </div>

            <!-- Reset Password Form -->
            <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? old('token') }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <!-- Email (Read-only) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-envelope text-gray-400'></i>
                        </div>
                        <input 
                            id="email" 
                            name="email_display" 
                            type="email" 
                            value="{{ $email ?? old('email') }}"
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed" 
                            readonly
                            disabled
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-lock text-gray-400'></i>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror" 
                            placeholder="Enter new password"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-lock-alt text-gray-400'></i>
                        </div>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            placeholder="Confirm new password"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
                        disabled
                    >
                        <i class='bx bx-check-circle mr-2'></i>
                        Reset Password
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        <i class='bx bx-arrow-back mr-1'></i>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    @if($errors->any())
        showError('{!! implode('<br>', $errors->all()) !!}', 'Validation Error');
    @endif

    // Disable form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        showInfo('Email hosting is not yet configured. Please contact the administrator to reset your password.', 'Email Service Not Available');
    });
</script>
@endpush
@endsection

