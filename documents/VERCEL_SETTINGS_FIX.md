# Vercel Settings Fix - Exact Configuration

## 🚨 CRITICAL ISSUES FOUND

1. ❌ **Build Command has COMMA** - Should use `&&`
2. ❌ **Development Command has `db:wipe`** - DANGEROUS! Will delete all data
3. ❌ **Using `config:clear` and `view:clear`** - Should be `config:cache` and `view:cache`
4. ⚠️ **Production Overrides** - Different build command may cause conflicts

## ✅ CORRECT SETTINGS

### Framework Settings

**Framework Preset:** `Vite` ✅ (Keep this)

**Build Command:** (Copy this EXACTLY - no commas!)
```bash
npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Output Directory:** `public` ✅ (Keep this)

**Install Command:** (IMPORTANT - Only npm install!)
```bash
npm install
```
⚠️ **Do NOT include `composer install` here** - Composer is not available during Install Command phase. It will run in Build Command instead.

**Development Command:** (CHANGE THIS IMMEDIATELY!)
```bash
npm run dev
```
⚠️ **DO NOT USE:** `php artisan db:wipe` - This will DELETE your database!

### Root Directory

**Root Directory:** (Leave empty) ✅

**Include files outside the root directory:** Enabled ✅ (Keep this)

**Skip deployments when there are no changes:** Disabled ✅ (Keep this)

## 📋 Step-by-Step Fix

### Step 1: Fix Build Command

1. Go to **Settings** → **Build and Deployment**
2. Find **Build Command**
3. **Delete** the current command: `npm run build, composer install...`
4. **Paste** this exact command:
   ```bash
   npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
5. Make sure **Override** toggle is **ON** (blue)

### Step 2: Fix Install Command

1. Find **Install Command**
2. **Change** to (ONLY npm install, NO composer!):
   ```bash
   npm install
   ```
   ⚠️ **Why?** Composer is NOT available during Install Command phase. It will run in Build Command instead.
3. Make sure **Override** toggle is **ON** (blue)

### Step 3: Fix Development Command (CRITICAL!)

1. Find **Development Command**
2. **DELETE** the current command: `php artisan db:wipe, php artisan migrate:refresh --seed`
3. **Change** to:
   ```bash
   npm run dev
   ```
   OR leave it empty
4. Make sure **Override** toggle is **ON** (blue)

### Step 4: Check Production Overrides

1. Look at the yellow warning banner
2. Click on the production deployment link
3. Verify the Production Overrides match your Project Settings
4. If they differ, update Production Overrides to match

### Step 5: Save and Redeploy

1. Click **Save** button at the bottom of Framework Settings
2. Click **Save** button at the bottom of Root Directory section
3. Go to **Deployments** tab
4. Click **"Redeploy"** on latest deployment

## 🔍 What Each Command Does

### Build Command Breakdown:
- `npm run build` - Builds Vite assets (CSS/JS)
- `composer install --no-dev --optimize-autoloader` - Installs PHP dependencies (production only)
- `php artisan config:cache` - **Creates** config cache (faster loading)
- `php artisan route:cache` - **Creates** route cache (faster routing)
- `php artisan view:cache` - **Creates** view cache (faster rendering)

### Why NOT `config:clear`?
- `config:clear` **removes** cache (slower)
- `config:cache` **creates** cache (faster)
- During build, we want to CREATE cache, not clear it!

### Why NOT `db:wipe`?
- `db:wipe` **DELETES ALL DATA** from database
- This is for development/testing only
- Running this in production = **DATA LOSS** 💥

## ⚠️ Important Notes

1. **Use `&&` not commas** - Commas don't chain commands properly
2. **Use `cache` not `clear`** - We want to create cache during build
3. **Never use `db:wipe` in production** - It will delete all your data
4. **Production Overrides** - Make sure they match Project Settings

## ✅ After Fixing

Your settings should look like:

- ✅ Framework Preset: Vite
- ✅ Build Command: `npm run build && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache`
- ✅ Output Directory: `public`
- ✅ Install Command: `composer install && npm install`
- ✅ Development Command: `npm run dev` (or empty)
- ✅ All Override toggles: ON (blue)

## 🚨 If You Already Ran `db:wipe`

If the `db:wipe` command already ran:
1. **Check your database** - Data may be lost
2. **Restore from backup** if available
3. **Run migrations** manually: `php artisan migrate`
4. **Seed data** if needed: `php artisan db:seed`

## 📚 Related Documentation

- `VERCEL_FIX_CHECKLIST.md` - Quick reference
- `documents/VERCEL_DEPLOYMENT_ERROR_FIX.md` - Build command errors
- `documents/VERCEL_PERFORMANCE_FIX.md` - Performance optimizations
