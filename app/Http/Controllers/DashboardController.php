<?php

namespace App\Http\Controllers;

use App\Support\DashboardMetrics;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_dashboard');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('admin.dashboard', DashboardMetrics::build());
    }
}
