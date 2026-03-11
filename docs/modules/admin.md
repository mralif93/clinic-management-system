# Admin Modules

The Admin role has the highest level of access and is responsible for managing all aspects of the clinic.

## Overview

The Admin modules provide a comprehensive suite of tools for clinic management, including staff and patient administration, appointment scheduling, financial management (payroll), and system configuration.

## Key Modules

### 1. Dashboard
- **Controller**: `Admin\DashboardController`
- **Purpose**: Provides a high-level overview of clinic operations, including statistics on appointments, patients, and revenue.

### 2. User & Staff Management
- **Controllers**: `Admin\UserController`, `Admin\StaffController`, `Admin\DoctorController`
- **Purpose**: Manage system users and clinic staff. 
- **Features**: 
    - Create/Edit/Delete users and staff.
    - Assign roles and permissions.
    - Manage doctor-specific information (specialties, etc.).

### 3. Patient Management
- **Controller**: `Admin\PatientController`
- **Purpose**: Centralized database of all patients.
- **Features**: Registration, profile management, and medical history access.

### 4. Appointment Management
- **Controller**: `Admin\AppointmentController`
- **Purpose**: Manage the clinic's schedule.
- **Features**: Book appointments, manage status (confirmed, cancelled, etc.), and view appointment lists.

### 5. Attendance & Leave
- **Controllers**: `Admin\AttendanceController`, `Admin\LeaveController`
- **Purpose**: Track staff presence and manage time-off requests.
- **Features**: Clock-in/out logs, leave submission and approval workflow.

### 6. Payroll System
- **Controller**: `Admin\PayrollController`
- **Purpose**: Manage staff salaries and payouts.
- **Features**: Calculate salaries, manage payslips, and track payment status.

### 7. Services & Packages
- **Controllers**: `Admin\ServiceController`, `Admin\PackageController`
- **Purpose**: Define the clinic's offerings.
- **Features**: Manage medical services provided and bundled service packages.

### 8. Communication
- **Controllers**: `Admin\AnnouncementController`, `Admin\ReferralLetterController`
- **Purpose**: Internal and external communication.
- **Features**: System-wide announcements and generation of referral letters for patients.

### 9. System Settings
- **Controller**: `Admin\SettingsController`
- **Purpose**: Configure clinic-wide parameters.
- **Features**: Clinic details, operational hours, and system preferences.

## Views

Main entry points are located in `resources/views/admin/`.
