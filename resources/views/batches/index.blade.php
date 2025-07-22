@extends('layouts.app')

@section('title', 'All Batches - Animal Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2 text-primary"></i>All Batches
        </h1>
        <p class="text-muted mb-0">Manage your animal batches</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('batches.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Batch
        </a>
    </div>
</div>

<!-- Batches Table -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Batch Overview
        </h5>
    </div>
    <div class="card-body">
        @if($batches->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Batch ID</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>Age</th>
                        <th>Current Count</th>
                        <th>Responsible Person</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batches as $batch)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $batch->batch_id }}</strong>
                            @if($batch->name)
                                <br><small class="text-muted">{{ $batch->name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($batch->animal_type) }}</span>
                        </td>
                        <td>
                            <strong>{{ $batch->start_date->format('M d, Y') }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $batch->age_display }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <strong class="{{ $batch->current_count > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($batch->current_count) }}
                                </strong>
                                <small class="text-muted ms-1">/ {{ number_format($batch->initial_count) }}</small>
                            </div>
                            @if($batch->current_count != $batch->initial_count)
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $batch->initial_count > 0 ? ($batch->current_count / $batch->initial_count) * 100 : 0 }}%">
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>{{ $batch->responsible_person }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('batches.show', $batch) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
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
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $batches->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No batches yet</h5>
            <p class="text-muted">Create your first batch to start managing animals.</p>
            <a href="{{ route('batches.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Your First Batch
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Quick Stats Cards -->
@if($batches->count() > 0)
<div class="row mt-4">
    @php
        $totalBatches = $batches->total();
        $totalAnimals = $batches->sum('current_count');
        $totalInitialAnimals = $batches->sum('initial_count');
        $survivalRate = $totalInitialAnimals > 0 ? ($totalAnimals / $totalInitialAnimals) * 100 : 0;
    @endphp
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4>{{ number_format($totalBatches) }}</h4>
                <small>Total Batches</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-paw fa-2x mb-2"></i>
                <h4>{{ number_format($totalAnimals) }}</h4>
                <small>Current Animals</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h4>{{ number_format($survivalRate, 1) }}%</h4>
                <small>Survival Rate</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-seedling fa-2x mb-2"></i>
                <h4>{{ $batches->sum(function($batch) { return $batch->feedRecords->count(); }) }}</h4>
                <small>Feed Records</small>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
