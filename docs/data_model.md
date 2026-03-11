# Data Model

The system's data model is centered around patients, appointments, and staff management.

## Core Entities

### 1. User (`App\Models\User`)
- **Role**: Base identity for all system interactions.
- **Attributes**: `name`, `email`, `role`, `password`.
- **Relationships**: 
    - Has one `Doctor` profile.
    - Has one `Staff` profile.
    - Has one `Patient` profile.

### 2. Patient (`App\Models\Patient`)
- **Role**: Represents a person receiving medical care.
- **Attributes**: `name`, `ic_number` (ID), `phone_number`, `medical_history`.
- **Relationships**: 
    - Has many `Appointments`.

### 3. Doctor (`App\Models\Doctor`)
- **Role**: Medical practitioner profile.
- **Attributes**: `name`, `specialty`, `registration_number`.
- **Relationships**: 
    - Has many `Appointments`.
    - Has many `DoctorSchedules`.

### 4. Staff (`App\Models\Staff`)
- **Role**: Clinic support staff profile.
- **Attributes**: `name`, `position`.
- **Relationships**: 
    - Has many `Attendances`.

### 5. Appointment (`App\Models\Appointment`)
- **Role**: Record of a scheduled visit.
- **Attributes**: `appointment_date`, `start_time`, `status`, `notes`.
- **Relationships**: 
    - Belongs to `Patient`.
    - Belongs to `Doctor`.

### 6. Attendance (`App\Models\Attendance`)
- **Role**: Shift tracking for staff.
- **Attributes**: `date`, `check_in`, `check_out`, `status`.

## Supporting Entities

- **Leave**: Tracks employee time-off requests.
- **Payroll**: Monthly salary records for staff.
- **Service & Package**: Catalog of medical services and bundles.
- **ReferralLetter**: Official letters for external medical referrals.
- **Todo**: Simple task management for staff.
- **Announcement**: Internal system-wide notifications.
