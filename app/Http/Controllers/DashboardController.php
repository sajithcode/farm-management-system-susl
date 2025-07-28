<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Batch;
use App\Models\IndividualAnimal;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get recent batches with current count > 0 for quick access (with error handling)
        try {
            $recentBatches = Batch::where('current_count', '>', 0)
                                 ->orderBy('created_at', 'desc')
                                 ->take(10)
                                 ->get();
        } catch (\Exception $e) {
            $recentBatches = collect([]); // Empty collection if table doesn't exist
        }
        
        // Get recent alive individual animals for quick access (with error handling)
        try {
            $recentIndividualAnimals = IndividualAnimal::where('status', 'alive')
                                                      ->orderBy('created_at', 'desc')
                                                      ->take(10)
                                                      ->get();
        } catch (\Exception $e) {
            $recentIndividualAnimals = collect([]); // Empty collection if table doesn't exist
        }
        
        // Calculate actual stats from database with error handling
        try {
            $totalBatches = Batch::count();
            $totalAnimals = Batch::sum('current_count');
        } catch (\Exception $e) {
            $totalBatches = 0;
            $totalAnimals = 0;
        }
        
        // Calculate individual animals stats with error handling
        try {
            $totalIndividualAnimals = IndividualAnimal::count();
            $aliveIndividualAnimals = IndividualAnimal::where('status', 'alive')->count();
        } catch (\Exception $e) {
            $totalIndividualAnimals = 0;
            $aliveIndividualAnimals = 0;
        }
        
        // Calculate feed stock with error handling
        try {
            $feedIn = \DB::table('feed_ins')->sum('quantity');
            $feedOut = \DB::table('feed_outs')->sum('quantity');
            $totalFeedStock = $feedIn - $feedOut;
        } catch (\Exception $e) {
            $totalFeedStock = 0;
        }
        
        // Get today's activity with error handling
        try {
            $deathsToday = \DB::table('batch_deaths')
                             ->whereDate('death_date', today())
                             ->sum('count');
        } catch (\Exception $e) {
            $deathsToday = 0;
        }
        
        try {
            $slaughtersToday = \DB::table('batch_slaughters')
                                 ->whereDate('slaughter_date', today())
                                 ->sum('count');
        } catch (\Exception $e) {
            $slaughtersToday = 0;
        }
        
        // Calculate total production (total weight from slaughters) with error handling
        try {
            $totalProduction = \DB::table('batch_slaughters')->sum('total_weight');
        } catch (\Exception $e) {
            $totalProduction = 0;
        }
        
        // Calculate total sales (revenue from sales) with error handling
        try {
            $totalSalesRevenue = Sale::sum('price');
            $salesThisMonth = Sale::whereMonth('date', now()->month)
                                  ->whereYear('date', now()->year)
                                  ->sum('price');
            $totalSalesCount = Sale::count();
        } catch (\Exception $e) {
            $totalSalesRevenue = 0;
            $salesThisMonth = 0;
            $totalSalesCount = 0;
        }
        
        $stats = [
            'total_batches' => $totalBatches,
            'total_animals' => $totalAnimals,
            'total_individual_animals' => $totalIndividualAnimals,
            'alive_individual_animals' => $aliveIndividualAnimals,
            'total_feed_stock' => max(0, $totalFeedStock), // Ensure non-negative
            'deaths_today' => $deathsToday,
            'slaughters_today' => $slaughtersToday,
            'total_production' => $totalProduction,
            'total_sales_revenue' => $totalSalesRevenue,
            'sales_this_month' => $salesThisMonth,
            'total_sales_count' => $totalSalesCount,
        ];

        return view('dashboard.index', compact('user', 'stats', 'recentBatches', 'recentIndividualAnimals'));
    }
}
