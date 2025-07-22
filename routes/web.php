<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Feed Management Routes
    Route::prefix('feed')->name('feed.')->group(function () {
        // Feed In (Stock Entry)
        Route::get('/in', [FeedManagementController::class, 'feedInIndex'])->name('in.index');
        Route::post('/in', [FeedManagementController::class, 'feedInStore'])->name('in.store');
        
        // Feed Out (Stock Issue)
        Route::get('/out', [FeedManagementController::class, 'feedOutIndex'])->name('out.index');
        Route::post('/out', [FeedManagementController::class, 'feedOutStore'])->name('out.store');
        
        // Stock Overview
        Route::get('/stock-overview', [FeedManagementController::class, 'stockOverview'])->name('stock.overview');
        
        // Feed Types Management (Admin only)
        Route::get('/types', [FeedManagementController::class, 'feedTypeIndex'])->name('types.index');
        Route::post('/types', [FeedManagementController::class, 'feedTypeStore'])->name('types.store');
    });
});
