# Vercel Environment Variables

Copy and paste these environment variables into your Vercel project settings.

## How to Add Environment Variables in Vercel

1. Go to your Vercel project dashboard
2. Navigate to **Settings** → **Environment Variables**
3. Add each variable below (or import from `vercel.env.example`)

## Required Environment Variables

### Application Settings
```
APP_NAME=Clinic Management System
APP_ENV=production
APP_KEY=base64:4tKqKXGoOnfJ4pUF6SWtEnz+DPeA1YoK2SpTiwb/k6c=
APP_DEBUG=false
APP_URL=https://clinic-management-system-blue.vercel.app
```

### Database Configuration (PostgreSQL/Supabase)
```
DB_CONNECTION=pgsql
DB_HOST=db.pgdhknzfdfvpelqiwgop.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.pgdhknzfdfvpelqiwgop
DB_PASSWORD=cvqvsaUO2owKiNS5
```

### PostgreSQL Connection URLs
```
POSTGRES_HOST=db.pgdhknzfdfvpelqiwgop.supabase.co
POSTGRES_DATABASE=postgres
POSTGRES_USER=postgres.pgdhknzfdfvpelqiwgop
POSTGRES_PASSWORD=cvqvsaUO2owKiNS5
POSTGRES_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&supa=base-pooler.x
POSTGRES_URL_NON_POOLING=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:5432/postgres?sslmode=require
POSTGRES_PRISMA_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&pgbouncer=true
```

### Supabase Configuration
```
SUPABASE_URL=https://pgdhknzfdfvpelqiwgop.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3OTI3NjksImV4cCI6MjA3OTM2ODc2OX0.91F-8BFcAAN2QqxsqIDbsdOAEMJw-3O4MWtVpFWXEAI
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2Mzc5Mjc2OSwiZXhwIjoyMDc5MzY4NzY5fQ.gummqqdKvC8gETG4IJ6092PjofsV9S5tlZU7V2F6hjc
SUPABASE_JWT_SECRET=P0rgOwlLlhU17XjJ4LqRVvoh4YBiQdfEr7SpG/TqxbHgdZ1hAWOWDA8AQUDaAhW3XYXbL8UlLIDebU3RbRkwrQ==
```

### Next.js Public Variables (if using Next.js frontend)
```
NEXT_PUBLIC_SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBnZGhrbnpmZGZ2cGVscWl3Z29wIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjM3OTI3NjksImV4cCI6MjA3OTM2ODc2OX0.91F-8BFcAAN2QqxsqIDbsdOAEMJw-3O4MWtVpFWXEAI
NEXT_PUBLIC_SUPABASE_URL=https://pgdhknzfdfvpelqiwgop.supabase.co
```

### Cache & Session
```
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## Important Notes

1. **Generate APP_KEY**: Before deploying, run `php artisan key:generate` locally and copy the generated key to `APP_KEY` in Vercel.

2. **Update APP_URL**: Replace `https://your-project.vercel.app` with your actual Vercel deployment URL.

3. **Environment Scope**: You can set variables for:
   - **Production** - Live deployments
   - **Preview** - Pull request previews
   - **Development** - Local development

4. **Security**: Never commit these values to Git. They are already in `.gitignore`.

## Quick Setup Script

You can also use Vercel CLI to add variables:

```bash
# Install Vercel CLI
npm i -g vercel

# Login
vercel login

# Add variables one by one
vercel env add APP_NAME production
vercel env add APP_ENV production
vercel env add DB_CONNECTION production
# ... (repeat for each variable)
```

Or use the Vercel dashboard for easier bulk import.

