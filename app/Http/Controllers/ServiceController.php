<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Page;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        // Check if services module is visible
        if (!Page::isModuleVisible('services')) {
            abort(404);
        }

        $query = Service::active();

        // Filter by type if provided
        if ($request->has('type') && in_array($request->type, ['psychology', 'homeopathy'])) {
            $query->where('type', $request->type);
        }

        $services = $query->orderBy('type')->orderBy('name')->get();
        
        // Group by type
        $groupedServices = $services->groupBy('type');

        return view('services.index', compact('services', 'groupedServices'));
    }

    /**
     * Display the specified service
     */
    public function show($slug)
    {
        // Check if services module is visible
        if (!Page::isModuleVisible('services')) {
            abort(404);
        }

        $service = Service::where('slug', $slug)->firstOrFail();
        
        // Only show active services
        if (!$service->is_active) {
            abort(404);
        }
        
        return view('services.show', compact('service'));
    }
}

