@extends('layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-chart-bar me-2 text-primary"></i>📊 Reports Dashboard</h1>
    <a href="{{ route('reports.generate') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Generate Report
    </a>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.dashboard') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ $startDate }}" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ $endDate }}" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Apply Filter
                </button>
                <a href="{{ route('reports.dashboard') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-refresh me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <!-- Sales Summary -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient text-white h-100" style="background: linear-gradient(45deg, #28a745, #20c997);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">💰 Sales</h6>
                        <h3 class="mb-0">${{ number_format($salesData['total_revenue'], 2) }}</h3>
                        <small class="opacity-75">{{ $salesData['total_sales'] }} transactions</small>
                    </div>
                    <div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>🥚 {{ $salesData['eggs_sales'] }} eggs | 🥩 {{ $salesData['meat_sales'] }} meat</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Feed Summary -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient text-white h-100" style="background: linear-gradient(45deg, #ffc107, #fd7e14);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1 text-dark">🌾 Feed</h6>
                        <h3 class="mb-0 text-dark">{{ number_format($feedData['total_feed_quantity'], 0) }} kg</h3>
                        <small class="opacity-75 text-dark">{{ $feedData['total_feed_records'] }} records</small>
                    </div>
                    <div>
                        <i class="fas fa-seedling fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-dark">Batch: {{ $feedData['batch_feed_records'] }} | Individual: {{ $feedData['individual_feed_records'] }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Death Summary -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient text-white h-100" style="background: linear-gradient(45deg, #dc3545, #c82333);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">💀 Deaths</h6>
                        <h3 class="mb-0">{{ $deathData['total_deaths'] }}</h3>
                        <small class="opacity-75">{{ $deathData['death_records'] }} records</small>
                    </div>
                    <div>
                        <i class="fas fa-heart-broken fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>Batch: {{ $deathData['batch_deaths'] }} | Individual: {{ $deathData['individual_deaths'] }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Slaughter Summary -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient text-white h-100" style="background: linear-gradient(45deg, #6c757d, #495057);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">🔪 Slaughter</h6>
                        <h3 class="mb-0">{{ $slaughterData['total_slaughters'] }}</h3>
                        <small class="opacity-75">{{ number_format($slaughterData['total_weight'], 1) }} kg</small>
                    </div>
                    <div>
                        <i class="fas fa-cut fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>Batch: {{ $slaughterData['batch_slaughters'] }} | Individual: {{ $slaughterData['individual_slaughters'] }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Production Summary -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card bg-gradient text-white h-100" style="background: linear-gradient(45deg, #17a2b8, #138496);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">🥚 Production</h6>
                        <h3 class="mb-0">{{ number_format($productionData['total_quantity'], 0) }}</h3>
                        <small class="opacity-75">{{ $productionData['total_records'] }} records</small>
                    </div>
                    <div>
                        <i class="fas fa-egg fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>Avg Daily: {{ number_format($productionData['average_daily'], 1) }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Report Generation -->
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Reports</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-2">
                        <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="report_type" value="sales">
                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                            <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                <i class="fas fa-file-pdf me-1"></i>Sales PDF
                            </button>
                        </form>
                    </div>
                    <div class="col-6 mb-2">
                        <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="report_type" value="feed">
                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                            <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                <i class="fas fa-file-pdf me-1"></i>Feed PDF
                            </button>
                        </form>
                    </div>
                    <div class="col-6 mb-2">
                        <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="report_type" value="death">
                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-file-pdf me-1"></i>Death PDF
                            </button>
                        </form>
                    </div>
                    <div class="col-6 mb-2">
                        <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="report_type" value="production">
                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                            <button type="submit" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-file-pdf me-1"></i>Production PDF
                            </button>
                        </form>
                    </div>
                </div>
                <hr>
                <a href="{{ route('reports.generate') }}" class="btn btn-primary w-100">
                    <i class="fas fa-cogs me-2"></i>Custom Report Generator
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Period Summary -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Period Summary ({{ Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }})
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2">
                        <h4 class="text-success">${{ number_format($salesData['total_revenue'], 0) }}</h4>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                    <div class="col-md-2">
                        <h4 class="text-warning">{{ number_format($feedData['total_feed_quantity'], 0) }}</h4>
                        <small class="text-muted">Feed Used (kg)</small>
                    </div>
                    <div class="col-md-2">
                        <h4 class="text-danger">{{ $deathData['total_deaths'] }}</h4>
                        <small class="text-muted">Total Deaths</small>
                    </div>
                    <div class="col-md-2">
                        <h4 class="text-secondary">{{ $slaughterData['total_slaughters'] }}</h4>
                        <small class="text-muted">Slaughtered</small>
                    </div>
                    <div class="col-md-2">
                        <h4 class="text-info">{{ number_format($productionData['total_quantity'], 0) }}</h4>
                        <small class="text-muted">Production</small>
                    </div>
                    <div class="col-md-2">
                        <h4 class="text-primary">{{ number_format($salesData['average_sale_value'], 0) }}</h4>
                        <small class="text-muted">Avg Sale Value</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
