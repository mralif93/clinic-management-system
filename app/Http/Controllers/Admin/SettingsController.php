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
    public function index(Request $request)
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        $groupedSettings = $settings->groupBy('group');

        // Prefer explicit query param, then route default, then fallback
        $activeTab = $request->input('tab', $request->route('tab', 'general'));

        return view('admin.settings.index', compact('settings', 'groupedSettings', 'activeTab'));
    }

    /**
     * Dedicated Pages management view (About / Team)
     */
    public function pages()
    {
        return view('admin.pages.index');
    }

    /**
     * Edit About page content
     */
    public function editAbout()
    {
        return view('admin.pages.edit', ['mode' => 'about']);
    }

    /**
     * Edit Team page content
     */
    public function editTeam()
    {
        return view('admin.pages.edit', ['mode' => 'team']);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'nullable|array',
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
        if (!empty($validated['settings'])) {
            foreach ($validated['settings'] as $key => $value) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Remove logo
     */
    public function removeLogo(Request $request)
    {
        try {
            $oldLogo = Setting::get('clinic_logo');

            // Delete file if it's a file path (local development)
            if ($oldLogo && !str_starts_with($oldLogo, 'data:')) {
                if (Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }

            // Clear logo setting
            Setting::set('clinic_logo', null);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logo removed successfully!'
                ]);
            }

            return redirect()->route('admin.settings.index')
                ->with('success', 'Logo removed successfully!');
        } catch (\Exception $e) {
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove logo: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.settings.index')
                ->with('error', 'Failed to remove logo: ' . $e->getMessage());
        }
    }

    /**
     * Update a single setting via AJAX (auto-save)
     */
    public function updateSingle(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string',
                'value' => 'nullable',
            ]);

            Setting::set($validated['key'], $validated['value']);

            return response()->json([
                'success' => true,
                'message' => 'Setting saved successfully',
                'key' => $validated['key'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save setting: ' . $e->getMessage(),
            ], 500);
        }
    }
}
