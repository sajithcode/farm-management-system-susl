@extends('layouts.app')

@section('title', 'Individual Animals')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-paw me-2 text-primary"></i>Individual Animals
        </h1>
        <p class="text-muted mb-0">Manage and track individual animals in your farm</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('individual-animals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Animal
        </a>
    </div>
</div>

<!-- Animals Summary Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-heartbeat fa-2x mb-2"></i>
                <h4>{{ $animals->where('status', 'alive')->count() }}</h4>
                <small>Alive Animals</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-heart-broken fa-2x mb-2"></i>
                <h4>{{ $animals->where('status', 'dead')->count() }}</h4>
                <small>Dead Animals</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <i class="fas fa-cut fa-2x mb-2"></i>
                <h4>{{ $animals->where('status', 'slaughtered')->count() }}</h4>
                <small>Slaughtered</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h4>{{ $animals->total() }}</h4>
                <small>Total Animals</small>
            </div>
        </div>
    </div>
</div>

<!-- Animals Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>All Individual Animals
        </h5>
    </div>
    <div class="card-body">
        @if($animals->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Animal ID</th>
                        <th>Type</th>
                        <th>Date of Birth</th>
                        <th>Age</th>
                        <th>Status</th>
                        <th>Responsible Person</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($animals as $animal)
                    <tr>
                        <td>
                            <strong>{{ $animal->animal_id }}</strong>
                            @if($animal->gender !== 'unknown')
                                <small class="text-muted d-block">
                                    <i class="fas fa-{{ $animal->gender === 'male' ? 'mars' : 'venus' }} me-1"></i>
                                    {{ ucfirst($animal->gender) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($animal->animal_type) }}</span>
                        </td>
                        <td>{{ $animal->date_of_birth->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $animal->age_display }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $animal->status_badge }}">
                                {{ ucfirst($animal->status) }}
                            </span>
                        </td>
                        <td>{{ $animal->responsible_person }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('individual-animals.show', $animal) }}" 
                                   class="btn btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($animal->status === 'alive')
                                <a href="{{ route('individual-animals.feed', $animal) }}" 
                                   class="btn btn-outline-success" title="Feed">
                                    <i class="fas fa-seedling"></i>
                                </a>
                                <a href="{{ route('individual-animals.death', $animal) }}" 
                                   class="btn btn-outline-danger" title="Record Death">
                                    <i class="fas fa-heart-broken"></i>
                                </a>
                                <a href="{{ route('individual-animals.slaughter', $animal) }}" 
                                   class="btn btn-outline-dark" title="Record Slaughter">
                                    <i class="fas fa-cut"></i>
                                </a>
                                @else
                                <span class="btn btn-outline-secondary disabled btn-sm">
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
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $animals->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-paw fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No Individual Animals Found</h5>
            <p class="text-muted">Start by adding your first individual animal to the system.</p>
            <a href="{{ route('individual-animals.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add First Animal
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
