# Vercel File Upload Solution

## Problem
Vercel's serverless environment has a **read-only filesystem**. Files cannot be written to `storage/app/public/` on Vercel.

## Solution
Logo uploads now use **base64 encoding** and store images directly in the database when running on Vercel.

## How It Works

### Automatic Environment Detection
The system automatically detects if it's running on Vercel:
- Checks `VERCEL` environment variable
- Checks `VERCEL_ENV` environment variable  
- Checks if `APP_URL` contains `vercel.app`

### Storage Methods

**Vercel/Production:**
- Image is converted to base64
- Stored as data URI in database: `data:image/jpeg;base64,/9j/4AAQ...`
- No file system access needed
- Works in serverless environment

**Local Development:**
- Files stored in `storage/app/public/logos/`
- Standard Laravel file storage
- Accessible via `asset('storage/...')`

## Testing on Vercel

1. **Upload Logo:**
   - Go to Admin → Settings
   - Upload a logo image
   - Click "Save Settings"

2. **Verify Upload:**
   - Logo should appear in admin sidebar
   - Favicon should update
   - Check database: `SELECT value FROM settings WHERE key = 'clinic_logo';`
   - Value should start with `data:image/...`

3. **If Upload Fails:**
   - Check Vercel function logs for errors
   - Verify file size is under 2MB
   - Check image format (JPEG, PNG, SVG, WEBP)

## Environment Variables

Make sure these are set in Vercel:
- `APP_ENV=production`
- `VERCEL=1` (automatically set by Vercel)
- `APP_URL=https://your-project.vercel.app`

## File Size Considerations

- **Base64 increases size by ~33%**
- **2MB limit** is enforced (configurable)
- For logos, this is usually fine
- Database can handle base64 strings up to several MB

## Future Improvements

If you need to handle larger files or many images:

1. **Supabase Storage** (Recommended)
   - You're already using Supabase
   - Free tier: 1GB storage
   - Easy integration

2. **AWS S3**
   - Reliable cloud storage
   - Pay per use

3. **Cloudinary**
   - Image optimization
   - CDN included

4. **Vercel Blob**
   - Vercel's storage solution
   - Native integration

## Current Status

✅ **Logo upload works on Vercel**
✅ **Automatic environment detection**
✅ **Backward compatible with local**
✅ **No external services required**

## Troubleshooting

**Upload fails silently:**
- Check Vercel function logs
- Verify file validation passes
- Check database connection

**Logo doesn't display:**
- Verify logo is saved in database
- Check if value starts with `data:image/`
- Clear browser cache

**File too large:**
- Reduce image size before upload
- Use image compression
- Consider using Supabase Storage for larger files

