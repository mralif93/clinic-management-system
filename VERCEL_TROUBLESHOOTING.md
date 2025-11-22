# Vercel 500 Error Troubleshooting Guide

## Your Deployment URL
**https://clinic-management-system-blue.vercel.app**

## Immediate Actions

### 1. Enable Debug Mode (Temporarily)

In Vercel Dashboard → Environment Variables, set:
```
APP_DEBUG=true
```

This will show the actual error message. **Disable after fixing!**

### 2. Check Vercel Function Logs

1. Go to: https://vercel.com/dashboard
2. Select your project: `clinic-management-system`
3. Click **Functions** tab
4. Click on any function execution
5. Check **Logs** section for error details

### 3. Verify Critical Environment Variables

Check these are set in Vercel:

**MUST HAVE:**
- ✅ `APP_KEY` - Generate with: `php artisan key:generate`
- ✅ `APP_URL` - Set to: `https://clinic-management-system-blue.vercel.app`
- ✅ `APP_ENV` - Set to: `production`
- ✅ `DB_CONNECTION` - Set to: `pgsql`
- ✅ `DB_HOST` - Set to: `db.pgdhknzfdfvpelqiwgop.supabase.co`
- ✅ `DB_DATABASE` - Set to: `postgres`
- ✅ `DB_USERNAME` - Set to: `postgres.pgdhknzfdfvpelqiwgop`
- ✅ `DB_PASSWORD` - Set to: `cvqvsaUO2owKiNS5`

## Common 500 Error Causes

### Error: "No application encryption key"
**Fix:**
```bash
# Run locally
php artisan key:generate
# Copy the output (starts with "base64:")
# Add to Vercel as APP_KEY
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Fix:**
- Verify database credentials
- Check if Supabase database is accessible
- Test connection string

### Error: "Class 'X' not found" or "vendor/autoload.php not found"
**Fix:**
- Ensure **Install Command** includes: `composer install`
- Check build logs to verify `composer install` runs
- Verify `vendor/` is not in `.gitignore` for deployment

### Error: "The stream or file could not be opened"
**Fix:**
- Already handled with `/tmp` cache paths
- Verify environment variables for cache paths are set

## Build Command Recommendations

### For First Deployment (Simplified):
```
composer install --no-dev --optimize-autoloader
```

### After It Works (Full):
```
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Step-by-Step Debugging

1. **Set APP_DEBUG=true** in Vercel
2. **Redeploy** the project
3. **Visit** https://clinic-management-system-blue.vercel.app
4. **Read the error message** displayed
5. **Fix the specific issue**
6. **Set APP_DEBUG=false** after fixing

## Check Vercel Build Logs

1. Go to Vercel Dashboard
2. Click on your latest deployment
3. Check **Build Logs** for:
   - ✅ `composer install` completes successfully
   - ✅ No PHP errors
   - ✅ All dependencies installed

## Verify Configuration

### In Vercel Dashboard Settings:

- **Framework Preset:** Other / No Framework
- **Root Directory:** `./`
- **Build Command:** `composer install --no-dev --optimize-autoloader`
- **Output Directory:** **EMPTY** (very important!)
- **Install Command:** `composer install && npm install`

## Quick Test

After setting `APP_DEBUG=true`, redeploy and check:
- What error message appears?
- Check Vercel function logs
- Verify all environment variables are set

## Need More Help?

Check these files:
- `VERCEL_500_FIX.md` - Detailed fix guide
- `VERCEL_ENV_VARIABLES.md` - Complete environment variable list
- `VERCEL_SETUP_GUIDE.md` - Full setup instructions

