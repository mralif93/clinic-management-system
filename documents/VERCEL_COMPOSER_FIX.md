# Fix: "composer: command not found" Error

## 🔴 Problem

Your Vercel deployment fails with:
```
sh: line 1: composer: command not found
Error: Command "composer install && npm install" exited with 127
```

## ✅ Solution

**Composer is NOT available during the Install Command phase.** With `vercel-php` runtime, Composer is only available during the **Build Command** phase.

### Fix: Move Composer to Build Command

## 📋 Correct Vercel Settings

### Install Command (ONLY npm install)
```bash
npm install
```
⚠️ **Do NOT include `composer install` here** - It will fail!

### Build Command (Includes composer install)
```bash
npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## 🔍 Why This Happens

**Vercel Build Process:**
1. **Install Command** runs FIRST (before PHP runtime is set up)
   - ❌ Composer NOT available here
   - ✅ npm IS available here
   
2. **Build Command** runs SECOND (after PHP runtime is set up)
   - ✅ Composer IS available here
   - ✅ PHP IS available here
   - ✅ npm IS available here

**With `vercel-php@0.7.4` runtime:**
- PHP and Composer are installed during the build phase
- They are NOT available during the install phase
- This is why `composer install` must be in Build Command, not Install Command

## 📋 Step-by-Step Fix

### Step 1: Update Install Command

1. Go to **Vercel Dashboard** → **Settings** → **Build and Deployment**
2. Find **Install Command**
3. **Change** to:
   ```bash
   npm install
   ```
4. Make sure **Override** toggle is **ON** (blue)
5. Click **Save**

### Step 2: Verify Build Command

1. Find **Build Command**
2. Make sure it includes `composer install`:
   ```bash
   npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
3. Make sure **Override** toggle is **ON** (blue)
4. Click **Save**

### Step 3: Redeploy

1. Go to **Deployments** tab
2. Click **"Redeploy"** on latest deployment
3. Wait for build to complete

## ✅ Expected Result

After fixing:
- ✅ Install Command runs: `npm install` (succeeds)
- ✅ Build Command runs: `npm run build` → `composer install` → Laravel cache commands (all succeed)
- ✅ Deployment completes successfully

## 🔍 Alternative: If npm run build fails

If `npm run build` fails, use this Build Command instead:
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

This skips Vite build and only runs Composer + Laravel caching.

## 📚 Summary

**Wrong:**
- Install Command: `composer install && npm install` ❌ (Composer not available)

**Correct:**
- Install Command: `npm install` ✅
- Build Command: `npm run build && composer install ...` ✅ (Composer available here)

## Related Issues

- If you see "composer: command not found" → Move `composer install` to Build Command
- If you see "php: command not found" → Make sure you're using `vercel-php` runtime
- If build still fails → Check Build Logs for specific error messages
