@extends('layouts.app')

@section('title', 'All Medicine Records')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-pills me-2 text-primary"></i>Medicine Management
        </h1>
        <p class="text-muted mb-0">Track and manage all medicine applications for batches and individual animals</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('medicines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Medicine Record
        </a>
    </div>
</div>

<!-- Medicine Summary Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-pills fa-2x mb-2"></i>
                <h4>{{ $totalRecords }}</h4>
                <small>Total Records</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                <h4>{{ $recentRecords }}</h4>
                <small>Recent (30 days)</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-layer-group fa-2x mb-2"></i>
                <h4>{{ $batchRecords }}</h4>
                <small>Batch Records</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-paw fa-2x mb-2"></i>
                <h4>{{ $individualRecords }}</h4>
                <small>Individual Records</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('medicines.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="apply_to" class="form-label">Application Type</label>
                <select class="form-select" id="apply_to" name="apply_to">
                    <option value="">All Types</option>
                    <option value="batch" {{ request('apply_to') === 'batch' ? 'selected' : '' }}>Batch Applications</option>
                    <option value="individual" {{ request('apply_to') === 'individual' ? 'selected' : '' }}>Individual Applications</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Medicine name, animal ID...">
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filter
                </button>
                <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Medicine Records Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>All Medicine Records
        </h5>
    </div>
    <div class="card-body">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Animal/Batch</th>
                        <th>Medicine Type</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Administered By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    <tr>
                        <td>
                            <strong>{{ $medicine->medicine_date->format('M d, Y') }}</strong>
                            <small class="text-muted d-block">{{ $medicine->medicine_date->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($medicine->apply_to === 'batch')
                                <span class="badge bg-info me-1">Batch</span>
                                <strong>{{ $medicine->batch ? $medicine->batch->batch_id : $medicine->batch_id }}</strong>
                                @if($medicine->batch)
                                    <small class="text-muted d-block">{{ ucfirst($medicine->batch->animal_type) }}</small>
                                @endif
                            @else
                                <span class="badge bg-warning me-1">Individual</span>
                                <strong>{{ $medicine->animal_id }}</strong>
                                @if($medicine->individualAnimal)
                                    <small class="text-muted d-block">{{ ucfirst($medicine->individualAnimal->animal_type) }}</small>
                                @endif
                            @endif
                        </td>
                        <td>
                            <strong>{{ $medicine->medicine_name }}</strong>
                            @if($medicine->notes)
                                <small class="text-muted d-block">{{ Str::limit($medicine->notes, 30) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $medicine->quantity_with_unit }}</span>
                        </td>
                        <td>
                            @if($medicine->cost_per_unit)
                                <strong>${{ number_format($medicine->total_cost, 2) }}</strong>
                                <small class="text-muted d-block">${{ number_format($medicine->cost_per_unit, 2) }}/{{ $medicine->unit }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $medicine->administered_by }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('medicines.show', $medicine) }}" 
                                   class="btn btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('medicines.edit', $medicine) }}" 
                                   class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        title="Delete" 
                                        onclick="confirmDelete({{ $medicine->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $medicines->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-pills fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No Medicine Records Found</h5>
            <p class="text-muted">Start by adding your first medicine application record.</p>
            <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add First Medicine Record
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this medicine record? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(medicineId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/medicines/${medicineId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
