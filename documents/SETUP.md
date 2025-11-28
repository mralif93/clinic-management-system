# Setup Guide

## Framework Information
- **Laravel 12** (Latest Version)
- Released: February 2025
- Requires PHP 8.2, 8.3, or 8.4

## Prerequisites
- PHP 8.2 or higher (8.2, 8.3, or 8.4) (with SQLite extension enabled)
- Composer
- Node.js (optional, for future frontend assets)

**Note**: This project uses SQLite for local development, so no separate database server is required!

## Installation Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup (SQLite)
The project is configured to use SQLite for local development. Create the database file:
```bash
touch database/database.sqlite
```

Edit `.env` file and configure your database (SQLite is already set as default):
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

Or simply use the default (recommended):
```
DB_CONNECTION=sqlite
# DB_DATABASE will default to database/database.sqlite
```

**Note**: Make sure the `database` directory is writable.

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed Database (Optional - Creates default admin user)
```bash
php artisan db:seed
```

This will create:
- Admin user: `admin@clinic.com` / `password`
- Regular user: `user@clinic.com` / `password`

### 6. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Routes Overview

### Public Routes
- `GET /` - Redirects to login
- `GET /login` - Login page
- `POST /login` - Handle login
- `GET /register` - Registration page
- `POST /register` - Handle registration

### Protected Routes (Requires Authentication)
- `POST /logout` - Logout user

### Admin Routes (Requires Admin Role)
- `GET /admin/dashboard` - Admin dashboard

## Middleware

1. **auth** - Checks if user is authenticated
2. **guest** - Redirects authenticated users away from login/register
3. **admin** - Checks if user has admin role

## Features Implemented

✅ Custom Login/Register with Tailwind CSS
✅ Public Layout (for login/register pages)
✅ Admin Layout (for admin dashboard)
✅ Authentication Middleware
✅ Admin Role Middleware
✅ Guest Middleware
✅ Beautiful UI with:
   - Tailwind CSS CDN
   - Boxicons CDN
   - SweetAlert2 CDN

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   └── DashboardController.php
│   │   ├── AuthController.php
│   │   └── Controller.php
│   └── Middleware/
│       ├── AdminMiddleware.php
│       ├── Authenticate.php
│       └── GuestMiddleware.php
├── Models/
│   └── User.php
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── admin/
│   └── dashboard.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
└── layouts/
    ├── admin.blade.php
    └── public.blade.php

routes/
└── web.php
```

## Troubleshooting

### Issue: "Class not found" errors
**Solution**: Run `composer dump-autoload`

### Issue: "Route not found"
**Solution**: Clear route cache with `php artisan route:clear`

### Issue: "Session driver not found"
**Solution**: Make sure you've run migrations: `php artisan migrate`

### Issue: "SQLite database not found"
**Solution**: Create the database file:
```bash
touch database/database.sqlite
```

### Issue: "SQLite extension not enabled"
**Solution**: Enable SQLite extension in your `php.ini` file:
```ini
extension=sqlite3
```

### Issue: "Permission denied" on storage/logs
**Solution**: Set proper permissions:
```bash
chmod -R 775 storage bootstrap/cache database
```

## Next Steps

After setup, you can:
1. Customize the admin dashboard
2. Add more features (patients, appointments, etc.)
3. Add more user roles if needed
4. Customize the UI/UX

