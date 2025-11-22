# Debugging 500 Error - Action Items

## Current Status
Your deployment at **https://clinic-management-system-blue.vercel.app/** is returning a 500 error.

## Changes Made

### 1. Enhanced Error Handling
- Added comprehensive error catching in `api/index.php`
- Errors are logged to stderr (visible in Vercel function logs)
- Error details displayed when `APP_DEBUG=true`

### 2. Enabled Debug Mode
- Set `APP_DEBUG=true` in `vercel.json` (temporarily)
- This will show detailed error messages

## Next Steps to Debug

### Step 1: Check Vercel Function Logs

1. Go to: https://vercel.com/dashboard
2. Select project: `clinic-management-system`
3. Click **Functions** tab
4. Click on a recent function execution
5. Check **Logs** - you should see:
   ```
   === Laravel Error ===
   Message: [actual error message]
   File: [file path]:[line number]
   Trace: [stack trace]
   ```

### Step 2: Common Issues to Check

#### Issue 1: Missing APP_KEY
**Error Message:** "No application encryption key has been specified"

**Fix:**
```bash
# Run locally
php artisan key:generate
# Copy the output (starts with "base64:")
# Add to Vercel Environment Variables as APP_KEY
```

#### Issue 2: Missing Vendor Directory
**Error Message:** "vendor/autoload.php not found"

**Fix:**
- Check Vercel Build Logs
- Ensure **Install Command** includes: `composer install`
- Verify build completes successfully

#### Issue 3: Database Connection
**Error Message:** "SQLSTATE..." or "Connection refused"

**Fix:**
- Verify all database environment variables are set
- Check Supabase database is accessible
- Test connection string

#### Issue 4: Missing Environment Variables
**Error Message:** "Undefined constant" or "env() returned null"

**Fix:**
- Add all required variables from `VERCEL_ENV_VARIABLES.md`
- Ensure `APP_KEY` is set
- Ensure `APP_URL` matches deployment URL

### Step 3: Verify Vercel Dashboard Settings

**Framework Preset:** Other / No Framework
**Root Directory:** `./`
**Build Command:** `composer install --no-dev --optimize-autoloader`
**Output Directory:** **EMPTY** (very important!)
**Install Command:** `composer install && npm install`

### Step 4: After Finding the Error

1. **Fix the specific issue** shown in the error message
2. **Set APP_DEBUG=false** in Vercel environment variables (for security)
3. **Redeploy**

## Quick Checklist

- [ ] Check Vercel Function Logs for error details
- [ ] Verify APP_KEY is set in Vercel
- [ ] Verify all database variables are set
- [ ] Check Build Logs to ensure composer install runs
- [ ] Verify Output Directory is EMPTY in Vercel dashboard
- [ ] Check if vendor/ directory exists after build

## What the Error Handler Does

The updated `api/index.php` will:
1. Catch all PHP errors and exceptions
2. Log errors to stderr (visible in Vercel logs)
3. Display detailed error when `APP_DEBUG=true`
4. Show generic message when `APP_DEBUG=false`

This will help identify the exact cause of the 500 error.

