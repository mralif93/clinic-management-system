# Vercel Configuration Guide for Laravel

## Step-by-Step Vercel Dashboard Configuration

### 1. Framework Preset
- **Select:** `Other` or `No Framework`
- Laravel is not a frontend framework, so don't use Vite/Next.js presets
- The `vercel.json` file handles the routing configuration

### 2. Root Directory
- **Value:** `./` (default - keep as is)
- This is correct for Laravel projects

### 3. Build and Output Settings

#### Build Command
```
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Explanation:**
- `composer install --no-dev` - Install production dependencies only
- `--optimize-autoloader` - Optimize Composer autoloader
- `php artisan config:cache` - Cache configuration files
- `php artisan route:cache` - Cache routes
- `php artisan view:cache` - Cache views

#### Output Directory
- **Value:** Leave empty or set to `public`
- Laravel doesn't have a static output directory like frontend frameworks
- The `vercel.json` routes handle this

#### Install Command
```
composer install && npm install
```

**Explanation:**
- `composer install` - Install PHP dependencies
- `npm install` - Install Node.js dependencies (for asset compilation if needed)

### 4. Environment Variables

Add all these environment variables in the Vercel dashboard:

#### Application Settings
```
APP_NAME=Clinic Management System
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-project.vercel.app
```

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
POSTGRES_HOST=db.pgdhknzfdfvpelqiwgop.supabase.co
POSTGRES_DATABASE=postgres
POSTGRES_USER=postgres.pgdhknzfdfvpelqiwgop
POSTGRES_PASSWORD=cvqvsaUO2owKiNS5
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

#### Next.js Public Variables (if using Next.js frontend)
```
NEXT_PUBLIC_SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3OTI3NjksImV4cCI6MjA3OTM2ODc2OX0.91F-8BFcAAN2QqxsqIDbsdOAEMJw-3O4MWtVpFWXEAI
NEXT_PUBLIC_SUPABASE_URL=https://pgdhknzfdfvpelqiwgop.supabase.co
```

#### Laravel Cache & Session (for Vercel serverless)
```
APP_CONFIG_CACHE=/tmp/config.php
APP_EVENTS_CACHE=/tmp/events.php
APP_PACKAGES_CACHE=/tmp/packages.php
APP_ROUTES_CACHE=/tmp/routes.php
APP_SERVICES_CACHE=/tmp/services.php
VIEW_COMPILED_PATH=/tmp
CACHE_DRIVER=array
LOG_CHANNEL=stderr
SESSION_DRIVER=cookie
```

## Quick Setup Checklist

- [ ] Framework Preset: Set to "Other" or "No Framework"
- [ ] Root Directory: `./` (default)
- [ ] Build Command: `composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] Output Directory: Leave empty or `public`
- [ ] Install Command: `composer install && npm install`
- [ ] Generate APP_KEY: Run `php artisan key:generate` locally and copy the key
- [ ] Add all environment variables listed above
- [ ] Update APP_URL after first deployment with your actual Vercel URL

## Important Notes

1. **APP_KEY**: Generate this locally first:
   ```bash
   php artisan key:generate
   ```
   Copy the generated key and paste it in Vercel environment variables.

2. **APP_URL**: After your first deployment, Vercel will provide a URL. Update the `APP_URL` environment variable with that URL.

3. **Database Migrations**: You may need to run migrations manually or add them to the build command:
   ```
   composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```

4. **Import .env**: You can use the "Import .env" button and paste the contents from `vercel.env.example` file, then manually add the missing variables.

## Troubleshooting

- If build fails, check that PHP 8.2+ is available in Vercel
- Ensure all environment variables are set correctly
- Check Vercel logs for specific error messages
- Make sure `vercel.json` is in the root directory

