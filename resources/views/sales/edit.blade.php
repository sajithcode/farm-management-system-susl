@extends('layouts.app')

@section('title', 'Edit Sale')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit me-2 text-warning"></i>Edit Sale</h1>
    <div>
        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-2"></i>View
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Sales
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Sale Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales.update', $sale) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Sale Date *</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', $sale->date->format('Y-m-d')) }}" required>
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
                                    <option value="eggs" {{ old('item', $sale->item) === 'eggs' ? 'selected' : '' }}>
                                        🥚 Eggs
                                    </option>
                                    <option value="meat" {{ old('item', $sale->item) === 'meat' ? 'selected' : '' }}>
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
                                    <option value="batch" {{ old('source_type', $sale->source_type) === 'batch' ? 'selected' : '' }}>
                                        Batch
                                    </option>
                                    <option value="individual_animal" {{ old('source_type', $sale->source_type) === 'individual_animal' ? 'selected' : '' }}>
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
                                        id="source_id" name="source_id" required>
                                    <option value="">Loading...</option>
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
                                           id="quantity" name="quantity" value="{{ old('quantity', $sale->quantity) }}" required>
                                    <span class="input-group-text" id="quantity-unit">{{ $sale->item === 'eggs' ? 'pcs' : 'kg' }}</span>
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
                                           id="price" name="price" value="{{ old('price', $sale->price) }}" required>
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
                               id="buyer" name="buyer" value="{{ old('buyer', $sale->buyer) }}" 
                               placeholder="Enter buyer name (optional)">
                        @error('buyer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Additional notes about the sale (optional)">{{ old('notes', $sale->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="fw-bold">Current Item:</td>
                        <td>
                            <span class="badge bg-{{ $sale->item === 'eggs' ? 'warning' : 'danger' }} text-dark">
                                {{ ucfirst($sale->item) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Current Source:</td>
                        <td>{{ $sale->source_name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Current Quantity:</td>
                        <td>
                            {{ number_format($sale->quantity, 2) }}
                            {{ $sale->item === 'eggs' ? 'pcs' : 'kg' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Current Price:</td>
                        <td class="text-success">${{ number_format($sale->price, 2) }}</td>
                    </tr>
                </table>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> Be careful when changing the source or quantities.
                </div>
            </div>
        </div>

        <div class="card mt-3">
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
    const currentSourceId = {{ $sale->source_id }};

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
    function loadSources(sourceType, selectedId = null) {
        if (sourceType) {
            sourceIdSelect.disabled = true;
            sourceIdSelect.innerHTML = '<option value="">Loading...</option>';
            
            fetch(`{{ route('sales.ajax.sources') }}?source_type=${sourceType}`)
                .then(response => response.json())
                .then(data => {
                    sourceIdSelect.innerHTML = '<option value="">Select source</option>';
                    data.forEach(source => {
                        const selected = selectedId && parseInt(selectedId) === parseInt(source.id) ? 'selected' : '';
                        sourceIdSelect.innerHTML += `<option value="${source.id}" ${selected}>${source.text}</option>`;
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
    }

    sourceTypeSelect.addEventListener('change', function() {
        loadSources(this.value);
    });

    // Initialize on page load
    if (sourceTypeSelect.value) {
        loadSources(sourceTypeSelect.value, currentSourceId);
    }
});
</script>
@endsection
