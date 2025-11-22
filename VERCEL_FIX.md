# Fix for Vercel Download Issue

## Problem
When accessing the live project on Vercel, files are being downloaded instead of being executed/displayed.

## Solution Applied

### 1. Updated `vercel.json`
Changed from `functions` to `builds` configuration:
- **Before:** Used `functions` with `vercel-php@0.7.4` runtime
- **After:** Uses `builds` with `@vercel/php` builder

This ensures Vercel properly recognizes and processes PHP files.

### 2. Fixed `api/index.php`
Updated the Kernel import to use the full namespace path:
- Changed from `use Illuminate\Contracts\Http\Kernel;` and `$app->make(Kernel::class)`
- To: `$app->make(Illuminate\Contracts\Http\Kernel::class)`

This ensures proper class resolution in the serverless environment.

## Additional Checks

### In Vercel Dashboard:

1. **Framework Preset**
   - Must be set to: **"Other"** or **"No Framework"**
   - NOT "Vite" or any frontend framework

2. **Build Command**
   ```
   composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```

3. **Output Directory**
   - Leave **EMPTY** or set to: `public`
   - Do NOT set to `dist` or any other directory

4. **Install Command**
   ```
   composer install && npm install
   ```

5. **Environment Variables**
   - Ensure `APP_KEY` is set (generate with `php artisan key:generate`)
   - Ensure `APP_URL` matches your Vercel deployment URL
   - All database variables are configured

## Testing

After redeploying:

1. Check that the URL loads the Laravel application (not downloads)
2. Verify routes are working (e.g., `/login`, `/register`)
3. Check browser console for any errors
4. Review Vercel function logs for PHP errors

## If Still Not Working

1. **Clear Vercel cache:**
   - Go to Vercel dashboard → Settings → Clear Build Cache
   - Redeploy

2. **Check PHP version:**
   - Ensure Vercel is using PHP 8.2+
   - This is usually automatic with `@vercel/php`

3. **Verify file structure:**
   - Ensure `api/index.php` exists in the root
   - Ensure `vendor/` directory is included (not in .gitignore for deployment)

4. **Check Content-Type headers:**
   - The `@vercel/php` builder should automatically set correct headers
   - If not, you may need to add custom headers in `vercel.json`

## Alternative: Use Railway or Heroku

If Vercel continues to have issues, consider:
- **Railway** - Better Laravel support
- **Heroku** - Proven Laravel hosting
- **Laravel Forge/Vapor** - Official Laravel hosting

