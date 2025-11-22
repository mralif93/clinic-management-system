<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
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
        $service = Service::where('slug', $slug)->firstOrFail();
        return view('services.show', compact('service'));
    }
}

