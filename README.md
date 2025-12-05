# Clinic Management System

A comprehensive Laravel-based clinic management system with role-based access control, appointment management, patient records, and modern UI.

## 🚀 Features

### Core Features
- ✅ **Laravel 12** (Latest Version) Framework
- ✅ **Custom Authentication** with Login/Register/Forgot Password
- ✅ **Role-Based Access Control** (Admin, Doctor, Staff, Patient)
- ✅ **Account Security** - Account lockout after 5 failed login attempts
- ✅ **Beautiful UI** with Tailwind CSS CDN, Boxicons, and SweetAlert2
- ✅ **Responsive Design** with Poppins font
- ✅ **SQLite Database** for local development (MySQL for production)

### Admin Features
- ✅ **Dashboard** with statistics and overview
- ✅ **User Management** - Full CRUD with soft delete, account unlock/reset
- ✅ **Patient Management** - Full CRUD with soft delete, auto-generated IDs (PAT-XXXXXX)
- ✅ **Doctor Management** - Full CRUD with soft delete, auto-generated IDs (DOC-XXXXXX)
- ✅ **Staff Management** - Full CRUD with soft delete, auto-generated IDs (STF-XXXXXX)
- ✅ **Appointment Management** - Full CRUD with soft delete, commission tracking for locum doctors
- ✅ **Service Management** - Full CRUD with soft delete (Psychology & Homeopathy)
- ✅ **Reports & Analytics** - Statistics, revenue tracking, date range filtering
- ✅ **Settings Management** - Configurable clinic settings, currency, landing page content
- ✅ **Attendance Management** - Live dashboard, manual entry, approval system, correction requests, CSV export
- ✅ **To-Do Management** - Task assignment, priority levels, recurring tasks, soft delete
- ✅ **Leave Management** - Full approval workflow, file attachments, soft delete, filtering
- ✅ **Payroll Management** - Multi-employment type support (Full-Time, Part-Time, Locum), auto-calculate salary, detailed breakdowns

### Doctor Features
- ✅ **Dashboard** with appointment statistics
- ✅ **My Appointments** - View and manage assigned appointments with commission display (locum doctors)
- ✅ **My Profile** - View and edit profile, change password
- ✅ **Schedule** - Daily and weekly view of appointments
- ✅ **Patients** - View patients with appointment history
- ✅ **Appointment Updates** - Update status, diagnosis, prescription, notes
- ✅ **Attendance Tracking** - Clock in/out, break management, work duration tracking
- ✅ **Leave Management** - Apply for leave, view status, upload proof, cancel pending requests
- ✅ **Payslip Access** - View approved/paid payslips with detailed commission breakdown (locum doctors)

### Staff Features
- ✅ **Dashboard** with clinic statistics
- ✅ **Appointments** - View and manage all clinic appointments
- ✅ **Schedule** - Daily and weekly view of all appointments
- ✅ **Patients** - View and manage all patients
- ✅ **My Profile** - View and edit profile, change password
- ✅ **Reports** - View clinic statistics and analytics
- ✅ **Attendance Tracking** - Clock in/out, break management, correction requests
- ✅ **My Tasks** - View assigned to-dos, update task status, track priorities
- ✅ **Leave Management** - Apply for leave, view status, upload proof, cancel pending requests
- ✅ **Payslip Access** - View approved/paid payslips with hours breakdown (part-time staff)

### Patient Features
- ✅ **Dashboard** with personal statistics
- ✅ **Public Layout** with consistent navigation

### Public Features
- ✅ **Landing Page** - Dynamic content from admin settings
- ✅ **Services Listing** - View Psychology & Homeopathy treatments
- ✅ **Service Details** - Individual service pages
- ✅ **Authentication Pages** - Login, Register, Forgot Password, Reset Password

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM (for asset compilation)
- SQLite (for local development) or MySQL (for production)

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd clinic-management-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Build assets** (optional - using CDNs)
   ```bash
   npm run build
   ```

5. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

6. **Generate application key**
   ```bash
   php artisan key:generate
   ```

7. **Create SQLite database file** (for local development)
   ```bash
   touch database/database.sqlite
   ```

8. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

9. **Run migrations**
   ```bash
   php artisan migrate
   ```

10. **Seed database** (creates default users and data)
    ```bash
    php artisan db:seed
    ```

11. **Start the development server**
    ```bash
    php artisan serve
    ```

## 👥 Default Users

After seeding, you can login with these credentials:

### Admin
- **Email:** `admin@clinic.com`
- **Password:** `password`
- **Access:** Full admin panel access

### Doctor
- **Email:** `doctor@clinic.com`
- **Password:** `password`
- **Access:** Doctor dashboard, appointments, patients, schedule, profile

### Staff
- **Email:** `staff@clinic.com`
- **Password:** `password`
- **Access:** Staff dashboard, appointments, patients, schedule, reports, profile

### Patient
- **Email:** `patient@clinic.com`
- **Password:** `password`
- **Access:** Patient dashboard

**Additional test users:**
- `doctor1@clinic.com` / `doctor2@clinic.com` (Doctors)
- `staff1@clinic.com` / `staff2@clinic.com` (Staff)
- `patient1@clinic.com` / `patient2@clinic.com` / `patient3@clinic.com` (Patients)

## 🛣️ Routes

### Public Routes
- `/` - Landing page
- `/login` - Login page
- `/register` - Registration page
- `/forgot-password` - Forgot password page
- `/reset-password/{token}` - Reset password page
- `/services` - Services listing
- `/services/{slug}` - Service details

### Patient Routes (Authenticated)
- `/patient/dashboard` - Patient dashboard

### Doctor Routes (Authenticated)
- `/doctor/dashboard` - Doctor dashboard
- `/doctor/appointments` - My appointments (with commission display for locum)
- `/doctor/appointments/{id}` - Appointment details (with commission breakdown for locum)
- `/doctor/profile` - My profile
- `/doctor/profile/edit` - Edit profile
- `/doctor/schedule` - My schedule
- `/doctor/patients` - My patients
- `/doctor/patients/{id}` - Patient details
- `/doctor/attendance` - Attendance tracking
- `/doctor/leaves` - Leave management
- `/doctor/payslips` - My payslips (approved/paid only)
- `/doctor/payslips/{id}` - Payslip details with commission breakdown

### Staff Routes (Authenticated)
- `/staff/dashboard` - Staff dashboard
- `/staff/appointments` - All appointments
- `/staff/appointments/{id}` - Appointment details
- `/staff/appointments/{id}/edit` - Edit appointment
- `/staff/schedule` - Clinic schedule
- `/staff/patients` - All patients
- `/staff/patients/{id}` - Patient details
- `/staff/profile` - My profile
- `/staff/profile/edit` - Edit profile
- `/staff/reports` - Reports & analytics
- `/staff/attendance` - Attendance tracking
- `/staff/todos` - My tasks (to-do list)
- `/staff/leaves` - Leave management
- `/staff/payslips` - My payslips (approved/paid only)
- `/staff/payslips/{id}` - Payslip details with hours breakdown

### Admin Routes (Authenticated + Admin Role)
- `/admin/dashboard` - Admin dashboard
- `/admin/users` - User management
- `/admin/patients` - Patient management
- `/admin/doctors` - Doctor management
- `/admin/staff` - Staff management
- `/admin/appointments` - Appointment management
- `/admin/services` - Service management
- `/admin/reports` - Reports & analytics
- `/admin/settings` - System settings
- `/admin/attendance` - Attendance management
- `/admin/attendance/live` - Live attendance dashboard
- `/admin/attendance/reports` - Attendance reports
- `/admin/attendance/corrections` - Attendance correction requests
- `/admin/todos` - To-Do management
- `/admin/leaves` - Leave management
- `/admin/payrolls` - Payroll management
- `/admin/payrolls/create` - Create payroll with auto-calculate
- `/admin/payrolls/{year}/{month}` - Monthly payroll list
- `/admin/payrolls/{id}` - Payslip details with breakdowns
- `/admin/payrolls/{id}/edit` - Edit payroll

## 👤 User Roles

### Admin
- Full system access
- User management (all roles)
- Patient, Doctor, Staff management
- Appointment management with commission tracking
- Service management
- Reports and analytics
- System settings
- Attendance management and approval
- To-Do assignment and tracking
- Payroll management with multi-employment type support

### Doctor
- View assigned appointments with commission display (locum doctors)
- Update appointment status, diagnosis, prescription
- View patient information
- Manage profile
- View schedule
- Track attendance (clock in/out, breaks)
- View payslips with commission breakdown (locum doctors)

### Staff
- View all appointments
- Edit appointments
- View all patients
- View clinic schedule
- View reports
- Manage profile
- Track attendance (clock in/out, breaks, correction requests)
- View and update assigned tasks
- View payslips with hours breakdown (part-time staff)

### Patient
- View personal dashboard
- Access public services
- Register and login

## 🔐 Security Features

- **Account Lockout:** Accounts are locked after 5 failed login attempts for 30 minutes
- **Password Hashing:** All passwords are securely hashed
- **CSRF Protection:** All forms include CSRF tokens
- **Role-Based Middleware:** Routes protected by role-based middleware
- **Soft Deletes:** Data can be restored after deletion
- **Input Validation:** All inputs are validated

## 📊 Database Structure

### Main Tables
- `users` - User accounts with roles and employment types (full_time, part_time, locum)
- `patients` - Patient profiles (linked to users)
- `doctors` - Doctor profiles (linked to users) with commission rates
- `staff` - Staff profiles (linked to users)
- `appointments` - Appointment records with fees and commission tracking
- `services` - Treatment services
- `settings` - System configuration
- `attendances` - Attendance records with clock in/out times and total hours
- `attendance_breaks` - Break tracking for attendance
- `attendance_corrections` - Correction requests for attendance
- `todos` - Task management with assignments and priorities
- `leaves` - Leave requests with status, type, and proof attachments
- `payrolls` - Payroll records with multi-employment type support

### Auto-Generated IDs
- **Patients:** PAT-000001, PAT-000002, etc.
- **Doctors:** DOC-000001, DOC-000002, etc.
- **Staff:** STF-000001, STF-000002, etc.

## 🎨 UI Components

- **Tailwind CSS** - Utility-first CSS framework (CDN)
- **Boxicons** - Icon library (CDN)
- **SweetAlert2** - Beautiful alert dialogs (CDN)
- **Poppins Font** - Google Fonts
- **Responsive Design** - Mobile-friendly layouts

## 📝 Services

The system includes two main service types:
- **Psychology** - Psychology treatment services
- **Homeopathy** - Homeopathy treatment services

Services can be managed from the admin panel and are dynamically displayed on the landing page.

## ⚙️ Settings

Admin can configure:
- Clinic name
- Currency and currency symbol
- Landing page content (hero text, stats, CTA, footer)
- Email settings (for future email functionality)

## 💰 Payroll System

### Multi-Employment Type Support

The system supports three employment types with different salary calculation methods:

#### 1. **Full-Time Employees**
- **Calculation:** Fixed monthly salary from `users.basic_salary`
- **Display:** Blue badge with briefcase icon
- **Payslip:** Shows basic salary and allowances

#### 2. **Part-Time Staff**
- **Calculation:** `Total Approved Hours × Hourly Rate`
- **Hours Source:** Attendance records (clock-in to clock-out minus breaks)
- **Display:** Orange badge with clock icon
- **Payslip:** Includes detailed **Hours Breakdown** section showing:
  - Date of each working day
  - Hours worked per day
  - Hourly rate
  - Daily and total earnings
- **Approval:** Only approved attendance records are counted

#### 3. **Locum Doctors**
- **Calculation:** `Total Appointment Fees × Commission Rate`
- **Fee Source:** Completed/confirmed appointments
- **Display:** Purple badge with wallet icon
- **Payslip:** Includes detailed **Commission Breakdown** section showing:
  - Date of each appointment
  - Patient name
  - Appointment fee
  - Commission amount per appointment
  - Total commission earned
- **Commission Rate:** Configurable per doctor (default: 60%)

### Payroll Features

- ✅ **Auto-Calculate Salary** - Automatically calculates based on employment type
- ✅ **Detailed Breakdowns** - Shows all source data (hours/appointments)
- ✅ **Employment Type Badges** - Color-coded visual indicators
- ✅ **Monthly View** - List all payrolls by year/month
- ✅ **Status Workflow** - Draft → Approved → Paid
- ✅ **Access Control** - Staff/doctors can only view approved/paid payslips
- ✅ **Print/Download** - Professional payslip template with breakdowns
- ✅ **Allowances & Deductions** - Support for EPF, SOCSO, tax, bonuses
- ✅ **Payment Methods** - Bank transfer, cash, cheque

### Appointment Commission Display

For locum doctors, commission information is displayed in:

- **Appointment List View:**
  - Commission badge showing rate and amount
  - Purple-themed design
  - Visible in doctor's appointment list

- **Appointment Detail View:**
  - Commission breakdown card
  - Shows fee, rate, and calculated commission
  - Patient and appointment details

## 🔄 Soft Deletes

All major entities support soft deletes:
- Users can be restored
- Patients can be restored
- Doctors can be restored
- Staff can be restored
- Appointments can be restored
- Services can be restored

## 📱 Responsive Design

The system is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones

## 🛠️ Technologies Used

- **Backend:**
  - Laravel 12 (PHP 8.2+)
  - SQLite (local) / MySQL (production)
  - Eloquent ORM

- **Frontend:**
  - Tailwind CSS (CDN)
  - Boxicons (CDN)
  - SweetAlert2 (CDN)
  - Poppins Font (Google Fonts)
  - Blade Templating

- **Development:**
  - Composer (PHP dependency management)
  - NPM (Node.js package management)
  - Vite (Asset bundling)

## 📄 License

MIT License

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For support, please contact the administrator or create an issue in the repository.

---

**Built with ❤️ using Laravel 12**
