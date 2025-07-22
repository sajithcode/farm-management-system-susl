<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Dummy data for now - will be replaced with actual data later
        $stats = [
            'total_batches' => 45,
            'total_animals' => 1250,
            'total_feed_stock' => 2500, // kg
            'deaths_today' => 2,
            'slaughters_today' => 8,
            'total_production' => 15000, // kg
            'total_sales' => 850000, // currency
        ];

        $quickLinks = [
            'Add Batch' => route('dashboard'), // placeholder
            'Add Animal' => route('dashboard'), // placeholder
            'Add Feed' => route('dashboard'), // placeholder
            'View Reports' => route('dashboard'), // placeholder
        ];

        return view('dashboard.index', compact('user', 'stats', 'quickLinks'));
    }
}
