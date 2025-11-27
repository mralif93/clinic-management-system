<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Services Routes
Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset Routes (Email functionality not yet implemented)
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Patient Routes (using public layout)
    Route::prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

        // Appointments
        Route::get('/appointments', [App\Http\Controllers\Patient\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [App\Http\Controllers\Patient\AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [App\Http\Controllers\Patient\AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{id}', [App\Http\Controllers\Patient\AppointmentController::class, 'show'])->name('appointments.show');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Patient\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [App\Http\Controllers\Patient\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Patient\ProfileController::class, 'update'])->name('profile.update');

        // Records
        Route::get('/records', [App\Http\Controllers\Patient\RecordController::class, 'index'])->name('records.index');
    });

    // Doctor Routes
    Route::prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('dashboard');

        // Appointments
        Route::get('/appointments', [App\Http\Controllers\Doctor\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{id}', [App\Http\Controllers\Doctor\AppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{id}/status', [App\Http\Controllers\Doctor\AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Doctor\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [App\Http\Controllers\Doctor\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Doctor\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [App\Http\Controllers\Doctor\ProfileController::class, 'updatePassword'])->name('profile.update-password');

        // Schedule
        Route::get('/schedule', [App\Http\Controllers\Doctor\ScheduleController::class, 'index'])->name('schedule.index');

        // Patients
        Route::get('/patients', [App\Http\Controllers\Doctor\PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{id}', [App\Http\Controllers\Doctor\PatientController::class, 'show'])->name('patients.show');
    });

    // Staff Routes
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

        // Appointments
        Route::get('/appointments', [App\Http\Controllers\Staff\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{id}', [App\Http\Controllers\Staff\AppointmentController::class, 'show'])->name('appointments.show');
        Route::get('/appointments/{id}/edit', [App\Http\Controllers\Staff\AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{id}', [App\Http\Controllers\Staff\AppointmentController::class, 'update'])->name('appointments.update');
        Route::put('/appointments/{id}/status', [App\Http\Controllers\Staff\AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('profile.update-password');

        // Schedule
        Route::get('/schedule', [App\Http\Controllers\Staff\ScheduleController::class, 'index'])->name('schedule.index');

        // Patients
        Route::get('/patients', [App\Http\Controllers\Staff\PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{id}', [App\Http\Controllers\Staff\PatientController::class, 'show'])->name('patients.show');

        // Reports
        Route::get('/reports', [App\Http\Controllers\Staff\ReportController::class, 'index'])->name('reports.index');

        // To-Do List (My Tasks)
        Route::get('/todos', [App\Http\Controllers\Staff\TodoController::class, 'index'])->name('todos.index');
        Route::get('/todos/{id}', [App\Http\Controllers\Staff\TodoController::class, 'show'])->name('todos.show');
        Route::put('/todos/{id}/status', [App\Http\Controllers\Staff\TodoController::class, 'updateStatus'])->name('todos.update-status');
    });

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::post('/users/{id}/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [App\Http\Controllers\Admin\UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('/users/{id}/unlock', [App\Http\Controllers\Admin\UserController::class, 'unlock'])->name('users.unlock');
        Route::post('/users/{id}/reset-attempts', [App\Http\Controllers\Admin\UserController::class, 'resetAttempts'])->name('users.reset-attempts');

        // Patient Management
        Route::resource('patients', App\Http\Controllers\Admin\PatientController::class);
        Route::post('/patients/{id}/restore', [App\Http\Controllers\Admin\PatientController::class, 'restore'])->name('patients.restore');
        Route::delete('/patients/{id}/force-delete', [App\Http\Controllers\Admin\PatientController::class, 'forceDelete'])->name('patients.force-delete');

        // Doctor Management
        Route::resource('doctors', App\Http\Controllers\Admin\DoctorController::class);
        Route::post('/doctors/{id}/restore', [App\Http\Controllers\Admin\DoctorController::class, 'restore'])->name('doctors.restore');
        Route::delete('/doctors/{id}/force-delete', [App\Http\Controllers\Admin\DoctorController::class, 'forceDelete'])->name('doctors.force-delete');

        // Staff Management
        Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);
        Route::post('/staff/{id}/restore', [App\Http\Controllers\Admin\StaffController::class, 'restore'])->name('staff.restore');
        Route::delete('/staff/{id}/force-delete', [App\Http\Controllers\Admin\StaffController::class, 'forceDelete'])->name('staff.force-delete');

        // Appointment Management
        Route::resource('appointments', App\Http\Controllers\Admin\AppointmentController::class);
        Route::post('/appointments/{id}/restore', [App\Http\Controllers\Admin\AppointmentController::class, 'restore'])->name('appointments.restore');
        Route::delete('/appointments/{id}/force-delete', [App\Http\Controllers\Admin\AppointmentController::class, 'forceDelete'])->name('appointments.force-delete');

        // Service Management
        Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
        Route::post('/services/{id}/restore', [App\Http\Controllers\Admin\ServiceController::class, 'restore'])->name('services.restore');
        Route::delete('/services/{id}/force-delete', [App\Http\Controllers\Admin\ServiceController::class, 'forceDelete'])->name('services.force-delete');

        // Reports
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        // Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::delete('/settings/logo', [App\Http\Controllers\Admin\SettingsController::class, 'removeLogo'])->name('settings.remove-logo');

        // To-Do Management
        Route::resource('todos', App\Http\Controllers\Admin\TodoController::class);
        Route::post('/todos/{id}/restore', [App\Http\Controllers\Admin\TodoController::class, 'restore'])->name('todos.restore');
        Route::delete('/todos/{id}/force-delete', [App\Http\Controllers\Admin\TodoController::class, 'forceDelete'])->name('todos.force-delete');
    });
});

