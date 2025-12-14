# Fix: "npm error Missing script: build," - Deployment Error

## 🔴 Problem

Your Vercel deployment is failing with:
```
npm error Missing script: "build,"
Error: Command "npm run build, composer install..."
```

**Root Cause:** The Build Command in Vercel Dashboard has a **COMMA** instead of `&&` to chain commands.

## ✅ Solution

### Step 1: Fix Build Command in Vercel Dashboard

1. Go to **Vercel Dashboard** → Your Project → **Settings** → **General**
2. Scroll to **Build Command** section
3. **Delete the current command** (it probably has a comma)
4. **Paste this exact command** (copy carefully, no commas!):

```bash
npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**⚠️ CRITICAL POINTS:**
- Use `&&` (double ampersand) NOT commas `,`
- No spaces around `&&`
- Make sure the entire command is on one line
- Don't cut off the command - it should end with `php artisan view:cache`

### Step 2: Alternative Build Commands

**If `npm run build` fails, try this version (without Vite build):**
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**If still having issues, use minimal version:**
```bash
composer install --no-dev --optimize-autoloader
```

### Step 3: Verify Install Command

Also check **Install Command** (should be):
```bash
composer install && npm install
```

### Step 4: Redeploy

1. Click **Save** in Settings
2. Go to **Deployments** tab
3. Click **"Redeploy"** on latest deployment
4. Wait for build to complete

## 📋 Command Breakdown

**Full Command:**
```bash
npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**What each part does:**
- `npm run build` - Builds Vite assets (CSS/JS)
- `composer install --no-dev --optimize-autoloader` - Installs PHP dependencies
- `php artisan config:cache` - Caches Laravel config
- `php artisan route:cache` - Caches routes
- `php artisan view:cache` - Caches Blade views

## ❌ Common Mistakes

1. **Using comma instead of &&**
   - ❌ Wrong: `npm run build, composer install...`
   - ✅ Correct: `npm run build && composer install...`

2. **Spaces around &&**
   - ❌ Wrong: `npm run build && composer install`
   - ✅ Correct: `npm run build && composer install` (no extra spaces)

3. **Incomplete command**
   - ❌ Wrong: `php artisan config: cache` (space after colon)
   - ✅ Correct: `php artisan config:cache` (no space)

4. **Cut off command**
   - ❌ Wrong: `php artisan route` (missing `:cache`)
   - ✅ Correct: `php artisan route:cache`

## 🔍 Verify After Fix

After redeploying, check:
1. ✅ Build logs show "Build completed successfully"
2. ✅ No "Missing script" errors
3. ✅ All commands execute in order
4. ✅ Deployment status is "Ready"

## Still Having Issues?

1. **Check Build Logs** - Look for specific error messages
2. **Try Minimal Command** - Start with just `composer install --no-dev --optimize-autoloader`
3. **Check package.json** - Verify `build` script exists (it should: `"build": "vite build"`)
4. **Enable Debug Mode** - Set `APP_DEBUG=true` temporarily to see detailed errors
