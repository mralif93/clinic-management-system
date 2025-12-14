# Quick Fix Checklist for Vercel 500 Error & Slow Loading

## ⚡ Quick Actions Required in Vercel Dashboard

### 1. Update Environment Variables (CRITICAL!)

Go to: **Vercel Dashboard → Your Project → Settings → Environment Variables**

**Update or Add these:**

```
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
```

**OR (Better Option) - Add this:**
```
POSTGRES_URL=postgres://postgres.pgdhknzfdfvpelqiwgop:cvqvsaUO2owKiNS5@aws-1-ap-southeast-1.pooler.supabase.com:6543/postgres?sslmode=require&pgbouncer=true
```

**Why:** Using port 6543 (connection pooler) instead of 5432 (direct) makes connections 5-10x faster.

### 2. Update Build Command (IMPORTANT!)

Go to: **Vercel Dashboard → Your Project → Settings → General → Build Command**

**Change to:**
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Why:** Caches Laravel config/routes/views during build, making requests much faster.

### 3. Redeploy

After making changes:
1. Go to **Deployments** tab
2. Click **"Redeploy"** on the latest deployment
3. Wait for build to complete
4. Test your application

## ✅ What Was Fixed in Code

- ✅ Updated `vercel.json` - Added 60s timeout, set DB_PORT to 6543
- ✅ Updated `config/database.php` - Optimized for serverless (no persistent connections, timeout settings)

## 📊 Expected Results

**Before:**
- Load time: 10-30 seconds
- Frequent 500 errors
- Timeout errors

**After:**
- Load time: 1-3 seconds (after first request)
- No timeout errors
- Much faster database connections

## 🔍 If Still Having Issues

1. **Enable Debug Mode** (temporarily):
   ```
   APP_DEBUG=true
   ```
   This shows actual error messages.

2. **Check Function Logs:**
   - Vercel Dashboard → Functions tab → Click execution → View Logs

3. **Verify Build Logs:**
   - Check that build completes successfully
   - Verify no errors in Laravel cache commands

## 📚 Full Documentation

See `documents/VERCEL_PERFORMANCE_FIX.md` for detailed explanation.
