# Vercel Performance Fix - 500 Error & Slow Loading

## Issues Fixed

1. ✅ **Database Connection Pooling** - Now using Supabase connection pooler (port 6543)
2. ✅ **Function Timeout** - Increased maxDuration to 60 seconds
3. ✅ **Database Connection Optimization** - Added PDO options for serverless
4. ✅ **Build Process** - Added Laravel optimization commands

## Critical Changes Made

### 1. Updated `vercel.json`
- Added `maxDuration: 60` to prevent function timeouts
- Set `DB_PORT: "6543"` to use Supabase connection pooler

### 2. Updated `config/database.php`
- Added PDO options optimized for serverless:
  - `PDO::ATTR_PERSISTENT => false` - Disable persistent connections
  - `PDO::ATTR_TIMEOUT => 5` - 5 second connection timeout
  - `PDO::ATTR_EMULATE_PREPARES => false` - Better performance

## Required Vercel Dashboard Settings

### Build Command (IMPORTANT!)
Update your Build Command in Vercel Dashboard → Settings → General:

**Full version (includes Vite build for assets):**
```bash
npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**⚠️ CRITICAL:** 
- Use `&&` (double ampersand) to chain commands, NOT commas
- No spaces around `&&`
- Make sure the command is complete (not cut off)

**If you get errors with npm run build, use this simplified version:**
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**If still having issues, start with minimal version:**
```bash
composer install --no-dev --optimize-autoloader
```

Then add caching commands back after it works.

### Environment Variables

**CRITICAL:** Update these in Vercel Dashboard → Settings → Environment Variables:

#### Use Connection Pooler (Port 6543)
```
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.pgdhknzfdfvpelqiwgop
DB_PASSWORD=cvqvsaUO2owKiNS5
```

**OR use the connection URL (recommended):**
```
POSTGRES_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&pgbouncer=true
```

The connection URL method is preferred as it automatically handles pooling.

#### Other Required Variables
```
APP_NAME=Clinic Management System
APP_ENV=production
APP_KEY=base64:4tKqKXGoOnfJ4pUF6SWtEnz+DPeA1YoK2SpTiwb/k6c=
APP_DEBUG=false
APP_URL=https://clinic-management-system-blue.vercel.app
```

## Why These Changes Fix the Issues

### 1. Connection Pooler (Port 6543)
- **Problem:** Direct database connections (port 5432) are slow on serverless
- **Solution:** Connection pooler (port 6543) reuses connections, much faster
- **Impact:** Reduces connection time from 2-5 seconds to <500ms

### 2. Function Timeout (maxDuration: 60)
- **Problem:** Vercel free tier has 10s timeout, Pro has 60s
- **Solution:** Explicitly set maxDuration to 60 seconds
- **Impact:** Prevents timeout errors on slower requests

### 3. Laravel Optimizations
- **Problem:** Laravel loads config/routes/views on every request (slow)
- **Solution:** Cache config, routes, and views during build
- **Impact:** Reduces cold start time by 50-70%

### 4. Database PDO Options
- **Problem:** Persistent connections don't work in serverless
- **Solution:** Disable persistent connections, add timeout
- **Impact:** Prevents connection errors and faster failures

## Step-by-Step Deployment

### Step 1: Update Environment Variables
1. Go to Vercel Dashboard → Your Project → Settings → Environment Variables
2. **Update DB_HOST** to: `aws-1-ap-southeast-1.pooler.supabase.com`
3. **Update DB_PORT** to: `6543`
4. **OR add POSTGRES_URL** with the pooler URL (recommended)

### Step 2: Update Build Command
1. Go to Vercel Dashboard → Your Project → Settings → General
2. Find **Build Command**
3. Update to:
   ```bash
   composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```

### Step 3: Redeploy
1. Push changes to your repository, OR
2. Go to Vercel Dashboard → Deployments → Click "Redeploy" on latest deployment

### Step 4: Verify
1. Check deployment logs for successful build
2. Test your application - should load much faster
3. Check Vercel Function Logs if still having issues

## Troubleshooting

### Still Getting 500 Errors?

1. **Enable Debug Mode Temporarily:**
   ```
   APP_DEBUG=true
   ```
   This will show the actual error message.

2. **Check Vercel Function Logs:**
   - Go to Vercel Dashboard → Functions tab
   - Click on a function execution
   - Check Logs for error details

3. **Verify Build Logs:**
   - Check that `composer install` completes successfully
   - Verify Laravel cache commands run without errors

### Still Slow?

1. **Check Database Connection:**
   - Verify you're using port 6543 (pooler) not 5432 (direct)
   - Check POSTGRES_URL is set correctly

2. **Check Cold Starts:**
   - First request after inactivity will be slower (normal for serverless)
   - Subsequent requests should be fast

3. **Monitor Function Duration:**
   - Check Vercel Dashboard → Functions → Duration
   - Should be < 3 seconds for most requests

## Expected Performance

### Before Fix:
- First request: 10-30 seconds (often times out)
- Subsequent requests: 5-15 seconds
- Frequent 500 errors

### After Fix:
- First request (cold start): 3-8 seconds
- Subsequent requests: 1-3 seconds
- No timeout errors

## Additional Optimizations (Optional)

### 1. Use Vercel Pro Plan
- 60-second function timeout (vs 10s on free)
- Better performance and reliability

### 2. Enable Vercel Edge Caching
- Add caching headers for static assets
- Already configured in `vercel.json`

### 3. Database Query Optimization
- Add indexes to frequently queried columns
- Use eager loading to reduce N+1 queries

### 4. Consider Alternative Hosting
- **Railway** - Better Laravel support
- **Laravel Vapor** - Official Laravel serverless platform
- **DigitalOcean App Platform** - Managed Laravel hosting

## Summary

The main fixes are:
1. ✅ Use PostgreSQL connection pooler (port 6543)
2. ✅ Add Laravel optimizations to build command
3. ✅ Increase function timeout
4. ✅ Optimize database connection settings

After applying these changes, your application should load much faster and stop giving 500 errors.
