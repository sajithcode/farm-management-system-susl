@extends('layouts.app')

@section('title', 'Animal Details - ' . $individualAnimal->animal_id)

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-paw me-2 text-primary"></i>{{ $individualAnimal->animal_id }}
            @if($individualAnimal->gender !== 'unknown')
                <i class="fas fa-{{ $individualAnimal->gender === 'male' ? 'mars' : 'venus' }} ms-2 text-{{ $individualAnimal->gender === 'male' ? 'primary' : 'danger' }}"></i>
            @endif
        </h1>
        <p class="text-muted mb-0">
            <i class="fas fa-paw me-1"></i>{{ ucfirst($individualAnimal->animal_type) }} 
            <span class="mx-2">•</span>
            <i class="fas fa-birthday-cake me-1"></i>Born {{ $individualAnimal->date_of_birth->format('M d, Y') }}
            <span class="mx-2">•</span>
            <span class="badge {{ $individualAnimal->status_badge }}">{{ ucfirst($individualAnimal->status) }}</span>
        </p>
    </div>
    <div class="col-md-4 text-md-end">
        @if($individualAnimal->status === 'alive')
        <div class="btn-group" role="group">
            <a href="{{ route('individual-animals.feed', $individualAnimal) }}" class="btn btn-success">
                <i class="fas fa-seedling me-2"></i>Feed
            </a>
            <a href="{{ route('individual-animals.death', $individualAnimal) }}" class="btn btn-danger">
                <i class="fas fa-heart-broken me-2"></i>Death
            </a>
            <a href="{{ route('individual-animals.slaughter', $individualAnimal) }}" class="btn btn-dark">
                <i class="fas fa-cut me-2"></i>Slaughter
            </a>
        </div>
        @endif
        <a href="{{ route('individual-animals.index') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<!-- Animal Overview Stats -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                <h4>{{ $individualAnimal->age_display }}</h4>
                <small>Current Age</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-{{ $individualAnimal->status === 'alive' ? 'success' : ($individualAnimal->status === 'dead' ? 'danger' : 'dark') }} text-white">
            <div class="card-body text-center">
                <i class="fas fa-{{ $individualAnimal->status === 'alive' ? 'heartbeat' : ($individualAnimal->status === 'dead' ? 'heart-broken' : 'cut') }} fa-2x mb-2"></i>
                <h4>{{ ucfirst($individualAnimal->status) }}</h4>
                <small>Current Status</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-seedling fa-2x mb-2"></i>
                <h4>{{ $individualAnimal->feedRecords->count() }}</h4>
                <small>Feed Records</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-weight fa-2x mb-2"></i>
                <h4>{{ $individualAnimal->current_weight ? number_format($individualAnimal->current_weight, 1) . ' kg' : 'N/A' }}</h4>
                <small>Current Weight</small>
            </div>
        </div>
    </div>
</div>

<!-- Animal Details Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="animalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-info-circle me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="feed-tab" data-bs-toggle="tab" data-bs-target="#feed" type="button" role="tab">
                    <i class="fas fa-seedling me-2"></i>Feed History
                    <span class="badge bg-primary ms-1">{{ $individualAnimal->feedRecords->count() }}</span>
                </button>
            </li>
            @if($individualAnimal->status === 'dead' && $individualAnimal->deathRecord)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="death-tab" data-bs-toggle="tab" data-bs-target="#death" type="button" role="tab">
                    <i class="fas fa-heart-broken me-2"></i>Death Record
                </button>
            </li>
            @endif
            @if($individualAnimal->status === 'slaughtered' && $individualAnimal->slaughterRecord)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="slaughter-tab" data-bs-toggle="tab" data-bs-target="#slaughter" type="button" role="tab">
                    <i class="fas fa-cut me-2"></i>Slaughter Record
                </button>
            </li>
            @endif
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="animalTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle me-2 text-primary"></i>Animal Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Animal ID:</strong></td>
                                <td>{{ $individualAnimal->animal_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Animal Type:</strong></td>
                                <td><span class="badge bg-info">{{ ucfirst($individualAnimal->animal_type) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Date of Birth:</strong></td>
                                <td>{{ $individualAnimal->date_of_birth->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Current Age:</strong></td>
                                <td><span class="badge bg-secondary">{{ $individualAnimal->age_display }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Gender:</strong></td>
                                <td>
                                    @if($individualAnimal->gender !== 'unknown')
                                        <i class="fas fa-{{ $individualAnimal->gender === 'male' ? 'mars' : 'venus' }} me-1 text-{{ $individualAnimal->gender === 'male' ? 'primary' : 'danger' }}"></i>
                                        {{ ucfirst($individualAnimal->gender) }}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge {{ $individualAnimal->status_badge }}">{{ ucfirst($individualAnimal->status) }}</span></td>
                            </tr>
                            @if($individualAnimal->supplier)
                            <tr>
                                <td><strong>Supplier:</strong></td>
                                <td>{{ $individualAnimal->supplier }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Responsible Person:</strong></td>
                                <td>{{ $individualAnimal->responsible_person }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-chart-pie me-2 text-success"></i>Health & Statistics</h5>
                        <table class="table table-sm">
                            @if($individualAnimal->current_weight)
                            <tr>
                                <td><strong>Current Weight:</strong></td>
                                <td>{{ number_format($individualAnimal->current_weight, 2) }} kg</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Total Feed Records:</strong></td>
                                <td>{{ $individualAnimal->feedRecords->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Feed Given:</strong></td>
                                <td>{{ number_format($individualAnimal->feedRecords->sum('quantity'), 2) }} kg</td>
                            </tr>
                            <tr>
                                <td><strong>Feed Cost:</strong></td>
                                <td>
                                    ${{ number_format($individualAnimal->feedRecords->sum(function($feed) {
                                        return $feed->quantity * ($feed->cost_per_unit ?: 0);
                                    }), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Days Alive:</strong></td>
                                <td>
                                    @if($individualAnimal->status === 'alive')
                                        {{ $individualAnimal->age_in_days }}
                                    @elseif($individualAnimal->deathRecord)
                                        {{ $individualAnimal->deathRecord->age_in_days }}
                                    @elseif($individualAnimal->slaughterRecord)
                                        {{ $individualAnimal->slaughterRecord->age_in_days }}
                                    @else
                                        {{ $individualAnimal->age_in_days }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Added By:</strong></td>
                                <td>{{ $individualAnimal->user->name ?? 'Unknown' }}</td>
                            </tr>
                        </table>
                        
                        @if($individualAnimal->notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Notes</h6>
                            <div class="alert alert-light">{{ $individualAnimal->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Feed History Tab -->
            <div class="tab-pane fade" id="feed" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="fas fa-seedling me-2 text-success"></i>Feed History</h5>
                    @if($individualAnimal->status === 'alive')
                    <a href="{{ route('individual-animals.feed', $individualAnimal) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i>Add Feed Record
                    </a>
                    @endif
                </div>
                
                @if($individualAnimal->feedRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Feed Type</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Administered By</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($individualAnimal->feedRecords->sortByDesc('feed_date') as $feed)
                            <tr>
                                <td>{{ $feed->feed_date->format('M d, Y') }}</td>
                                <td>{{ $feed->feed_type }}</td>
                                <td>{{ number_format($feed->quantity, 2) }} {{ $feed->unit }}</td>
                                <td>
                                    @if($feed->cost_per_unit)
                                        ${{ number_format($feed->cost_per_unit * $feed->quantity, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $feed->administered_by }}</td>
                                <td>{{ $feed->notes ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total</th>
                                <th>{{ number_format($individualAnimal->feedRecords->sum('quantity'), 2) }} kg</th>
                                <th>
                                    ${{ number_format($individualAnimal->feedRecords->sum(function($feed) {
                                        return $feed->quantity * ($feed->cost_per_unit ?: 0);
                                    }), 2) }}
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No feed records yet</h6>
                    @if($individualAnimal->status === 'alive')
                    <a href="{{ route('individual-animals.feed', $individualAnimal) }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add First Feed Record
                    </a>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Death Record Tab -->
            @if($individualAnimal->status === 'dead' && $individualAnimal->deathRecord)
            <div class="tab-pane fade" id="death" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <h5><i class="fas fa-heart-broken me-2 text-danger"></i>Death Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Death Date:</strong></td>
                                <td>{{ $individualAnimal->deathRecord->death_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Age at Death:</strong></td>
                                <td>{{ $individualAnimal->deathRecord->age_in_days }} days</td>
                            </tr>
                            @if($individualAnimal->deathRecord->weight)
                            <tr>
                                <td><strong>Weight at Death:</strong></td>
                                <td>{{ number_format($individualAnimal->deathRecord->weight, 2) }} kg</td>
                            </tr>
                            @endif
                            @if($individualAnimal->deathRecord->cause)
                            <tr>
                                <td><strong>Cause of Death:</strong></td>
                                <td>{{ $individualAnimal->deathRecord->cause }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Recorded By:</strong></td>
                                <td>{{ $individualAnimal->deathRecord->user->name ?? 'Unknown' }}</td>
                            </tr>
                        </table>
                        
                        @if($individualAnimal->deathRecord->notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Notes</h6>
                            <div class="alert alert-light">{{ $individualAnimal->deathRecord->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Slaughter Record Tab -->
            @if($individualAnimal->status === 'slaughtered' && $individualAnimal->slaughterRecord)
            <div class="tab-pane fade" id="slaughter" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <h5><i class="fas fa-cut me-2 text-dark"></i>Slaughter Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Slaughter Date:</strong></td>
                                <td>{{ $individualAnimal->slaughterRecord->slaughter_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Age at Slaughter:</strong></td>
                                <td>{{ $individualAnimal->slaughterRecord->age_in_days }} days</td>
                            </tr>
                            @if($individualAnimal->slaughterRecord->live_weight)
                            <tr>
                                <td><strong>Live Weight:</strong></td>
                                <td>{{ number_format($individualAnimal->slaughterRecord->live_weight, 2) }} kg</td>
                            </tr>
                            @endif
                            @if($individualAnimal->slaughterRecord->dressed_weight)
                            <tr>
                                <td><strong>Dressed Weight:</strong></td>
                                <td>{{ number_format($individualAnimal->slaughterRecord->dressed_weight, 2) }} kg</td>
                            </tr>
                            @endif
                            @if($individualAnimal->slaughterRecord->purpose)
                            <tr>
                                <td><strong>Purpose:</strong></td>
                                <td>{{ $individualAnimal->slaughterRecord->purpose }}</td>
                            </tr>
                            @endif
                            @if($individualAnimal->slaughterRecord->medicine_used)
                            <tr>
                                <td><strong>Medicine Used:</strong></td>
                                <td>{{ $individualAnimal->slaughterRecord->medicine_used }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Recorded By:</strong></td>
                                <td>{{ $individualAnimal->slaughterRecord->user->name ?? 'Unknown' }}</td>
                            </tr>
                        </table>
                        
                        @if($individualAnimal->slaughterRecord->notes)
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Notes</h6>
                            <div class="alert alert-light">{{ $individualAnimal->slaughterRecord->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
