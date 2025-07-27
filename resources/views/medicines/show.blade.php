@extends('layouts.app')

@section('title', 'Medicine Record Details')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-pills me-2 text-primary"></i>Medicine Record Details
        </h1>
        <p class="text-muted mb-0">
            Applied on {{ $medicine->medicine_date->format('M d, Y') }} to 
            {{ $medicine->target_display }}
        </p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Records
        </a>
    </div>
</div>

<!-- Medicine Details -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Medicine Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Application Date:</strong></td>
                                <td>{{ $medicine->medicine_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Medicine Name:</strong></td>
                                <td><span class="badge bg-primary">{{ $medicine->medicine_name }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Quantity:</strong></td>
                                <td>{{ $medicine->quantity_with_unit }}</td>
                            </tr>
                            <tr>
                                <td><strong>Application Type:</strong></td>
                                <td>
                                    @if($medicine->apply_to === 'batch')
                                        <span class="badge bg-info">Batch Application</span>
                                    @else
                                        <span class="badge bg-warning">Individual Application</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Administered By:</strong></td>
                                <td>{{ $medicine->administered_by }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            @if($medicine->cost_per_unit)
                            <tr>
                                <td><strong>Cost per Unit:</strong></td>
                                <td>${{ number_format($medicine->cost_per_unit, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Cost:</strong></td>
                                <td><strong>${{ number_format($medicine->total_cost, 2) }}</strong></td>
                            </tr>
                            @else
                            <tr>
                                <td><strong>Cost:</strong></td>
                                <td><span class="text-muted">Not recorded</span></td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Recorded By:</strong></td>
                                <td>{{ $medicine->user->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Record Created:</strong></td>
                                <td>{{ $medicine->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if($medicine->updated_at != $medicine->created_at)
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $medicine->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                @if($medicine->notes)
                <div class="mt-3">
                    <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Notes</h6>
                    <div class="alert alert-light">{{ $medicine->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Target Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-target me-2"></i>Target Information
                </h6>
            </div>
            <div class="card-body">
                @if($medicine->apply_to === 'batch' && $medicine->batch)
                    <h6 class="text-primary">Batch: {{ $medicine->batch->batch_id }}</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Animal Type:</strong></td>
                            <td>{{ ucfirst($medicine->batch->animal_type) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Initial Count:</strong></td>
                            <td>{{ $medicine->batch->initial_count }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Count:</strong></td>
                            <td>{{ $medicine->batch->current_count }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $medicine->batch->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($medicine->batch->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Batch Date:</strong></td>
                            <td>{{ $medicine->batch->batch_date->format('M d, Y') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('batches.show', $medicine->batch) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-2"></i>View Batch Details
                        </a>
                    </div>
                @elseif($medicine->apply_to === 'individual' && $medicine->individualAnimal)
                    <h6 class="text-warning">Individual: {{ $medicine->animal_id }}</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Animal Type:</strong></td>
                            <td>{{ ucfirst($medicine->individualAnimal->animal_type) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td>
                                @if($medicine->individualAnimal->gender !== 'unknown')
                                    {{ ucfirst($medicine->individualAnimal->gender) }}
                                    <i class="fas fa-{{ $medicine->individualAnimal->gender === 'male' ? 'mars' : 'venus' }} ms-1"></i>
                                @else
                                    Unknown
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date of Birth:</strong></td>
                            <td>{{ $medicine->individualAnimal->date_of_birth->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Age:</strong></td>
                            <td>{{ $medicine->individualAnimal->age_display }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $medicine->individualAnimal->status_badge }}">
                                    {{ ucfirst($medicine->individualAnimal->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($medicine->individualAnimal->current_weight)
                        <tr>
                            <td><strong>Current Weight:</strong></td>
                            <td>{{ number_format($medicine->individualAnimal->current_weight, 1) }} kg</td>
                        </tr>
                        @endif
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('individual-animals.show', $medicine->individualAnimal) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-2"></i>View Animal Details
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                        <p class="text-muted mb-0">Target information not available</p>
                        <small class="text-muted">
                            {{ $medicine->apply_to === 'batch' ? 'Batch' : 'Animal' }}: 
                            {{ $medicine->apply_to === 'batch' ? $medicine->batch_id : $medicine->animal_id }}
                        </small>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit Record
                    </a>
                    
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>Delete Record
                    </button>
                    
                    <hr>
                    
                    <a href="{{ route('medicines.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i>Add New Medicine Record
                    </a>
                    
                    @if($medicine->apply_to === 'individual' && $medicine->individualAnimal && $medicine->individualAnimal->status === 'alive')
                    <a href="{{ route('individual-animals.feed', $medicine->individualAnimal) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-seedling me-2"></i>Feed This Animal
                    </a>
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
                <p>Are you sure you want to delete this medicine record?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('medicines.destroy', $medicine) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
