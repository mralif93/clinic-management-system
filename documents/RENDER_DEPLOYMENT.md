# Render Deployment Guide for Laravel

## 🚨 Issue Fixed

The application was failing to deploy on Render because:
- Vite dev server was binding to `localhost:5173` instead of `0.0.0.0`
- Render requires applications to bind to `0.0.0.0` on the `PORT` environment variable
- The start command was using `npm run dev` which is for development, not production

## ✅ Solution Applied

### 1. Created `render.yaml`
This file configures Render to:
- Use PHP environment
- Build assets with `npm run build` (production build)
- Start Laravel with `php artisan serve` binding to `0.0.0.0` on the `PORT` variable

### 2. Updated `vite.config.js`
- Added server configuration to bind to `0.0.0.0` when `PORT` environment variable is set
- This ensures Vite dev server (if used) will be accessible from Render

### 3. Updated `package.json`
- Added `start` script that uses Laravel's built-in server with proper host/port binding

## 📋 Render Dashboard Configuration

### Step 1: Create New Web Service

1. Go to your Render dashboard
2. Click "New +" → "Web Service"
3. Connect your GitHub repository: `mralif93/clinic-management-system`
4. Select branch: `master`

### Step 2: Configure Service Settings

**Name:** `clinic-management-system`

**Environment:** `PHP`

**Region:** Choose closest to your users

**Branch:** `master`

**Root Directory:** (leave empty)

**Build Command:** (Render will use `render.yaml`, but you can override)
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Start Command:** (Render will use `render.yaml`, but you can override)
```bash
php artisan serve --host=0.0.0.0 --port=${PORT}
```

### Step 3: Environment Variables

Add these environment variables in Render dashboard:

#### Application Settings
```
APP_NAME=Clinic Management System
APP_ENV=production
APP_KEY=base64:mePMZOXJToAL6hk94jOxnTEWsyKT5GZYLpVVpQOFf2c=
APP_DEBUG=false
APP_URL=https://clinic-management-system-oyxz.onrender.com
```

**⚠️ Important:** Update `APP_URL` to match your Render URL (check after first deployment)

#### Database Configuration (PostgreSQL/Supabase)
```
DB_CONNECTION=pgsql
DB_HOST=db.pgdhknzfdfvpelqiwgop.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.pgdhknzfdfvpelqiwgop
DB_PASSWORD=cvqvsaUO2owKiNS5
```

#### PostgreSQL Connection URLs
```
POSTGRES_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&supa=base-pooler.x
POSTGRES_URL_NON_POOLING=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:5432/postgres?sslmode=require
POSTGRES_PRISMA_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&pgbouncer=true
```

#### Supabase Configuration
```
SUPABASE_URL=https://pgdhknzfdfvpelqiwgop.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3OTI3NjksImV4cCI6MjA3OTM2ODc2OX0.91F-8BFcAAN2QqxsqIDbsdOAEMJw-3O4MWtVpFWXEAI
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2Mzc5Mjc2OSwiZXhwIjoyMDc5MzY4NzY5fQ.gummqqdKvC8gETG4IJ6092PjofsV9S5tlZU7V2F6hjc
SUPABASE_JWT_SECRET=P0rgOwlLlhU17XjJ4LqRVvoh4YBiQdfEr7SpG/TqxbHgdZ1hAWOWDA8AQUDaAhW3XYXbL8UlLIDebU3RbRkwrQ==
```

#### Cache & Session
```
CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

#### Mail Configuration
```
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=Clinic Management System
```

### Step 4: Deploy

1. Click "Create Web Service"
2. Render will automatically detect `render.yaml` and use those settings
3. Wait for the build to complete
4. Check the deployment logs for any errors

## 🔍 Troubleshooting

### Port Binding Issues

If you still see "Port scan timeout":
1. Verify `startCommand` in `render.yaml` uses `--host=0.0.0.0`
2. Ensure `PORT` environment variable is available (Render sets this automatically)
3. Check that you're not using `npm run dev` in production

### Build Failures

If build fails:
1. Check that `composer.json` has all required dependencies
2. Verify PHP version is 8.2+ (set in Render dashboard)
3. Check build logs for specific error messages

### Database Connection Issues

If database connection fails:
1. Verify all `DB_*` environment variables are set correctly
2. Check that Supabase allows connections from Render's IP addresses
3. Ensure `DB_PORT` matches your Supabase configuration (5432 or 6543)

### Asset Loading Issues

If CSS/JS assets don't load:
1. Verify `npm run build` completed successfully
2. Check that `public/build` directory exists after build
3. Ensure `APP_URL` matches your Render URL exactly

## 📝 Notes

- **Free Tier:** Render free instances spin down after 15 minutes of inactivity, causing ~50 second cold starts
- **Production:** For production, consider upgrading to a paid plan for better performance
- **Environment Variables:** Never commit sensitive values to git. Always use Render's environment variable settings
- **APP_URL:** Update this after first deployment to match your actual Render URL

## 🔄 Differences from Vercel

| Aspect | Vercel | Render |
|--------|--------|--------|
| Runtime | Serverless Functions | Traditional Server |
| Start Command | Not needed (serverless) | `php artisan serve` |
| Port Binding | N/A | Must bind to `0.0.0.0:${PORT}` |
| Build | Static assets only | Full Laravel app |
| Configuration | `vercel.json` | `render.yaml` |

## ✅ Verification Checklist

After deployment, verify:
- [ ] Application loads at Render URL
- [ ] No port binding errors in logs
- [ ] Database connection works
- [ ] CSS/JS assets load correctly
- [ ] Routes are accessible
- [ ] Environment variables are set correctly
- [ ] `APP_URL` matches Render URL
