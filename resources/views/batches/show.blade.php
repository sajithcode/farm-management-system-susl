@extends('layouts.app')

@section('title', 'Batch Details - ' . $batch->batch_id)

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2 text-primary"></i>{{ $batch->batch_id }}
            @if($batch->name)
                <small class="text-muted">- {{ $batch->name }}</small>
            @endif
        </h1>
        <p class="text-muted mb-0">
            <i class="fas fa-paw me-1"></i>{{ ucfirst($batch->animal_type) }} Batch
            <span class="mx-2">•</span>
            <i class="fas fa-calendar me-1"></i>Started {{ $batch->start_date->format('M d, Y') }}
            <span class="mx-2">•</span>
            <span class="badge bg-secondary">{{ $batch->age_display }}</span>
        </p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="btn-group" role="group">
            <a href="{{ route('batches.feed', $batch) }}" class="btn btn-success">
                <i class="fas fa-seedling me-2"></i>Feed
            </a>
            <a href="{{ route('batches.death', $batch) }}" class="btn btn-danger">
                <i class="fas fa-heart-broken me-2"></i>Death
            </a>
            <a href="{{ route('batches.slaughter', $batch) }}" class="btn btn-dark">
                <i class="fas fa-cut me-2"></i>Slaughter
            </a>
        </div>
        <a href="{{ route('batches.index') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<!-- Batch Overview Stats -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-sort-numeric-up fa-2x mb-2"></i>
                <h4>{{ number_format($batch->initial_count) }}</h4>
                <small>Initial Count</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-paw fa-2x mb-2"></i>
                <h4>{{ number_format($batch->current_count) }}</h4>
                <small>Current Count</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-heart-broken fa-2x mb-2"></i>
                <h4>{{ number_format($batch->deathRecords->sum('count')) }}</h4>
                <small>Total Deaths</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <i class="fas fa-cut fa-2x mb-2"></i>
                <h4>{{ number_format($batch->slaughterRecords->sum('count')) }}</h4>
                <small>Total Slaughtered</small>
            </div>
        </div>
    </div>
</div>

<!-- Batch Details Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="batchTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-info-circle me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="feed-tab" data-bs-toggle="tab" data-bs-target="#feed" type="button" role="tab">
                    <i class="fas fa-seedling me-2"></i>Feed Records
                    <span class="badge bg-primary ms-1">{{ $batch->feedRecords->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="death-tab" data-bs-toggle="tab" data-bs-target="#death" type="button" role="tab">
                    <i class="fas fa-heart-broken me-2"></i>Death Records
                    <span class="badge bg-danger ms-1">{{ $batch->deathRecords->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="slaughter-tab" data-bs-toggle="tab" data-bs-target="#slaughter" type="button" role="tab">
                    <i class="fas fa-cut me-2"></i>Slaughter Records
                    <span class="badge bg-dark ms-1">{{ $batch->slaughterRecords->count() }}</span>
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="batchTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle me-2 text-primary"></i>Batch Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Batch ID:</strong></td>
                                <td>{{ $batch->batch_id }}</td>
                            </tr>
                            @if($batch->name)
                            <tr>
                                <td><strong>Batch Name:</strong></td>
                                <td>{{ $batch->name }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Animal Type:</strong></td>
                                <td><span class="badge bg-info">{{ ucfirst($batch->animal_type) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td>{{ $batch->start_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Current Age:</strong></td>
                                <td><span class="badge bg-secondary">{{ $batch->age_display }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Responsible Person:</strong></td>
                                <td>{{ $batch->responsible_person }}</td>
                            </tr>
                            @if($batch->location)
                            <tr>
                                <td><strong>Location:</strong></td>
                                <td>{{ $batch->location }}</td>
                            </tr>
                            @endif
                            @if($batch->supplier)
                            <tr>
                                <td><strong>Supplier:</strong></td>
                                <td>{{ $batch->supplier }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-chart-pie me-2 text-success"></i>Batch Statistics</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Initial Count:</strong></td>
                                <td>{{ number_format($batch->initial_count) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Current Count:</strong></td>
                                <td><strong class="text-success">{{ number_format($batch->current_count) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Deaths:</strong></td>
                                <td><span class="text-danger">{{ number_format($batch->deathRecords->sum('count')) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Total Slaughtered:</strong></td>
                                <td><span class="text-dark">{{ number_format($batch->slaughterRecords->sum('count')) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Survival Rate:</strong></td>
                                <td>
                                    @php
                                        $survivalRate = $batch->initial_count > 0 ? ($batch->current_count / $batch->initial_count) * 100 : 0;
                                    @endphp
                                    <span class="badge {{ $survivalRate > 80 ? 'bg-success' : ($survivalRate > 60 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ number_format($survivalRate, 1) }}%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Total Feed Records:</strong></td>
                                <td>{{ $batch->feedRecords->count() }}</td>
                            </tr>
                        </table>
                        
                        @if($batch->notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Notes</h6>
                            <div class="alert alert-light">{{ $batch->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Feed Records Tab -->
            <div class="tab-pane fade" id="feed" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="fas fa-seedling me-2 text-success"></i>Feed Records</h5>
                    <a href="{{ route('batches.feed', $batch) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i>Add Feed Record
                    </a>
                </div>
                
                @if($batch->feedRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Age</th>
                                <th>Feed Type</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->feedRecords->sortByDesc('feed_date') as $feed)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($feed->feed_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($feed->feed_date)->diffInDays($batch->start_date) }} days
                                    </span>
                                </td>
                                <td>{{ $feed->feed_type }}</td>
                                <td>{{ number_format($feed->quantity, 2) }} {{ $feed->unit }}</td>
                                <td>
                                    @if($feed->cost_per_unit)
                                        ${{ number_format($feed->cost_per_unit * $feed->quantity, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $feed->notes ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3">Total</th>
                                <th>{{ number_format($batch->feedRecords->sum('quantity'), 2) }} kg</th>
                                <th>
                                    ${{ number_format($batch->feedRecords->sum(function($feed) {
                                        return $feed->quantity * ($feed->cost_per_unit ?: 0);
                                    }), 2) }}
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No feed records yet</h6>
                    <a href="{{ route('batches.feed', $batch) }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add First Feed Record
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Death Records Tab -->
            <div class="tab-pane fade" id="death" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="fas fa-heart-broken me-2 text-danger"></i>Death Records</h5>
                    <a href="{{ route('batches.death', $batch) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-plus me-2"></i>Record Death
                    </a>
                </div>
                
                @if($batch->deathRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Age</th>
                                <th>Count</th>
                                <th>Cause</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->deathRecords->sortByDesc('death_date') as $death)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($death->death_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($death->death_date)->diffInDays($batch->start_date) }} days
                                    </span>
                                </td>
                                <td><span class="badge bg-danger">{{ $death->count }}</span></td>
                                <td>{{ $death->cause ?: '-' }}</td>
                                <td>{{ $death->notes ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total Deaths</th>
                                <th><span class="badge bg-danger">{{ $batch->deathRecords->sum('count') }}</span></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-heart-broken fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No death records</h6>
                    <p class="text-muted">Great! No deaths recorded for this batch.</p>
                </div>
                @endif
            </div>
            
            <!-- Slaughter Records Tab -->
            <div class="tab-pane fade" id="slaughter" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="fas fa-cut me-2 text-dark"></i>Slaughter Records</h5>
                    <a href="{{ route('batches.slaughter', $batch) }}" class="btn btn-dark btn-sm">
                        <i class="fas fa-plus me-2"></i>Record Slaughter
                    </a>
                </div>
                
                @if($batch->slaughterRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Age</th>
                                <th>Count</th>
                                <th>Avg Weight</th>
                                <th>Total Weight</th>
                                <th>Price/kg</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->slaughterRecords->sortByDesc('slaughter_date') as $slaughter)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($slaughter->slaughter_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($slaughter->slaughter_date)->diffInDays($batch->start_date) }} days
                                    </span>
                                </td>
                                <td><span class="badge bg-dark">{{ $slaughter->count }}</span></td>
                                <td>
                                    @if($slaughter->average_weight)
                                        {{ number_format($slaughter->average_weight, 2) }} kg
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($slaughter->total_weight)
                                        {{ number_format($slaughter->total_weight, 2) }} kg
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($slaughter->price_per_kg)
                                        ${{ number_format($slaughter->price_per_kg, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($slaughter->total_weight && $slaughter->price_per_kg)
                                        <strong>${{ number_format($slaughter->total_weight * $slaughter->price_per_kg, 2) }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total</th>
                                <th><span class="badge bg-dark">{{ $batch->slaughterRecords->sum('count') }}</span></th>
                                <th></th>
                                <th>{{ number_format($batch->slaughterRecords->sum('total_weight'), 2) }} kg</th>
                                <th></th>
                                <th>
                                    <strong>
                                        ${{ number_format($batch->slaughterRecords->sum(function($slaughter) {
                                            return $slaughter->total_weight * ($slaughter->price_per_kg ?: 0);
                                        }), 2) }}
                                    </strong>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-cut fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No slaughter records yet</h6>
                    <p class="text-muted">No animals have been slaughtered from this batch.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
