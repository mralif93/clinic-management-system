# Fix: File Upload on Vercel

## Issue
File uploads don't work on Vercel because:
- Vercel's serverless environment has a **read-only filesystem**
- The `storage/app/public` directory is not writable
- Files uploaded to local storage won't persist between function invocations

## Solution Applied

### Base64 Storage for Vercel
The logo upload now uses **base64 encoding** and stores the image data directly in the database when running on Vercel/production.

### How It Works

1. **Local Development:**
   - Files are stored in `storage/app/public/logos/`
   - Uses Laravel's standard file storage
   - Files are accessible via `asset('storage/...')`

2. **Vercel/Production:**
   - Files are converted to base64
   - Stored directly in the `settings` table as data URI
   - Format: `data:image/jpeg;base64,/9j/4AAQSkZJRg...`
   - No file system access needed

### Implementation

**Controller (`SettingsController.php`):**
```php
if (env('APP_ENV') === 'production' || env('VERCEL')) {
    // Vercel/serverless - store as base64
    $imageData = file_get_contents($file->getRealPath());
    $base64 = base64_encode($imageData);
    $mimeType = $file->getMimeType();
    $logoData = 'data:' . $mimeType . ';base64,' . $base64;
    Setting::set('clinic_logo', $logoData);
} else {
    // Local - use file storage
    $logoPath = $file->store('logos', 'public');
    Setting::set('clinic_logo', $logoPath);
}
```

**Views:**
All views now check if the logo is base64 or file path:
```php
if ($logoPath && str_starts_with($logoPath, 'data:')) {
    $logoUrl = $logoPath; // Base64 data URI
} elseif ($logoPath) {
    $logoUrl = asset('storage/' . $logoPath); // File path
}
```

## Updated Files

1. ✅ `app/Http/Controllers/Admin/SettingsController.php` - Handles both storage methods
2. ✅ `resources/views/admin/settings/index.blade.php` - Displays both formats
3. ✅ `resources/views/layouts/admin.blade.php` - Logo and favicon support
4. ✅ `resources/views/layouts/public.blade.php` - Favicon support
5. ✅ `resources/views/layouts/doctor.blade.php` - Favicon support
6. ✅ `resources/views/layouts/staff.blade.php` - Favicon support

## Benefits

- ✅ **Works on Vercel** - No file system needed
- ✅ **Works locally** - Uses standard file storage
- ✅ **Automatic detection** - Switches based on environment
- ✅ **No external services** - Uses database storage
- ✅ **Simple implementation** - No additional packages needed

## Limitations

- **File Size:** Base64 increases file size by ~33%
- **Database Size:** Images stored in database (acceptable for logos)
- **Max Size:** 2MB limit (configurable in validation)

## Alternative Solutions (Future)

If you need to handle larger files or many images, consider:

1. **Supabase Storage** - Since you're already using Supabase
2. **AWS S3** - Reliable cloud storage
3. **Cloudinary** - Image hosting and optimization
4. **Vercel Blob** - Vercel's storage solution

## Testing

### On Vercel:
1. Upload a logo in Settings
2. Logo should be stored as base64 in database
3. Logo should display in admin sidebar
4. Favicon should update

### Locally:
1. Upload a logo in Settings
2. Logo should be stored in `storage/app/public/logos/`
3. Logo should display correctly

## Verification

Check the database:
```sql
SELECT value FROM settings WHERE key = 'clinic_logo';
-- Vercel: Should start with "data:image/..."
-- Local: Should be "logos/filename.jpg"
```

## Result

✅ **Logo upload now works on Vercel**
✅ **Automatic environment detection**
✅ **Backward compatible with local development**
✅ **No code changes needed for different environments**

