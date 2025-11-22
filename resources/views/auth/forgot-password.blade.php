@extends('layouts.public')

@section('title', 'Forgot Password - Clinic Management System')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <i class='bx bx-lock text-4xl text-blue-600'></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Forgot Password</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your email address and we'll send you a link to reset your password</p>
            </div>

            <!-- Info Alert -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class='bx bx-info-circle text-yellow-600 text-xl mr-3 mt-0.5'></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Email Service Not Available</p>
                        <p>Email hosting is not yet configured. Please contact the administrator to reset your password.</p>
                    </div>
                </div>
            </div>

            <!-- Forgot Password Form -->
            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf

                <!-- Email -->
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
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                            placeholder="Enter your email"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        <i class='bx bx-send mr-2'></i>
                        Send Reset Link
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

