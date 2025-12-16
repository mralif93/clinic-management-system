@extends('layouts.public')

@section('title', 'Login - Clinic Management System')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8 relative z-0">
    <div class="max-w-md w-full space-y-8 relative z-10">
        <div class="bg-white rounded-2xl shadow-xl p-8 relative z-20">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <i class='bx bx-clinic text-4xl text-blue-600'></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
                <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <form id="loginForm" class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email -->
                <x-forms.input-text
                    name="email"
                    label="Email Address"
                    type="email"
                    icon="bx-envelope"
                    placeholder="Enter your email"
                    required
                    :value="old('email')"
                    autocomplete="email"
                />

                <!-- Password -->
                <x-forms.input-text
                    name="password"
                    label="Password"
                    type="password"
                    icon="bx-lock"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                />

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        id="loginButton"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
                    >
                        <span id="loginButtonText" class="flex items-center">
                            <i class='bx bx-log-in mr-2'></i>
                            Sign In
                        </span>
                        <span id="loginButtonLoader" class="hidden flex items-center">
                            <i class='bx bx-loader-alt bx-spin mr-2'></i>
                            Signing in...
                        </span>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign up here
                        </a>
                    </p>
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

    // Login form with 3-second loader
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const button = document.getElementById('loginButton');
        const buttonText = document.getElementById('loginButtonText');
        const buttonLoader = document.getElementById('loginButtonLoader');
        
        // Disable button and show loader
        button.disabled = true;
        buttonText.classList.add('hidden');
        buttonLoader.classList.remove('hidden');
        
        // Show loading overlay
        Swal.fire({
            title: 'Signing in...',
            text: 'Please wait while we authenticate you',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Wait 3 seconds then submit form
        setTimeout(() => {
            form.submit();
        }, 3000);
    });
</script>
@endpush
@endsection

