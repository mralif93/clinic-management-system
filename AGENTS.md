# AGENTS.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Development commands

### Initial setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
```

If using SQLite locally, set `DB_CONNECTION=sqlite` and `DB_DATABASE=/absolute/path/to/database/database.sqlite` in `.env`.

### Run the app
```bash
php artisan serve
npm run dev
```

### Build assets
```bash
npm run build
```

### Tests
```bash
composer test
```

Run all tests via Artisan:
```bash
php artisan test
```

Run a single test file:
```bash
php artisan test tests/Unit/LeaveOverlapTest.php
```

Run one test method/class by filter:
```bash
php artisan test --filter=AppointmentConflictTest
```

### Code style / linting
```bash
./vendor/bin/pint
```

### Useful domain-specific Artisan commands
```bash
php artisan attendance:auto-clock-out
php artisan todos:generate-recurring
```

## High-level architecture

### Application shape
- Laravel 12 monolith with Blade-rendered UI and Eloquent models.
- Role-oriented modules are organized under `app/Http/Controllers/{Admin,Doctor,Staff,Patient}`.
- Route composition is centralized in `routes/web.php`; middleware aliases are registered in `bootstrap/app.php`.

### Request flow and access control
- Public marketing/content routes + auth routes are in `routes/web.php`.
- Authenticated app areas are split by prefix (`/admin`, `/doctor`, `/staff`, `/patient`) with role checks.
- `admin` access is enforced by `app/Http/Middleware/AdminMiddleware.php`.
- Doctor/staff app areas are additionally gated by daily attendance check-in middleware:
  - `doctor.checkin` (`DoctorCheckInMiddleware`)
  - `staff.checkin` (`StaffCheckInMiddleware`)
- The dynamic slug route (`/{slug}`) is intentionally placed after specific routes; keep it near the end of `routes/web.php` to avoid shadowing.

### Core domain model
- `User` is the auth root and carries `role` and payroll-related employment fields.
- Role profiles are 1:1 with `User`:
  - `Patient`
  - `Doctor`
  - `Staff`
- `Appointment` is the operational center for patient flow, consultation lifecycle, and payment status.
- Supporting HR/ops models:
  - `Attendance` + `AttendanceBreak` + `AttendanceCorrection`
  - `Leave`
  - `Todo` (including recurring task templates)
  - `Payroll`
- Settings and public content are DB-backed (`Setting`, `Page`, `Service`, `Package`, `TeamMember`, `Announcement`) and rendered through public/home controllers.

### Important cross-cutting business logic
- Authentication lockout is custom:
  - login logic: `app/Http/Controllers/AuthController.php`
  - counters/lock methods: `app/Models/User.php`
  - thresholds: `config/security.php`
- Appointment lifecycle/status constants and conflict checks are in `app/Models/Appointment.php`; multiple controllers rely on these shared status values.
- Staff QR check-in flow uses appointment `confirmation_token` and status transitions (`scheduled -> arrived -> ...`) in `app/Http/Controllers/Staff/QrScannerController.php`.
- Payroll computation is employment-type dependent in `app/Http/Controllers/Admin/PayrollController.php`:
  - full-time: fixed salary
  - part-time: approved attendance hours × hourly rate
  - locum: appointment fees × commission rate

### Scheduling and automation
- Scheduled tasks are defined in `routes/console.php`:
  - recurring todo generation (`todos:generate-recurring`)
  - auto clock-out for open attendances (`attendance:auto-clock-out`)
- Command implementations are in `app/Console/Commands`.

### Frontend and assets
- UI is primarily Blade + CDN assets (Tailwind, Alpine, SweetAlert, Boxicons), with shared role layouts in `resources/views/layouts`.
- Vite is present but minimal (`resources/js/app.js` as entry in `vite.config.js`).
- Many reusable JS/CSS utilities are loaded from `public/js` and `public/css` via Blade layouts.

### Data and seeds
- Schema and feature evolution are migration-driven in `database/migrations`.
- Seed orchestration is in `database/seeders/DatabaseSeeder.php`; `UserSeeder` creates default role users used for local testing.

### Deployment notes (Vercel)
- Serverless entrypoint is `api/index.php` (not just `public/index.php`).
- Routing/runtime behavior for production deploys is controlled by `vercel.json`.
