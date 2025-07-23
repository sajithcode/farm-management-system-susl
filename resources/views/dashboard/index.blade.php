@extends('layouts.app')

@section('title', 'Dashboard - Farm Management System')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </h1>
        <p class="text-muted mb-0">Welcome back, {{ $user->name }}!</p>
    </div>
    <div class="col-md-4 text-md-end">
        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'vc' ? 'warning' : 'info') }} p-2">
            <i class="fas fa-user-tag me-1"></i>{{ ucfirst(str_replace('_', ' ', $user->role)) }}
        </span>
        @if($user->location)
        <span class="badge bg-secondary p-2 ms-2">
            <i class="fas fa-map-marker-alt me-1"></i>{{ $user->location }}
        </span>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Total Batches</h5>
                    <h2 class="mb-0">{{ number_format($stats['total_batches']) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Total Animals</h5>
                    <h2 class="mb-0">{{ number_format($stats['total_animals']) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-paw fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-warning text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Feed Stock</h5>
                    <h2 class="mb-0">{{ number_format($stats['total_feed_stock']) }} kg</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-seedling fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-info text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Total Production</h5>
                    <h2 class="mb-0">{{ number_format($stats['total_production']) }} kg</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-weight fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Individual Animals Stats -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-6 mb-3">
        <div class="card stats-card bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Individual Animals</h5>
                    <h2 class="mb-0">{{ number_format($stats['total_individual_animals']) }}</h2>
                    <small class="opacity-75">Total registered</small>
                </div>
                <div class="ms-3">
                    <i class="fas fa-paw fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6 mb-3">
        <div class="card stats-card bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Alive Animals</h5>
                    <h2 class="mb-0">{{ number_format($stats['alive_individual_animals']) }}</h2>
                    <small class="opacity-75">Currently alive</small>
                </div>
                <div class="ms-3">
                    <i class="fas fa-heartbeat fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Activity & Sales -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-danger text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Deaths Today</h5>
                    <h2 class="mb-0">{{ number_format($stats['deaths_today']) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-heart-broken fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card bg-dark text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Slaughters Today</h5>
                    <h2 class="mb-0">{{ number_format($stats['slaughters_today']) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-cut fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card stats-card bg-gradient h-100" style="background: linear-gradient(45deg, #28a745, #20c997);">
            <div class="card-body d-flex align-items-center text-white">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Total Sales</h5>
                    <h2 class="mb-0">${{ number_format($stats['total_sales']) }}</h2>
                    <small class="opacity-75">All time revenue</small>
                </div>
                <div class="ms-3">
                    <i class="fas fa-dollar-sign fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links & Recent Batches -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Animal Management Quick Actions -->
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('batches.create') }}" class="btn btn-outline-primary quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-plus-circle me-3 text-success"></i>
                            <div>
                                <strong>Add New Batch</strong>
                                <br><small class="text-muted">Register a new batch of animals</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('batches.index') }}" class="btn btn-outline-secondary quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-users me-3 text-primary"></i>
                            <div>
                                <strong>View All Batches</strong>
                                <br><small class="text-muted">Manage existing batches</small>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Batch Operations Quick Actions -->
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-success quick-link-btn flex-fill text-start" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-seedling me-2"></i>
                                <div>
                                    <strong>Feed Batch</strong>
                                    <br><small class="text-muted">Record feeding for batches</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-outline-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(isset($recentBatches) && $recentBatches->count() > 0)
                                    @foreach($recentBatches->take(5) as $batch)
                                    <li><a class="dropdown-item" href="{{ route('batches.feed', $batch) }}">
                                        <i class="fas fa-seedling me-2"></i>{{ $batch->batch_id }}
                                        <small class="text-muted d-block">{{ $batch->current_count }} animals</small>
                                    </a></li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">No batches available</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('batches.index') }}">View All Batches</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-danger quick-link-btn flex-fill text-start" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-heart-broken me-2"></i>
                                <div>
                                    <strong>Record Deaths</strong>
                                    <br><small class="text-muted">Track mortality records</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-outline-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(isset($recentBatches) && $recentBatches->count() > 0)
                                    @foreach($recentBatches->take(5) as $batch)
                                    <li><a class="dropdown-item" href="{{ route('batches.death', $batch) }}">
                                        <i class="fas fa-heart-broken me-2"></i>{{ $batch->batch_id }}
                                        <small class="text-muted d-block">{{ $batch->current_count }} animals</small>
                                    </a></li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">No batches available</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('batches.index') }}">View All Batches</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-dark quick-link-btn flex-fill text-start" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cut me-2"></i>
                                <div>
                                    <strong>Record Slaughter</strong>
                                    <br><small class="text-muted">Track harvesting records</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-outline-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(isset($recentBatches) && $recentBatches->count() > 0)
                                    @foreach($recentBatches->take(5) as $batch)
                                    <li><a class="dropdown-item" href="{{ route('batches.slaughter', $batch) }}">
                                        <i class="fas fa-cut me-2"></i>{{ $batch->batch_id }}
                                        <small class="text-muted d-block">{{ $batch->current_count }} animals</small>
                                    </a></li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">No batches available</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('batches.index') }}">View All Batches</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Individual Animal Management Quick Actions -->
                    <div class="col-12 mb-3">
                        <hr class="my-3">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-paw me-2"></i>Individual Animal Management
                        </h6>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('individual-animals.create') }}" class="btn btn-outline-primary quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-plus-circle me-3 text-success"></i>
                            <div>
                                <strong>Add New Animal</strong>
                                <br><small class="text-muted">Register individual animal</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('individual-animals.index') }}" class="btn btn-outline-secondary quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-paw me-3 text-primary"></i>
                            <div>
                                <strong>View All Animals</strong>
                                <br><small class="text-muted">Manage individual animals</small>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Individual Animal Operations Quick Actions -->
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-success quick-link-btn flex-fill text-start" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-seedling me-2"></i>
                                <div>
                                    <strong>Feed Animal</strong>
                                    <br><small class="text-muted">Record individual feeding</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-outline-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(isset($recentIndividualAnimals) && $recentIndividualAnimals->count() > 0)
                                    @foreach($recentIndividualAnimals->take(5) as $animal)
                                    <li><a class="dropdown-item" href="{{ route('individual-animals.feed', $animal) }}">
                                        <i class="fas fa-seedling me-2"></i>{{ $animal->animal_id }}
                                        <small class="text-muted d-block">{{ ucfirst($animal->animal_type) }} - {{ $animal->age_display }}</small>
                                    </a></li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">No animals available</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('individual-animals.index') }}">View All Animals</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-danger quick-link-btn flex-fill text-start" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-heart-broken me-2"></i>
                                <div>
                                    <strong>Record Death</strong>
                                    <br><small class="text-muted">Track individual deaths</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-outline-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(isset($recentIndividualAnimals) && $recentIndividualAnimals->count() > 0)
                                    @foreach($recentIndividualAnimals->take(5) as $animal)
                                    <li><a class="dropdown-item" href="{{ route('individual-animals.death', $animal) }}">
                                        <i class="fas fa-heart-broken me-2"></i>{{ $animal->animal_id }}
                                        <small class="text-muted d-block">{{ ucfirst($animal->animal_type) }} - {{ $animal->age_display }}</small>
                                    </a></li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">No animals available</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('individual-animals.index') }}">View All Animals</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-dark quick-link-btn flex-fill text-start" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cut me-2"></i>
                                <div>
                                    <strong>Record Slaughter</strong>
                                    <br><small class="text-muted">Track individual slaughter</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-outline-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(isset($recentIndividualAnimals) && $recentIndividualAnimals->count() > 0)
                                    @foreach($recentIndividualAnimals->take(5) as $animal)
                                    <li><a class="dropdown-item" href="{{ route('individual-animals.slaughter', $animal) }}">
                                        <i class="fas fa-cut me-2"></i>{{ $animal->animal_id }}
                                        <small class="text-muted d-block">{{ ucfirst($animal->animal_type) }} - {{ $animal->age_display }}</small>
                                    </a></li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">No animals available</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('individual-animals.index') }}">View All Animals</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <hr class="my-3">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-seedling me-2"></i>Feed Management
                        </h6>
                    </div>
                    
                    <!-- Feed Management Quick Actions -->
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('feed.in.index') }}" class="btn btn-outline-warning quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-plus-square me-3"></i>
                            <div>
                                <strong>Feed Stock In</strong>
                                <br><small class="text-muted">Add new feed inventory</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('feed.out.index') }}" class="btn btn-outline-info quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-minus-square me-3"></i>
                            <div>
                                <strong>Feed Stock Out</strong>
                                <br><small class="text-muted">Issue feed from inventory</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('feed.stock.overview') }}" class="btn btn-outline-secondary quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-chart-bar me-3"></i>
                            <div>
                                <strong>Stock Overview</strong>
                                <br><small class="text-muted">View current stock levels</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Quick Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Feed Usage Today</span>
                        <span class="fw-bold">245 kg</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Health Check Status</span>
                        <span class="fw-bold text-success">96%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 96%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Weekly Growth Rate</span>
                        <span class="fw-bold text-info">+12%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 78%"></div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Data updated: {{ date('M d, Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Batches Section -->
@if(isset($recentBatches) && $recentBatches->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Recent Batches
                </h5>
                <a href="{{ route('batches.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Batch ID</th>
                                <th>Animal Type</th>
                                <th>Current Count</th>
                                <th>Age</th>
                                <th>Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBatches->take(5) as $batch)
                            <tr>
                                <td>
                                    <a href="{{ route('batches.show', $batch) }}" class="text-decoration-none">
                                        <strong class="text-primary">{{ $batch->batch_id }}</strong>
                                    </a>
                                    @if($batch->name)
                                        <br><small class="text-muted">{{ $batch->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($batch->animal_type) }}</span>
                                </td>
                                <td>
                                    <strong class="{{ $batch->current_count > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($batch->current_count) }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $batch->age_display }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('batches.feed', $batch) }}" 
                                           class="btn btn-sm btn-outline-success" 
                                           title="Feed Batch">
                                            <i class="fas fa-seedling"></i>
                                        </a>
                                        <a href="{{ route('batches.death', $batch) }}" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Record Death">
                                            <i class="fas fa-heart-broken"></i>
                                        </a>
                                        <a href="{{ route('batches.slaughter', $batch) }}" 
                                           class="btn btn-sm btn-outline-dark" 
                                           title="Record Slaughter">
                                            <i class="fas fa-cut"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Individual Animals Section -->
@if(isset($recentIndividualAnimals) && $recentIndividualAnimals->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-paw me-2"></i>Recent Individual Animals
                </h5>
                <a href="{{ route('individual-animals.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Animal ID</th>
                                <th>Type</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th>Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentIndividualAnimals->take(5) as $animal)
                            <tr>
                                <td>
                                    <a href="{{ route('individual-animals.show', $animal) }}" class="text-decoration-none">
                                        <strong class="text-primary">{{ $animal->animal_id }}</strong>
                                    </a>
                                    @if($animal->gender !== 'unknown')
                                        <br><small class="text-muted">
                                            <i class="fas fa-{{ $animal->gender === 'male' ? 'mars' : 'venus' }} me-1"></i>
                                            {{ ucfirst($animal->gender) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($animal->animal_type) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $animal->age_display }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $animal->status_badge }}">
                                        {{ ucfirst($animal->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($animal->status === 'alive')
                                        <a href="{{ route('individual-animals.feed', $animal) }}" 
                                           class="btn btn-sm btn-outline-success" 
                                           title="Feed Animal">
                                            <i class="fas fa-seedling"></i>
                                        </a>
                                        <a href="{{ route('individual-animals.death', $animal) }}" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Record Death">
                                            <i class="fas fa-heart-broken"></i>
                                        </a>
                                        <a href="{{ route('individual-animals.slaughter', $animal) }}" 
                                           class="btn btn-sm btn-outline-dark" 
                                           title="Record Slaughter">
                                            <i class="fas fa-cut"></i>
                                        </a>
                                        @else
                                        <span class="btn btn-sm btn-outline-secondary disabled">
                                            <i class="fas fa-ban"></i> {{ ucfirst($animal->status) }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Role-specific content -->
@if($user->role === 'admin')
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-crown me-2"></i>
            <strong>Admin Access:</strong> You have full administrative privileges. You can manage users, configure system settings, and access all features.
        </div>
    </div>
</div>
@elseif($user->role === 'vc')
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="fas fa-stethoscope me-2"></i>
            <strong>Veterinary Care Access:</strong> You have access to health monitoring, medical records, and veterinary management features.
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="alert alert-primary">
            <i class="fas fa-clipboard-list me-2"></i>
            <strong>Data Collector Access:</strong> You can input and manage farm data, including animal records, feeding schedules, and daily observations.
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    // Add some interactivity to the stats cards
    document.addEventListener('DOMContentLoaded', function() {
        const statsCards = document.querySelectorAll('.stats-card');
        
        statsCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
    });
</script>
@endsection
