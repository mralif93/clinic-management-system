# System Architecture

The selection of technologies and architectural patterns ensures the clinic management system is robust, secure, and scalable.

## Tech Stack

- **Framework**: Laravel 10/11 (PHP)
- **Frontend**: Blade Templating Engine, Vanilla CSS, Tailwind CSS (for some UI components).
- **Icons**: Hugeicons / Boxicons.
- **Database**: PostgreSQL / MySQL (compatible).
- **Environment**: Vercel (for deployment) or local LEMP/WAMP stack.

## Core Principles

### 1. Model-View-Controller (MVC)
The application strictly follows the MVC pattern. 
- **Models**: Located in `app/Models/`, representing data and business logic.
- **Views**: Located in `resources/views/`, handling presentation.
- **Controllers**: Organized by role in `app/Http/Controllers/`.

### 2. Role-Based Access Control (RBAC)
Security is enforced through roles. Middleware is used to restrict access to routes based on the user's role:
- `admin`: Full access to all modules and settings.
- `doctor`: Access to clinical and patient management features.
- `staff`: Access to front-desk and support features.
- `patient`: Access to personal records and appointment booking.

### 3. Middleware
The system uses custom middleware to ensure that authenticated users can only access routes belonging to their assigned role.

## Security

- **Authentication**: Built-in Laravel authentication system.
- **Data Protection**: IC numbers and personal data are handled securely.
- **CSRF Protection**: All form submissions are protected against Cross-Site Request Forgery.

## Deployment

The system is optimized for deployment on Vercel, using `vercel.json` for configuration and handling serverless functions for PHP routing.
