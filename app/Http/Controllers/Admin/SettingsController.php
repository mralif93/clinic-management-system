<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        $groupedSettings = $settings->groupBy('group');
        
        return view('admin.settings.index', compact('settings', 'groupedSettings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            
            try {
                // For Vercel/serverless: Use base64 encoding and store in database
                // For local development: Use file storage
                $isVercel = env('VERCEL') === '1' || env('VERCEL_ENV') !== null || str_contains(env('APP_URL', ''), 'vercel.app');
                if ($isVercel || env('APP_ENV') === 'production') {
                    // Vercel/serverless environment - store as base64 in database
                    $imageData = file_get_contents($file->getRealPath());
                    $base64 = base64_encode($imageData);
                    $mimeType = $file->getMimeType();
                    $extension = $file->getClientOriginalExtension();
                    $logoData = 'data:' . $mimeType . ';base64,' . $base64;
                    
                    // Delete old logo if exists
                    $oldLogo = Setting::get('clinic_logo');
                    if ($oldLogo && str_starts_with($oldLogo, 'data:')) {
                        // Old logo was base64, just update
                    }
                    
                    // Store base64 data
                    Setting::set('clinic_logo', $logoData);
                } else {
                    // Local development - use file storage
                    $oldLogo = Setting::get('clinic_logo');
                    if ($oldLogo && !str_starts_with($oldLogo, 'data:') && Storage::disk('public')->exists($oldLogo)) {
                        Storage::disk('public')->delete($oldLogo);
                    }
                    
                    $logoPath = $file->store('logos', 'public');
                    Setting::set('clinic_logo', $logoPath);
                }
            } catch (\Exception $e) {
                return redirect()->route('admin.settings.index')
                    ->with('error', 'Failed to upload logo: ' . $e->getMessage());
            }
        }

        // Update other settings
        foreach ($validated['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}

