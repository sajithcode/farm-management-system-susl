@extends('layouts.app')

@section('title', 'All Production Records - Farm Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-clipboard-list me-2 text-success"></i>All Production Records
                </h1>
                <p class="text-muted mb-0">View and manage all production records</p>
            </div>
            <a href="{{ route('production.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add Production Record
            </a>
        </div>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('production.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="batch_filter" class="form-label">Filter by Batch</label>
                        <select class="form-select" id="batch_filter" name="batch">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->batch_id }}" {{ request('batch') == $batch->batch_id ? 'selected' : '' }}>
                                    {{ $batch->batch_id }} - {{ ucfirst($batch->animal_type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="product_filter" class="form-label">Filter by Product</label>
                        <select class="form-select" id="product_filter" name="product_type">
                            <option value="">All Products</option>
                            <option value="eggs" {{ request('product_type') == 'eggs' ? 'selected' : '' }}>🥚 Eggs</option>
                            <option value="meat" {{ request('product_type') == 'meat' ? 'selected' : '' }}>🥩 Meat</option>
                            <option value="milk" {{ request('product_type') == 'milk' ? 'selected' : '' }}>🥛 Milk</option>
                            <option value="other" {{ request('product_type') == 'other' ? 'selected' : '' }}>📦 Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">Total Records</h5>
                        <h3 class="mb-0">{{ $statistics['total_records'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">This Month</h5>
                        <h3 class="mb-0">{{ $statistics['this_month'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-calendar-month fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">This Week</h5>
                        <h3 class="mb-0">{{ $statistics['this_week'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-calendar-week fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">Today</h5>
                        <h3 class="mb-0">{{ $statistics['today'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Production Records Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Production Records
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="exportTable('csv')">
                            <i class="fas fa-file-csv me-1"></i>CSV
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel me-1"></i>Excel
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($productions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="productionTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Batch</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Recorded By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productions as $production)
                                <tr>
                                    <td>{{ $loop->iteration + ($productions->currentPage() - 1) * $productions->perPage() }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $production->formatted_date }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $production->batch->batch_id }}</strong><br>
                                        <small class="text-muted">{{ ucfirst($production->batch->animal_type) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $production->product_type == 'eggs' ? 'bg-warning' : ($production->product_type == 'meat' ? 'bg-danger' : ($production->product_type == 'milk' ? 'bg-info' : 'bg-secondary')) }}">
                                            {{ $production->product_type_display }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($production->quantity, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ ucfirst($production->unit) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $production->user->name }}</small><br>
                                        <small class="text-muted">{{ $production->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('production.show', $production->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('production.edit', $production->id) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Edit Record">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete Record"
                                                    onclick="confirmDelete({{ $production->id }}, '{{ $production->product_type_display }} - {{ $production->formatted_date }}')">
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
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $productions->firstItem() }} to {{ $productions->lastItem() }} of {{ $productions->total() }} results
                        </div>
                        {{ $productions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Production Records Found</h5>
                        <p class="text-muted">Start by adding your first production record.</p>
                        <a href="{{ route('production.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Add Production Record
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
                <p>Are you sure you want to delete this production record?</p>
                <p><strong id="deleteRecordInfo"></strong></p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(productionId, recordInfo) {
    document.getElementById('deleteRecordInfo').textContent = recordInfo;
    document.getElementById('deleteForm').action = `/production/${productionId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function exportTable(format) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export', format);
    window.location.href = currentUrl.toString();
}

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('#batch_filter, #product_filter');
    const dateInputs = document.querySelectorAll('#date_from, #date_to');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endsection
