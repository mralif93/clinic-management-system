# Staff Modules

The Staff role is designated for clinic receptionists and assistants who handle the flow of patients and administrative support.

## Overview

Staff modules focus on operational efficiency, patient intake, and supporting the doctors in managing the clinic's day-to-day activities.

## Key Modules

### 1. Dashboard
- **Controller**: `Staff\DashboardController`
- **Purpose**: Overview of active appointments and clinic status.

### 2. Front Desk Operations
- **Controllers**: `Staff\AppointmentController`, `Staff\QrScannerController`
- **Purpose**: Manage patient arrivals.
- **Features**: Patient check-in via QR code, appointment booking, and status updates.

### 3. Patient Administration
- **Controller**: `Staff\PatientController`
- **Purpose**: Maintain patient records.
- **Features**: Registering new patients and updating profiles.

### 4. Clinic Support
- **Controllers**: `Staff\DoctorController`, `Staff\ReportController`
- **Purpose**: Assist clinic operations.
- **Features**: View doctor availability and generate basic operational reports.

### 5. Personal HR
- **Controllers**: `Staff\AttendanceController`, `Staff\LeaveController`, `Staff\PayslipController`, `Staff\ProfileController`
- **Purpose**: Manage individual employment records.
- **Features**: Clocking in/out, leave application, and viewing payslips.

## Views

Main entry points are located in `resources/views/staff/`.
