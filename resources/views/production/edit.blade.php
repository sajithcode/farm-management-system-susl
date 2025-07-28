@extends('layouts.app')

@section('title', 'Edit Production Record - Farm Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit me-2 text-warning"></i>Edit Production Record
                </h1>
                <p class="text-muted mb-0">Update production record information</p>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('production.show', $production->id) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>View Details
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
                    <i class="fas fa-edit me-2"></i>Edit Production Record Form
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('production.update', $production->id) }}" id="productionEditForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label for="production_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('production_date') is-invalid @enderror" 
                                   id="production_date" 
                                   name="production_date" 
                                   value="{{ old('production_date', $production->production_date) }}" 
                                   max="{{ date('Y-m-d') }}" 
                                   required>
                            @error('production_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Batch Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="batch_id" class="form-label">
                                <i class="fas fa-users me-1"></i>Batch <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('batch_id') is-invalid @enderror" 
                                    id="batch_id" 
                                    name="batch_id" 
                                    required>
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->batch_id }}" 
                                            {{ old('batch_id', $production->batch_id) == $batch->batch_id ? 'selected' : '' }}>
                                        {{ $batch->batch_id }} - {{ ucfirst($batch->animal_type) }} ({{ $batch->current_count }} animals)
                                    </option>
                                @endforeach
                            </select>
                            @error('batch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Product Type -->
                        <div class="col-md-6 mb-3">
                            <label for="product_type" class="form-label">
                                <i class="fas fa-tag me-1"></i>Product Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('product_type') is-invalid @enderror" 
                                    id="product_type" 
                                    name="product_type" 
                                    required>
                                <option value="">Select Product Type</option>
                                <option value="eggs" {{ old('product_type', $production->product_type) == 'eggs' ? 'selected' : '' }}>🥚 Eggs</option>
                                <option value="meat" {{ old('product_type', $production->product_type) == 'meat' ? 'selected' : '' }}>🥩 Meat</option>
                                <option value="milk" {{ old('product_type', $production->product_type) == 'milk' ? 'selected' : '' }}>🥛 Milk</option>
                                <option value="other" {{ old('product_type', $production->product_type) == 'other' ? 'selected' : '' }}>📦 Other</option>
                            </select>
                            @error('product_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">
                                <i class="fas fa-balance-scale me-1"></i>Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', $production->quantity) }}" 
                                   step="0.01" 
                                   min="0.01" 
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Unit -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">
                                <i class="fas fa-ruler me-1"></i>Unit <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('unit') is-invalid @enderror" 
                                    id="unit" 
                                    name="unit" 
                                    required>
                                <option value="">Select Unit</option>
                                <option value="pieces" {{ old('unit', $production->unit) == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                <option value="dozen" {{ old('unit', $production->unit) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                <option value="kg" {{ old('unit', $production->unit) == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="liters" {{ old('unit', $production->unit) == 'liters' ? 'selected' : '' }}>Liters</option>
                                <option value="units" {{ old('unit', $production->unit) == 'units' ? 'selected' : '' }}>Units</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Additional notes about the production">{{ old('notes', $production->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="btn-group" role="group">
                            <a href="{{ route('production.show', $production->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Production
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Current Record Info -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Current Record
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted">Original Date:</td>
                        <td><strong>{{ $production->formatted_date }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Current Batch:</td>
                        <td><strong>{{ $production->batch->batch_id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Current Product:</td>
                        <td>
                            <span class="badge {{ $production->product_type == 'eggs' ? 'bg-warning' : ($production->product_type == 'meat' ? 'bg-danger' : ($production->product_type == 'milk' ? 'bg-info' : 'bg-secondary')) }}">
                                {{ $production->product_type_display }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Current Quantity:</td>
                        <td><strong>{{ number_format($production->quantity, 2) }} {{ $production->unit }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Recorded By:</td>
                        <td>{{ $production->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created:</td>
                        <td>{{ $production->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Edit Guidelines -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Edit Guidelines
                </h5>
            </div>
            <div class="card-body">
                <ul class="small text-muted mb-0">
                    <li>Ensure the production date is accurate</li>
                    <li>Verify the batch information is correct</li>
                    <li>Double-check quantity and units</li>
                    <li>Update notes if additional information is available</li>
                    <li>Changes will be tracked with timestamps</li>
                </ul>
                
                <div class="alert alert-warning small mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Changes to production records may affect reports and statistics.
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('production.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i>Add New Production
                    </a>
                    <a href="{{ route('production.index', ['batch' => $production->batch_id]) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-filter me-2"></i>View Batch Productions
                    </a>
                    <a href="{{ route('production.index', ['product_type' => $production->product_type]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-2"></i>View Similar Products
                    </a>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productTypeSelect = document.getElementById('product_type');
    const unitSelect = document.getElementById('unit');

    // Auto-suggest units based on product type
    productTypeSelect.addEventListener('change', function() {
        const productType = this.value;
        const currentUnit = unitSelect.value;
        
        // Only change if current unit is empty
        if (!currentUnit) {
            switch(productType) {
                case 'eggs':
                    unitSelect.value = 'pieces';
                    break;
                case 'meat':
                    unitSelect.value = 'kg';
                    break;
                case 'milk':
                    unitSelect.value = 'liters';
                    break;
            }
        }
    });
});

function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
