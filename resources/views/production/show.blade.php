@extends('layouts.app')

@section('title', 'Production Record Details - Farm Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-eye me-2 text-info"></i>Production Record Details
                </h1>
                <p class="text-muted mb-0">View production record information</p>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('production.edit', $production->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit Record
                </a>
                <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Production Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Production Date:</td>
                                <td>
                                    <span class="badge bg-primary">{{ $production->formatted_date }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Batch ID:</td>
                                <td>
                                    <strong>{{ $production->batch->batch_id }}</strong>
                                    <br><small class="text-muted">{{ ucfirst($production->batch->animal_type) }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Product Type:</td>
                                <td>
                                    <span class="badge {{ $production->product_type == 'eggs' ? 'bg-warning' : ($production->product_type == 'meat' ? 'bg-danger' : ($production->product_type == 'milk' ? 'bg-info' : 'bg-secondary')) }}">
                                        {{ $production->product_type_display }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Quantity:</td>
                                <td>
                                    <span class="h5 text-success mb-0">{{ number_format($production->quantity, 2) }}</span>
                                    <span class="badge bg-light text-dark ms-2">{{ ucfirst($production->unit) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Recorded By:</td>
                                <td>
                                    <strong>{{ $production->user->name }}</strong>
                                    <br><small class="text-muted">{{ $production->user->email }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Record Date:</td>
                                <td>
                                    <small class="text-muted">{{ $production->created_at->format('M d, Y H:i A') }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($production->notes)
                <div class="mt-4">
                    <h6 class="fw-bold text-muted">
                        <i class="fas fa-sticky-note me-2"></i>Notes:
                    </h6>
                    <div class="alert alert-light">
                        {{ $production->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Batch Information -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Batch Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Batch ID:</td>
                                <td><strong>{{ $production->batch->batch_id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Animal Type:</td>
                                <td>{{ ucfirst($production->batch->animal_type) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Current Count:</td>
                                <td><span class="badge bg-success">{{ $production->batch->current_count }} animals</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Start Date:</td>
                                <td>{{ \Carbon\Carbon::parse($production->batch->start_date)->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Age:</td>
                                <td>{{ \Carbon\Carbon::parse($production->batch->start_date)->diffInDays(now()) }} days</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Status:</td>
                                <td>
                                    <span class="badge {{ $production->batch->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($production->batch->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('production.edit', $production->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit This Record
                    </a>
                    <a href="{{ route('production.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add New Production
                    </a>
                    <a href="{{ route('production.index', ['batch' => $production->batch_id]) }}" class="btn btn-info">
                        <i class="fas fa-filter me-2"></i>View Batch Productions
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>Delete Record
                    </button>
                </div>
            </div>
        </div>

        <!-- Production Statistics -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Related Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">This Batch - Total Productions:</small>
                    <div class="h5 text-primary">{{ $batchProductionCount }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">This Product Type - This Month:</small>
                    <div class="h5 text-success">{{ $productTypeMonthCount }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Your Total Records:</small>
                    <div class="h5 text-info">{{ $userTotalCount }}</div>
                </div>
            </div>
        </div>

        <!-- Record History -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Record History
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Created</small>
                            <div class="small">{{ $production->created_at->format('M d, Y H:i A') }}</div>
                        </div>
                    </div>
                    @if($production->updated_at != $production->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Last Updated</small>
                            <div class="small">{{ $production->updated_at->format('M d, Y H:i A') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
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
                <div class="alert alert-info">
                    <strong>{{ $production->product_type_display }}</strong> - {{ $production->formatted_date }}<br>
                    Quantity: {{ number_format($production->quantity, 2) }} {{ $production->unit }}
                </div>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('production.destroy', $production->id) }}" style="display: inline;">
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

@section('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 15px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 17px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #dee2e6;
}
</style>
@endsection

@section('scripts')
<script>
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
