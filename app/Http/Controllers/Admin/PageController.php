<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display module visibility control
     */
    public function index()
    {
        // Module configurations
        $moduleConfigs = [
            'services' => [
                'name' => 'Services',
                'icon' => 'bx-grid-alt',
                'color' => 'cyan',
                'route' => 'services.index',
                'admin_route' => 'admin.services.index',
            ],
            'packages' => [
                'name' => 'Packages',
                'icon' => 'bx-package',
                'color' => 'purple',
                'route' => 'packages.index',
                'admin_route' => 'admin.packages.index',
            ],
            'team' => [
                'name' => 'Team',
                'icon' => 'bx-group',
                'color' => 'indigo',
                'route' => 'team.index',
                'admin_route' => 'admin.team.index',
            ],
            'about' => [
                'name' => 'About',
                'icon' => 'bx-info-circle',
                'color' => 'blue',
                'route' => 'about',
                'admin_route' => 'admin.pages.about',
            ],
        ];
        
        // Fetch module pages and combine with configs
        $modules = [];
        foreach ($moduleConfigs as $type => $config) {
            $page = Page::where('type', $type)->first();
            $modules[] = [
                'type' => $type,
                'config' => $config,
                'page' => $page,
                'order' => $page ? ($page->order ?? 999) : 999, // Default to 999 if no page or order
            ];
        }
        
        // Sort by order (ascending)
        usort($modules, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        
        return view('admin.pages.index', compact('modules'));
    }

    /**
     * Toggle page published status
     */
    public function toggleStatus($id)
    {
        $page = Page::findOrFail($id);
        
        // Allow toggling for all pages including system pages (services, packages, team)
        if ($page->is_published) {
            $page->unpublish();
            $message = 'Module hidden successfully!';
        } else {
            $page->publish();
            $message = 'Module published successfully!';
        }

        return redirect()->route('admin.pages.index')
            ->with('success', $message);
    }

    /**
     * Update module order
     */
    public function updateOrder(Request $request, $id)
    {
        $validated = $request->validate([
            'order' => 'required|integer|min:0',
        ]);

        $page = Page::findOrFail($id);
        
        $page->update(['order' => $validated['order']]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Module order updated successfully!');
    }
}
