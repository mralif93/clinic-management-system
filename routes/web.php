<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Team Routes
Route::get('/team', [App\Http\Controllers\TeamController::class, 'index'])->name('team.index');

// Services Routes
Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');

// Packages Routes
Route::get('/packages', [App\Http\Controllers\PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package:slug}', [App\Http\Controllers\PackageController::class, 'show'])->name('packages.show');

// Announcements Routes
Route::get('/announcements', [HomeController::class, 'announcements'])->name('announcements.index');
Route::get('/announcements/{id}', [HomeController::class, 'showAnnouncement'])->name('announcements.show');

// Dynamic Page Route (must be after specific routes)
Route::get('/{slug}', [HomeController::class, 'page'])->name('page.show')
    ->where('slug', '^(?!admin|login|register|logout|forgot-password|reset-password|services|about|team|packages|announcements|staff|doctor|patient).*');

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
        Route::post('/appointments/{id}/cancel', [App\Http\Controllers\Patient\AppointmentController::class, 'cancel'])->name('appointments.cancel');
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
        // Check-in routes (no middleware)
        Route::get('/check-in', [App\Http\Controllers\Doctor\DashboardController::class, 'checkIn'])->name('check-in');
        Route::post('/check-in', [App\Http\Controllers\Doctor\DashboardController::class, 'storeCheckIn'])->name('check-in.store');

        // Attendance clock routes (no middleware - needed for clock-out during logout)
        Route::post('/attendance/clock-in', [App\Http\Controllers\Doctor\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
        Route::post('/attendance/clock-out', [App\Http\Controllers\Doctor\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

        // All other routes require check-in
        Route::middleware('doctor.checkin')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('dashboard');

            // Appointments
            Route::get('/appointments', [App\Http\Controllers\Doctor\AppointmentController::class, 'index'])->name('appointments.index');
            Route::get('/appointments/{id}', [App\Http\Controllers\Doctor\AppointmentController::class, 'show'])->name('appointments.show');
            Route::get('/appointments/{id}/edit', [App\Http\Controllers\Doctor\AppointmentController::class, 'edit'])->name('appointments.edit');
            Route::put('/appointments/{id}', [App\Http\Controllers\Doctor\AppointmentController::class, 'update'])->name('appointments.update');
            Route::get('/appointments/{id}/invoice', [App\Http\Controllers\Doctor\AppointmentController::class, 'invoice'])->name('appointments.invoice');

            // Profile
            Route::get('/profile', [App\Http\Controllers\Doctor\ProfileController::class, 'show'])->name('profile.show');
            Route::get('/profile/edit', [App\Http\Controllers\Doctor\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [App\Http\Controllers\Doctor\ProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [App\Http\Controllers\Doctor\ProfileController::class, 'updatePassword'])->name('profile.update-password');

            // Schedule
            Route::get('/schedule', [App\Http\Controllers\Doctor\ScheduleController::class, 'index'])->name('schedule.index');
            Route::get('/schedule/settings', [App\Http\Controllers\Doctor\DoctorScheduleController::class, 'settings'])->name('schedule.settings');
            Route::post('/schedule/settings', [App\Http\Controllers\Doctor\DoctorScheduleController::class, 'saveSettings'])->name('schedule.save-settings');

            // Patients
            Route::get('/patients', [App\Http\Controllers\Doctor\PatientController::class, 'index'])->name('patients.index');
            Route::get('/patients/{id}', [App\Http\Controllers\Doctor\PatientController::class, 'show'])->name('patients.show');

            // Attendance
            Route::get('/attendance', [App\Http\Controllers\Doctor\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/attendance/break-start', [App\Http\Controllers\Doctor\AttendanceController::class, 'startBreak'])->name('attendance.break-start');
            Route::post('/attendance/break-end', [App\Http\Controllers\Doctor\AttendanceController::class, 'endBreak'])->name('attendance.break-end');

            // To-Do List (My Tasks)
            Route::get('/todos', [App\Http\Controllers\Doctor\TodoController::class, 'index'])->name('todos.index');
            Route::get('/todos/{id}', [App\Http\Controllers\Doctor\TodoController::class, 'show'])->name('todos.show');
            Route::put('/todos/{id}/status', [App\Http\Controllers\Doctor\TodoController::class, 'updateStatus'])->name('todos.update-status');

            // Leave Management
            Route::resource('leaves', App\Http\Controllers\Doctor\LeaveController::class)->parameters(['leaves' => 'leave']);

            // Payslips (View Own)
            Route::get('/payslips', [App\Http\Controllers\Doctor\PayslipController::class, 'index'])->name('payslips.index');
            Route::get('/payslips/{id}', [App\Http\Controllers\Doctor\PayslipController::class, 'show'])->name('payslips.show');
        });
    });

    // Staff Routes
    Route::prefix('staff')->name('staff.')->group(function () {
        // Check-in page (not protected by check-in middleware)
        Route::get('/check-in', [App\Http\Controllers\Staff\DashboardController::class, 'checkIn'])->name('check-in');
        Route::post('/check-in', [App\Http\Controllers\Staff\DashboardController::class, 'storeCheckIn'])->name('check-in.store');

        // All other routes require check-in
        Route::middleware('staff.checkin')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

            // Patient Flow Dashboard
            Route::get('/patient-flow', [App\Http\Controllers\Staff\DashboardController::class, 'patientFlow'])->name('patient-flow');
            Route::post('/patient-flow/{id}/update-status', [App\Http\Controllers\Staff\DashboardController::class, 'updateFlowStatus'])->name('patient-flow.update-status');
            Route::get('/patient-flow/data', [App\Http\Controllers\Staff\DashboardController::class, 'getFlowData'])->name('patient-flow.data');

            // Appointments
            Route::get('/appointments', [App\Http\Controllers\Staff\AppointmentController::class, 'index'])->name('appointments.index');
            Route::get('/appointments/create', [App\Http\Controllers\Staff\AppointmentController::class, 'create'])->name('appointments.create');
            Route::post('/appointments', [App\Http\Controllers\Staff\AppointmentController::class, 'store'])->name('appointments.store');
            Route::get('/appointments/{id}', [App\Http\Controllers\Staff\AppointmentController::class, 'show'])->name('appointments.show');
            Route::get('/appointments/{id}/edit', [App\Http\Controllers\Staff\AppointmentController::class, 'edit'])->name('appointments.edit');
            Route::put('/appointments/{id}', [App\Http\Controllers\Staff\AppointmentController::class, 'update'])->name('appointments.update');
            Route::put('/appointments/{id}/status', [App\Http\Controllers\Staff\AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
            Route::get('/appointments/{id}/invoice', [App\Http\Controllers\Staff\AppointmentController::class, 'invoice'])->name('appointments.invoice');

            // Profile
            Route::get('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'show'])->name('profile.show');
            Route::get('/profile/edit', [App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('profile.update-password');

            // Schedule
            Route::get('/schedule', [App\Http\Controllers\Staff\ScheduleController::class, 'index'])->name('schedule.index');
            Route::get('/schedule/doctors', [App\Http\Controllers\Staff\ScheduleController::class, 'listDoctors'])->name('schedule.doctors');
            Route::get('/schedule/doctor/{doctor}', [App\Http\Controllers\Staff\ScheduleController::class, 'viewDoctorSchedule'])->name('schedule.view-doctor');

            // Patients
            Route::get('/patients', [App\Http\Controllers\Staff\PatientController::class, 'index'])->name('patients.index');
            Route::get('/patients/{id}', [App\Http\Controllers\Staff\PatientController::class, 'show'])->name('patients.show');

            // Doctors
            Route::get('/doctors/{id}', [App\Http\Controllers\Staff\DoctorController::class, 'show'])->name('doctors.show');

            // Reports
            Route::get('/reports', [App\Http\Controllers\Staff\ReportController::class, 'index'])->name('reports.index');

            // Attendance
            Route::get('/attendance', [App\Http\Controllers\Staff\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/attendance/clock-in', [App\Http\Controllers\Staff\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
            Route::post('/attendance/clock-out', [App\Http\Controllers\Staff\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
            Route::post('/attendance/break-start', [App\Http\Controllers\Staff\AttendanceController::class, 'startBreak'])->name('attendance.break-start');
            Route::post('/attendance/break-end', [App\Http\Controllers\Staff\AttendanceController::class, 'endBreak'])->name('attendance.break-end');
            Route::post('/attendance/correction', [App\Http\Controllers\Staff\AttendanceController::class, 'requestCorrection'])->name('attendance.correction');

            // To-Do List (My Tasks)
            Route::get('/todos', [App\Http\Controllers\Staff\TodoController::class, 'index'])->name('todos.index');
            Route::get('/todos/{id}', [App\Http\Controllers\Staff\TodoController::class, 'show'])->name('todos.show');
            Route::put('/todos/{id}/status', [App\Http\Controllers\Staff\TodoController::class, 'updateStatus'])->name('todos.update-status');

            // Leave Management
            Route::resource('leaves', App\Http\Controllers\Staff\LeaveController::class)->parameters(['leaves' => 'leave']);

            // Payslips (View Own)
            Route::get('/payslips', [App\Http\Controllers\Staff\PayslipController::class, 'index'])->name('payslips.index');
            Route::get('/payslips/{id}', [App\Http\Controllers\Staff\PayslipController::class, 'show'])->name('payslips.show');
        });
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
        Route::get('/appointments/trash', [App\Http\Controllers\Admin\AppointmentController::class, 'trash'])->name('appointments.trash');
        Route::get('/appointments/{id}/invoice', [App\Http\Controllers\Admin\AppointmentController::class, 'invoice'])->name('appointments.invoice')->where('id', '[0-9]+');
        Route::post('/appointments/{id}/restore', [App\Http\Controllers\Admin\AppointmentController::class, 'restore'])->name('appointments.restore')->where('id', '[0-9]+');
        Route::delete('/appointments/{id}/force-delete', [App\Http\Controllers\Admin\AppointmentController::class, 'forceDelete'])->name('appointments.force-delete')->where('id', '[0-9]+');
        Route::get('/appointments/{year}/{month}', [App\Http\Controllers\Admin\AppointmentController::class, 'byMonth'])->name('appointments.by-month')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{1,2}']);
        Route::resource('appointments', App\Http\Controllers\Admin\AppointmentController::class);

        // Service Management
        Route::post('/services/toggle-visibility', [App\Http\Controllers\Admin\ServiceController::class, 'toggleModuleVisibility'])->name('services.toggle-visibility');
        Route::post('/services/update-order', [App\Http\Controllers\Admin\ServiceController::class, 'updateModuleOrder'])->name('services.update-order');
        Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
        Route::post('/services/{id}/restore', [App\Http\Controllers\Admin\ServiceController::class, 'restore'])->name('services.restore');
        Route::delete('/services/{id}/force-delete', [App\Http\Controllers\Admin\ServiceController::class, 'forceDelete'])->name('services.force-delete');

        // Package Management
        Route::post('/packages/toggle-visibility', [App\Http\Controllers\Admin\PackageController::class, 'toggleModuleVisibility'])->name('packages.toggle-visibility');
        Route::post('/packages/update-order', [App\Http\Controllers\Admin\PackageController::class, 'updateModuleOrder'])->name('packages.update-order');
        Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
        Route::post('/packages/{id}/restore', [App\Http\Controllers\Admin\PackageController::class, 'restore'])->name('packages.restore');
        Route::delete('/packages/{id}/force-delete', [App\Http\Controllers\Admin\PackageController::class, 'forceDelete'])->name('packages.force-delete');

        // Team Management
        Route::post('/team/toggle-visibility', [App\Http\Controllers\Admin\TeamController::class, 'toggleModuleVisibility'])->name('team.toggle-visibility');
        Route::post('/team/update-order', [App\Http\Controllers\Admin\TeamController::class, 'updateModuleOrder'])->name('team.update-order');
        Route::resource('team', App\Http\Controllers\Admin\TeamController::class);
        Route::post('/team/{id}/restore', [App\Http\Controllers\Admin\TeamController::class, 'restore'])->name('team.restore');
        Route::delete('/team/{id}/force-delete', [App\Http\Controllers\Admin\TeamController::class, 'forceDelete'])->name('team.force-delete');

        // Announcement Management
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
        Route::post('/announcements/{id}/toggle-publish', [App\Http\Controllers\Admin\AnnouncementController::class, 'togglePublish'])->name('announcements.toggle-publish');
        Route::post('/announcements/{id}/toggle-featured', [App\Http\Controllers\Admin\AnnouncementController::class, 'toggleFeatured'])->name('announcements.toggle-featured');
        Route::post('/announcements/{id}/restore', [App\Http\Controllers\Admin\AnnouncementController::class, 'restore'])->name('announcements.restore');
        Route::delete('/announcements/{id}/force-delete', [App\Http\Controllers\Admin\AnnouncementController::class, 'forceDelete'])->name('announcements.force-delete');

        // Reports
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        // Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/auto-save', [App\Http\Controllers\Admin\SettingsController::class, 'updateSingle'])->name('settings.auto-save');
        Route::delete('/settings/logo', [App\Http\Controllers\Admin\SettingsController::class, 'removeLogo'])->name('settings.remove-logo');

        // Legacy page edit route (for About only) - MUST come before resource route
        Route::get('/pages/about', [App\Http\Controllers\Admin\SettingsController::class, 'editAbout'])
            ->name('pages.about');
        Route::post('/pages/about/toggle-visibility', [App\Http\Controllers\Admin\SettingsController::class, 'toggleAboutVisibility'])->name('pages.about.toggle-visibility');
        Route::post('/pages/about/update-order', [App\Http\Controllers\Admin\SettingsController::class, 'updateAboutOrder'])->name('pages.about.update-order');

        // Pages Management - Module Visibility Control only
        Route::get('/pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
        Route::post('/pages/{id}/toggle-status', [App\Http\Controllers\Admin\PageController::class, 'toggleStatus'])->name('pages.toggle-status');
        Route::post('/pages/{id}/update-order', [App\Http\Controllers\Admin\PageController::class, 'updateOrder'])->name('pages.update-order');

        // Style Guide
        Route::view('/style-guide/buttons', 'admin.style-guide.buttons')->name('style-guide.buttons');

        // Attendance Management
        Route::get('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/live', [App\Http\Controllers\Admin\AttendanceController::class, 'live'])->name('attendance.live');
        Route::get('/attendance/export', [App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.export');
        Route::get('/attendance/reports', [App\Http\Controllers\Admin\AttendanceController::class, 'reports'])->name('attendance.reports');
        Route::get('/attendance/trash', [App\Http\Controllers\Admin\AttendanceController::class, 'trash'])->name('attendance.trash');
        Route::get('/attendance/corrections', [App\Http\Controllers\Admin\AttendanceController::class, 'corrections'])->name('attendance.corrections');
        Route::get('/attendance/{attendance}/show', [App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('attendance.show')->where('attendance', '[0-9]+');
        Route::get('/attendance/{attendance}/edit', [App\Http\Controllers\Admin\AttendanceController::class, 'edit'])->name('attendance.edit')->where('attendance', '[0-9]+');
        Route::get('/attendance/{year}/{month}', [App\Http\Controllers\Admin\AttendanceController::class, 'byMonth'])->name('attendance.by-month')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{1,2}']);
        Route::post('/attendance/corrections/{correction}/approve', [App\Http\Controllers\Admin\AttendanceController::class, 'approveCorrection'])->name('attendance.corrections.approve');
        Route::post('/attendance/{id}/restore', [App\Http\Controllers\Admin\AttendanceController::class, 'restore'])->name('attendance.restore');
        Route::delete('/attendance/{id}/force-delete', [App\Http\Controllers\Admin\AttendanceController::class, 'forceDelete'])->name('attendance.force-delete');
        Route::post('/attendance/corrections/{correction}/reject', [App\Http\Controllers\Admin\AttendanceController::class, 'rejectCorrection'])->name('attendance.corrections.reject');
        Route::post('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');
        Route::put('/attendance/{attendance}', [App\Http\Controllers\Admin\AttendanceController::class, 'update'])->name('attendance.update');
        Route::post('/attendance/{attendance}/approve', [App\Http\Controllers\Admin\AttendanceController::class, 'approve'])->name('attendance.approve');
        Route::delete('/attendance/{attendance}', [App\Http\Controllers\Admin\AttendanceController::class, 'destroy'])->name('attendance.destroy');

        // To-Do Management
        Route::resource('todos', App\Http\Controllers\Admin\TodoController::class);
        Route::post('/todos/{id}/restore', [App\Http\Controllers\Admin\TodoController::class, 'restore'])->name('todos.restore');
        Route::delete('/todos/{id}/force-delete', [App\Http\Controllers\Admin\TodoController::class, 'forceDelete'])->name('todos.force-delete');

        // Leave Management
        Route::get('/leaves/trash', [App\Http\Controllers\Admin\LeaveController::class, 'trash'])->name('leaves.trash');
        Route::get('/leaves/{year}/{month}', [App\Http\Controllers\Admin\LeaveController::class, 'byMonth'])->name('leaves.by-month');
        Route::resource('leaves', App\Http\Controllers\Admin\LeaveController::class)->parameters(['leaves' => 'leave']);
        Route::post('/leaves/{id}/restore', [App\Http\Controllers\Admin\LeaveController::class, 'restore'])->name('leaves.restore');
        Route::delete('/leaves/{id}/force-delete', [App\Http\Controllers\Admin\LeaveController::class, 'forceDelete'])->name('leaves.force-delete');
        Route::post('/leaves/{leave}/approve', [App\Http\Controllers\Admin\LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{leave}/reject', [App\Http\Controllers\Admin\LeaveController::class, 'reject'])->name('leaves.reject');

        // Payroll Management
        Route::get('/payrolls/trash', [App\Http\Controllers\Admin\PayrollController::class, 'trash'])->name('payrolls.trash');
        Route::get('/payrolls/{year}/{month}', [App\Http\Controllers\Admin\PayrollController::class, 'byMonth'])->name('payrolls.by-month');
        Route::post('/payrolls/calculate-salary', [App\Http\Controllers\Admin\PayrollController::class, 'calculateSalary'])->name('payrolls.calculate-salary');
        Route::resource('payrolls', App\Http\Controllers\Admin\PayrollController::class)->parameters(['payrolls' => 'payroll']);
        Route::post('/payrolls/{payroll}/approve', [App\Http\Controllers\Admin\PayrollController::class, 'approve'])->name('payrolls.approve');
        Route::post('/payrolls/{payroll}/mark-as-paid', [App\Http\Controllers\Admin\PayrollController::class, 'markAsPaid'])->name('payrolls.mark-as-paid');
        Route::post('/payrolls/{id}/restore', [App\Http\Controllers\Admin\PayrollController::class, 'restore'])->name('payrolls.restore');
        Route::delete('/payrolls/{id}/force-delete', [App\Http\Controllers\Admin\PayrollController::class, 'forceDelete'])->name('payrolls.force-delete');

        // Schedule Management
        Route::get('/schedules', [App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{doctor}/manage', [App\Http\Controllers\Admin\ScheduleController::class, 'manage'])->name('schedules.manage');
        Route::post('/schedules/{doctor}/save', [App\Http\Controllers\Admin\ScheduleController::class, 'saveSettings'])->name('schedules.save-settings');
        Route::get('/schedules/{doctor}/view', [App\Http\Controllers\Admin\ScheduleController::class, 'view'])->name('schedules.view');

        // Export
        Route::post('/export/csv', [App\Http\Controllers\ExportController::class, 'csv'])->name('export.csv');
        Route::post('/export/json', [App\Http\Controllers\ExportController::class, 'json'])->name('export.json');
    });
});

// Global Search (available to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
    Route::get('/search/autocomplete', [App\Http\Controllers\SearchController::class, 'autocomplete'])->name('search.autocomplete');
});

