<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the landing page
     */
    public function index()
    {
        // Get featured services (limit to 3 per type for landing page)
        $psychologyServices = Service::active()
            ->byType('psychology')
            ->orderBy('name')
            ->limit(3)
            ->get();

        $homeopathyServices = Service::active()
            ->byType('homeopathy')
            ->orderBy('name')
            ->limit(3)
            ->get();

        // Get total counts for stats
        $totalPsychologyServices = Service::active()->byType('psychology')->count();
        $totalHomeopathyServices = Service::active()->byType('homeopathy')->count();

        return view('home', compact('psychologyServices', 'homeopathyServices', 'totalPsychologyServices', 'totalHomeopathyServices'));
    }
}

