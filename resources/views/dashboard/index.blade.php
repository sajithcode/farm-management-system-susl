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

<!-- Quick Links -->
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
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-outline-primary quick-link-btn w-100 text-start" onclick="alert('Add Batch functionality coming soon!')">
                            <i class="fas fa-users me-3"></i>
                            <div>
                                <strong>Add New Batch</strong>
                                <br><small class="text-muted">Register a new batch of animals</small>
                            </div>
                        </button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-outline-success quick-link-btn w-100 text-start" onclick="alert('Add Animal functionality coming soon!')">
                            <i class="fas fa-paw me-3"></i>
                            <div>
                                <strong>Add Individual Animal</strong>
                                <br><small class="text-muted">Register individual animal</small>
                            </div>
                        </button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('feed.in.index') }}" class="btn btn-outline-warning quick-link-btn w-100 text-start text-decoration-none">
                            <i class="fas fa-seedling me-3"></i>
                            <div>
                                <strong>Feed Management</strong>
                                <br><small class="text-muted">Manage feed stock in/out</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-outline-info quick-link-btn w-100 text-start" onclick="alert('Reports functionality coming soon!')">
                            <i class="fas fa-chart-bar me-3"></i>
                            <div>
                                <strong>View Reports</strong>
                                <br><small class="text-muted">Generate detailed reports</small>
                            </div>
                        </button>
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
