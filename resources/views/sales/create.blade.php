@extends('layouts.app')

@section('title', 'Add New Sale')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus-circle me-2 text-success"></i>Add New Sale</h1>
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Sales
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Sale Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Sale Date *</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item" class="form-label">Item *</label>
                                <select class="form-select @error('item') is-invalid @enderror" id="item" name="item" required>
                                    <option value="">Select Item</option>
                                    <option value="eggs" {{ old('item') === 'eggs' ? 'selected' : '' }}>
                                        🥚 Eggs
                                    </option>
                                    <option value="meat" {{ old('item') === 'meat' ? 'selected' : '' }}>
                                        🥩 Meat
                                    </option>
                                </select>
                                @error('item')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="source_type" class="form-label">Source Type *</label>
                                <select class="form-select @error('source_type') is-invalid @enderror" 
                                        id="source_type" name="source_type" required>
                                    <option value="">Select Source Type</option>
                                    <option value="batch" {{ old('source_type') === 'batch' ? 'selected' : '' }}>
                                        Batch
                                    </option>
                                    <option value="individual_animal" {{ old('source_type') === 'individual_animal' ? 'selected' : '' }}>
                                        Individual Animal
                                    </option>
                                </select>
                                @error('source_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="source_id" class="form-label">Source *</label>
                                <select class="form-select @error('source_id') is-invalid @enderror" 
                                        id="source_id" name="source_id" required disabled>
                                    <option value="">Select source type first</option>
                                </select>
                                @error('source_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0.01" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                                    <span class="input-group-text" id="quantity-unit">-</span>
                                </div>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price ($) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0.01" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="buyer" class="form-label">Buyer Name</label>
                        <input type="text" class="form-control @error('buyer') is-invalid @enderror" 
                               id="buyer" name="buyer" value="{{ old('buyer') }}" 
                               placeholder="Enter buyer name (optional)">
                        @error('buyer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Additional notes about the sale (optional)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Guide</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Items:</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-egg text-warning me-2"></i>Eggs (pieces)</li>
                        <li><i class="fas fa-drumstick-bite text-danger me-2"></i>Meat (kg)</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">Source Types:</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-users text-info me-2"></i>Batch - Group of animals</li>
                        <li><i class="fas fa-paw text-success me-2"></i>Individual Animal</li>
                    </ul>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tip:</strong> Select the source type first to see available sources.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sourceTypeSelect = document.getElementById('source_type');
    const sourceIdSelect = document.getElementById('source_id');
    const itemSelect = document.getElementById('item');
    const quantityUnit = document.getElementById('quantity-unit');

    // Update quantity unit based on selected item
    itemSelect.addEventListener('change', function() {
        if (this.value === 'eggs') {
            quantityUnit.textContent = 'pcs';
        } else if (this.value === 'meat') {
            quantityUnit.textContent = 'kg';
        } else {
            quantityUnit.textContent = '-';
        }
    });

    // Load sources when source type changes
    sourceTypeSelect.addEventListener('change', function() {
        const sourceType = this.value;
        
        if (sourceType) {
            sourceIdSelect.disabled = true;
            sourceIdSelect.innerHTML = '<option value="">Loading...</option>';
            
            fetch(`{{ route('sales.ajax.sources') }}?source_type=${sourceType}`)
                .then(response => response.json())
                .then(data => {
                    sourceIdSelect.innerHTML = '<option value="">Select source</option>';
                    data.forEach(source => {
                        sourceIdSelect.innerHTML += `<option value="${source.id}">${source.text}</option>`;
                    });
                    sourceIdSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    sourceIdSelect.innerHTML = '<option value="">Error loading sources</option>';
                    sourceIdSelect.disabled = false;
                });
        } else {
            sourceIdSelect.innerHTML = '<option value="">Select source type first</option>';
            sourceIdSelect.disabled = true;
        }
    });

    // Initialize quantity unit if item is already selected
    if (itemSelect.value) {
        itemSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
