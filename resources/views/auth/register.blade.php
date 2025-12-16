@extends('layouts.public')

@section('title', 'Register - Clinic Management System')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <i class='bx bx-clinic text-4xl text-blue-600'></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                <p class="mt-2 text-sm text-gray-600">Sign up to get started</p>
            </div>

            <!-- Register Form -->
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Name -->
                <x-forms.input-text
                    name="name"
                    label="Full Name"
                    type="text"
                    icon="bx-user"
                    placeholder="Enter your full name"
                    required
                    :value="old('name')"
                    autocomplete="name"
                />

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
                    autocomplete="new-password"
                />

                <!-- Confirm Password -->
                <x-forms.input-text
                    name="password_confirmation"
                    label="Confirm Password"
                    type="password"
                    icon="bx-lock-alt"
                    placeholder="Confirm your password"
                    required
                    autocomplete="new-password"
                />

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out min-h-[44px]"
                    >
                        <i class='bx bx-user-plus mr-2'></i>
                        Create Account
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in here
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
</script>
@endpush
@endsection

