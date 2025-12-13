<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Page;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index()
    {
        // Check if packages module is visible
        if (!Page::isModuleVisible('packages')) {
            abort(404);
        }

        $packages = Package::active()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('packages.index', compact('packages'));
    }

    /**
     * Display the specified package
     */
    public function show($slug)
    {
        // Check if packages module is visible
        if (!Page::isModuleVisible('packages')) {
            abort(404);
        }

        $package = Package::where('slug', $slug)->firstOrFail();
        
        // Only show active packages
        if (!$package->is_active) {
            abort(404);
        }
        
        return view('packages.show', compact('package'));
    }
}
