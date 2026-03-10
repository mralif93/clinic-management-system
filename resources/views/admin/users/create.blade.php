@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
        <i class='hgi-stroke hgi-user-add-01 text-2xl'></i>
    </div>
    <div>
        <h2 class="text-2xl font-bold">Create New User</h2>
        <p class="text-blue-100 text-sm mt-1">Add a new user to the system</p>
    </div>
</div>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='hgi-stroke hgi-arrow-left-01'></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                @csrf

                <!-- Account Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-user text-blue-600'></i>
                        Account Information
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('name') border-red-500 @enderror"
                                placeholder="Enter full name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('email') border-red-500 @enderror"
                                placeholder="Enter email address">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="md:col-span-2">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm bg-white @error('role') border-red-500 @enderror">
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Select the user's role to determine their permissions</p>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-lock text-blue-600'></i>
                        Security
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required minlength="8"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm @error('password') border-red-500 @enderror"
                                    placeholder="Enter password (min 8 characters)">
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class='hgi-stroke hgi-view-off-slash text-lg' id="passwordToggle"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    minlength="8"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                                    placeholder="Confirm password">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class='hgi-stroke hgi-view-off-slash text-lg' id="passwordConfirmationToggle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='hgi-stroke hgi-cancel-circle'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-all text-sm shadow-lg shadow-blue-600/20">
                        <i class='hgi-stroke hgi-floppy-disk'></i>
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
                    toggle.classList.remove('hgi-view-off-slash');
                    toggle.classList.add('hgi-eye');
                } else {
                    field.type = 'password';
                    toggle.classList.remove('hgi-eye');
                    toggle.classList.add('hgi-view-off-slash');
                }
            }

            document.getElementById('createUserForm').addEventListener('submit', function (e) {
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