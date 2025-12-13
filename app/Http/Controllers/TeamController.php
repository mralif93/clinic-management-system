<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\Page;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of team members
     */
    public function index()
    {
        // Check if team module is visible
        if (!Page::isModuleVisible('team')) {
            abort(404);
        }

        $teamMembers = TeamMember::active()
            ->ordered()
            ->get();

        return view('team.index', compact('teamMembers'));
    }
}
