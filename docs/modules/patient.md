# Patient Modules

The Patient role provides a secure portal for patients to interact with the clinic and manage their health records.

## Overview

Patient modules are designed for ease of use and provide transparency regarding appointments and medical visit history.

## Key Modules

### 1. Dashboard
- **Controller**: `Patient\DashboardController`
- **Purpose**: At-a-glance view of upcoming appointments and recent notifications.

### 2. Appointment Booking
- **Controller**: `Patient\AppointmentController`
- **Purpose**: Self-service appointment management.
- **Features**: Requesting new appointments and viewing existing ones.

### 3. Medical Records
- **Controller**: `Patient\RecordController`
- **Purpose**: Access to personal clinical data.
- **Features**: View visit history and clinical records.

### 4. Profile Management
- **Controller**: `Patient\ProfileController`
- **Purpose**: Update personal information.
- **Features**: Management of contact details and medical preferences.

## Views

Main entry points are located in `resources/views/patient/`.
