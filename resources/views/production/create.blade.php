@extends('layouts.app')

@section('title', 'Add Production Record - Farm Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus-circle me-2 text-success"></i>Add Production Record
                </h1>
                <p class="text-muted mb-0">Record new production from your farm</p>
            </div>
            <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Production Records
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-egg me-2"></i>Production Record Form
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('production.store') }}" id="productionForm">
                    @csrf
                    
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
                                   value="{{ old('production_date', date('Y-m-d')) }}" 
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
                                            {{ old('batch_id') == $batch->batch_id ? 'selected' : '' }}>
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
                                <option value="eggs" {{ old('product_type') == 'eggs' ? 'selected' : '' }}>🥚 Eggs</option>
                                <option value="meat" {{ old('product_type') == 'meat' ? 'selected' : '' }}>🥩 Meat</option>
                                <option value="milk" {{ old('product_type') == 'milk' ? 'selected' : '' }}>🥛 Milk</option>
                                <option value="other" {{ old('product_type') == 'other' ? 'selected' : '' }}>📦 Other</option>
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
                                   value="{{ old('quantity') }}" 
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
                                <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Liters</option>
                                <option value="units" {{ old('unit') == 'units' ? 'selected' : '' }}>Units</option>
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
                                  placeholder="Additional notes about the production">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('production.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Record Production
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Production Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-lightbulb me-2 text-warning"></i>Quick Tips</h6>
                    <ul class="small text-muted mb-0">
                        <li>Select the batch that produced the items</li>
                        <li>Choose the appropriate product type</li>
                        <li>Enter the accurate quantity produced</li>
                        <li>Use consistent units for better tracking</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-chart-line me-2 text-info"></i>Common Products</h6>
                    <ul class="small text-muted mb-0">
                        <li><strong>🥚 Eggs:</strong> Daily egg collection</li>
                        <li><strong>🥩 Meat:</strong> Slaughtered animals</li>
                        <li><strong>🥛 Milk:</strong> Daily milk production</li>
                        <li><strong>📦 Other:</strong> By-products, fertilizer, etc.</li>
                    </ul>
                </div>

                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-2"></i>
                    Production records help track farm productivity and plan for future needs.
                </div>
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
        const unitOptions = unitSelect.querySelectorAll('option');
        
        // Reset all options
        unitOptions.forEach(option => {
            if (option.value) {
                option.style.display = 'block';
            }
        });

        // Suggest appropriate units
        switch(productType) {
            case 'eggs':
                // Focus on pieces and dozen for eggs
                unitSelect.value = 'pieces';
                break;
            case 'meat':
                // Focus on kg for meat
                unitSelect.value = 'kg';
                break;
            case 'milk':
                // Focus on liters for milk
                unitSelect.value = 'liters';
                break;
            default:
                unitSelect.value = '';
        }
    });
});
</script>
@endsection
