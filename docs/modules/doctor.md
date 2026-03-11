# Doctor Modules

The Doctor role focuses on patient care, appointment management, and personal professional tracking.

## Overview

The Doctor modules provide a streamlined interface for medical practitioners to manage their daily clinic activities without the overhead of full administrative functions.

## Key Modules

### 1. Dashboard
- **Controller**: `Doctor\DashboardController`
- **Purpose**: Personalized overview of the day's schedule, upcoming appointments, and quick stats.

### 2. Appointment Management
- **Controller**: `Doctor\AppointmentController`
- **Purpose**: Manage patient visits.
- **Features**: View scheduled appointments, mark attendance, and update appointment status.

### 3. Patient Records
- **Controller**: `Doctor\PatientController`
- **Purpose**: Access to patient clinical information.
- **Features**: View patient profiles and medical history.

### 4. Schedule & Availability
- **Controllers**: `Doctor\ScheduleController`, `Doctor\DoctorScheduleController`
- **Purpose**: Manage working hours.
- **Features**: Set availability and view personal duty rosters.

### 5. Professional Records
- **Controllers**: `Doctor\AttendanceController`, `Doctor\LeaveController`, `Doctor\PayslipController`
- **Purpose**: Personal HR tools.
- **Features**: Clocking in/out, applying for leave, and viewing personal payslips.

### 6. Clinical Communication
- **Controller**: `Doctor\ReferralLetterController`
- **Purpose**: Facilitate patient referrals.
- **Features**: Generate and manage referral letters for specialist care.

## Views

Main entry points are located in `resources/views/doctor/`.
