# Vercel Deployment Guide

## ⚠️ Important Note

**Vercel does not natively support PHP/Laravel applications.** Vercel is designed for serverless functions and static sites (Node.js, Next.js, etc.).

For Laravel applications, consider these alternatives:
- **Railway** - Easy Laravel deployment
- **Heroku** - Popular platform for Laravel
- **DigitalOcean App Platform** - Managed Laravel hosting
- **Laravel Forge/Vapor** - Official Laravel hosting
- **Traditional VPS** - Full control with services like DigitalOcean, AWS, etc.

## Vercel Configuration

The project is configured to work with Vercel using the `vercel-php` runtime. The configuration is based on the [meeting-room-booking example](https://github.com/NazihaIzzati/meeting-room-booking/blob/main/vercel.json).

### Project Structure

The `vercel.json` file is configured to:
- Use `vercel-php@0.7.4` runtime
- Route all requests through `api/index.php`
- Cache static assets with appropriate headers
- Use `/tmp` directory for Laravel cache files (required for serverless)

### 1. Install Vercel CLI

```bash
npm i -g vercel
```

### 2. Configure Environment Variables

Add all environment variables from `vercel.env.example` to your Vercel project:

**Via Vercel Dashboard:**
1. Go to your project settings
2. Navigate to "Environment Variables"
3. Add each variable from the list below

**Via Vercel CLI:**
```bash
vercel env add APP_NAME
vercel env add APP_ENV
vercel env add APP_KEY
# ... (repeat for each variable)
```

### 3. Required Environment Variables

Copy these to Vercel:

```env
APP_NAME=Clinic Management System
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-project.vercel.app

DB_CONNECTION=pgsql
DB_HOST=db.pgdhknzfdfvpelqiwgop.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.pgdhknzfdfvpelqiwgop
DB_PASSWORD=cvqvsaUO2owKiNS5

POSTGRES_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&supa=base-pooler.x
POSTGRES_URL_NON_POOLING=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:5432/postgres?sslmode=require
POSTGRES_PRISMA_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&pgbouncer=true

SUPABASE_URL=https://pgdhknzfdfvpelqiwgop.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3OTI3NjksImV4cCI6MjA3OTM2ODc2OX0.91F-8BFcAAN2QqxsqIDbsdOAEMJw-3O4MWtVpFWXEAI
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2Mzc5Mjc2OSwiZXhwIjoyMDc5MzY4NzY5fQ.gummqqdKvC8gETG4IJ6092PjofsV9S5tlZU7V2F6hjc
SUPABASE_JWT_SECRET=P0rgOwlLlhU17XjJ4LqRVvoh4YBiQdfEr7SpG/TqxbHgdZ1hAWOWDA8AQUDaAhW3XYXbL8UlLIDebU3RbRkwrQ==

NEXT_PUBLIC_SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3OTI3NjksImV4cCI6MjA3OTM2ODc2OX0.91F-8BFcAAN2QqxsqIDbsdOAEMJw-3O4MWtVpFWXEAI
NEXT_PUBLIC_SUPABASE_URL=https://pgdhknzfdfvpelqiwgop.supabase.co
```

### 4. Generate APP_KEY

Before deploying, generate your application key:

```bash
php artisan key:generate
```

Copy the generated key and add it as `APP_KEY` in Vercel environment variables.

### 5. Important Configuration Notes

The `vercel.json` file includes:
- **Cache paths**: All Laravel cache files are configured to use `/tmp` directory (required for serverless)
- **Cache driver**: Set to `array` (in-memory) for serverless compatibility
- **Log channel**: Set to `stderr` for Vercel logging
- **Session driver**: Set to `cookie` for stateless sessions

### 6. Deploy

```bash
vercel
```

Or connect your GitHub repository to Vercel for automatic deployments.

### 7. Update APP_URL

After deployment, update the `APP_URL` environment variable in Vercel with your actual deployment URL (e.g., `https://your-project.vercel.app`).

## Database Configuration

The project is configured to use PostgreSQL (Supabase) in production. Make sure:

1. Your Supabase database is accessible
2. All migrations are run: `php artisan migrate`
3. Seed data if needed: `php artisan db:seed`

## Recommended Alternative: Railway

For a better Laravel deployment experience, consider Railway:

1. Sign up at [railway.app](https://railway.app)
2. Create a new project
3. Add PostgreSQL database
4. Connect your GitHub repository
5. Railway will automatically detect Laravel and deploy

## Recommended Alternative: Laravel Forge

For production-ready Laravel hosting:

1. Sign up at [forge.laravel.com](https://forge.laravel.com)
2. Connect your server (DigitalOcean, AWS, etc.)
3. Deploy your application
4. Configure PostgreSQL database

