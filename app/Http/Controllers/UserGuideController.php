<?php

namespace App\Http\Controllers;

class UserGuideController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role ?? 'patient';

        return view('user-guide.index', compact('role'));
    }
}
