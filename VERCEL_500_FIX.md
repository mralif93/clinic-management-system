# Fixing 500 Error on Vercel

## Common Causes of 500 Errors

### 1. Missing APP_KEY
**Most Common Issue**
- Generate locally: `php artisan key:generate`
- Copy the generated key
- Add to Vercel environment variables as `APP_KEY`

### 2. Missing Vendor Directory
- Vercel needs to run `composer install` during build
- Check your **Install Command** in Vercel dashboard:
  ```
  composer install && npm install
  ```

### 3. Database Connection Issues
- Verify all database environment variables are set
- Check if Supabase database is accessible
- Test connection locally first

### 4. Missing Environment Variables
- Ensure ALL required variables are set in Vercel
- Check `VERCEL_ENV_VARIABLES.md` for complete list

### 5. Storage Permissions
- Laravel needs writable storage directories
- Vercel serverless uses `/tmp` for cache (already configured)

## Debugging Steps

### Step 1: Enable Debug Mode Temporarily

In Vercel environment variables, set:
```
APP_DEBUG=true
```

This will show detailed error messages. **Remove after fixing!**

### Step 2: Check Vercel Function Logs

1. Go to Vercel Dashboard
2. Click on your project
3. Go to **Functions** tab
4. Click on a function execution
5. Check **Logs** for error messages

### Step 3: Verify Build Process

Check that:
- ✅ `composer install` runs successfully
- ✅ `vendor/` directory is created
- ✅ No build errors in Vercel logs

### Step 4: Test Database Connection

Add this to your build command to test:
```
composer install --no-dev --optimize-autoloader && php artisan migrate --force --pretend
```

## Quick Fix Checklist

- [ ] **APP_KEY** is set in Vercel (generate with `php artisan key:generate`)
- [ ] **APP_URL** is set to: `https://clinic-management-system-blue.vercel.app`
- [ ] **APP_DEBUG** is set to `false` (or `true` for debugging)
- [ ] All **database variables** are set correctly
- [ ] **Install Command** includes `composer install`
- [ ] **Build Command** runs successfully
- [ ] Check **Vercel Function Logs** for specific errors

## Updated Configuration

The `api/index.php` file has been updated to:
- Show better error messages when APP_DEBUG=true
- Check if vendor directory exists
- Log errors to stderr (visible in Vercel logs)
- Handle exceptions gracefully

## Next Steps

1. **Set APP_DEBUG=true** temporarily in Vercel
2. **Redeploy** and check the error message
3. **Fix the specific issue** shown in the error
4. **Set APP_DEBUG=false** after fixing

## Common Error Messages

### "No application encryption key has been specified"
→ Set `APP_KEY` in Vercel environment variables

### "SQLSTATE[HY000] [2002] Connection refused"
→ Database connection issue - check DB_HOST, DB_PORT, credentials

### "Class 'X' not found"
→ Missing vendor directory - ensure `composer install` runs

### "The stream or file could not be opened"
→ Storage permissions - already handled with `/tmp` paths

