@extends('layouts.app')

@section('title', 'Record Feed - ' . $batch->batch_id)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-seedling me-2 text-success"></i>Record Feed
                </h1>
                <p class="text-muted mb-0">
                    Batch: <strong>{{ $batch->batch_id }}</strong>
                    @if($batch->name) - {{ $batch->name }} @endif
                    <span class="mx-2">•</span>
                    <span class="badge bg-secondary">{{ $batch->age_display }}</span>
                    <span class="mx-2">•</span>
                    Current Count: <strong class="text-success">{{ number_format($batch->current_count) }}</strong>
                </p>
            </div>
            <a href="{{ route('batches.show', $batch) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Batch
            </a>
        </div>

        <!-- Feed Form -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-seedling me-2"></i>Feed Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('batches.feed.store', $batch) }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Feed Date -->
                        <div class="col-md-6 mb-3">
                            <label for="feed_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Feed Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('feed_date') is-invalid @enderror" 
                                   id="feed_date" 
                                   name="feed_date" 
                                   value="{{ old('feed_date', date('Y-m-d')) }}" 
                                   min="{{ $batch->start_date->format('Y-m-d') }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('feed_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Auto-calculated Age -->
                        <div class="col-md-6 mb-3">
                            <label for="age_display" class="form-label">
                                <i class="fas fa-clock me-1"></i>Age at Feed Date
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="age_display" 
                                   readonly
                                   placeholder="Select feed date to calculate age">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Feed Type -->
                        <div class="col-md-6 mb-3">
                            <label for="feed_type" class="form-label">
                                <i class="fas fa-tag me-1"></i>Feed Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('feed_type') is-invalid @enderror" 
                                    id="feed_type" 
                                    name="feed_type" 
                                    required>
                                <option value="">Select Feed Type</option>
                                @if($batch->animal_type === 'broiler')
                                    <option value="Starter Feed" {{ old('feed_type') == 'Starter Feed' ? 'selected' : '' }}>
                                        🌱 Starter Feed (0-10 days)
                                    </option>
                                    <option value="Grower Feed" {{ old('feed_type') == 'Grower Feed' ? 'selected' : '' }}>
                                        🌿 Grower Feed (11-24 days)
                                    </option>
                                    <option value="Finisher Feed" {{ old('feed_type') == 'Finisher Feed' ? 'selected' : '' }}>
                                        🌾 Finisher Feed (25+ days)
                                    </option>
                                @elseif($batch->animal_type === 'layer')
                                    <option value="Chick Starter" {{ old('feed_type') == 'Chick Starter' ? 'selected' : '' }}>
                                        🐣 Chick Starter (0-6 weeks)
                                    </option>
                                    <option value="Grower Feed" {{ old('feed_type') == 'Grower Feed' ? 'selected' : '' }}>
                                        🌿 Grower Feed (7-18 weeks)
                                    </option>
                                    <option value="Layer Feed" {{ old('feed_type') == 'Layer Feed' ? 'selected' : '' }}>
                                        🥚 Layer Feed (19+ weeks)
                                    </option>
                                @else
                                    <option value="Starter Feed" {{ old('feed_type') == 'Starter Feed' ? 'selected' : '' }}>
                                        🌱 Starter Feed
                                    </option>
                                    <option value="Grower Feed" {{ old('feed_type') == 'Grower Feed' ? 'selected' : '' }}>
                                        🌿 Grower Feed
                                    </option>
                                    <option value="Finisher Feed" {{ old('feed_type') == 'Finisher Feed' ? 'selected' : '' }}>
                                        🌾 Finisher Feed
                                    </option>
                                    <option value="Concentrate" {{ old('feed_type') == 'Concentrate' ? 'selected' : '' }}>
                                        ⚡ Concentrate
                                    </option>
                                    <option value="Hay/Grass" {{ old('feed_type') == 'Hay/Grass' ? 'selected' : '' }}>
                                        🌿 Hay/Grass
                                    </option>
                                @endif
                                <option value="Supplement" {{ old('feed_type') == 'Supplement' ? 'selected' : '' }}>
                                    💊 Supplement
                                </option>
                                <option value="Other" {{ old('feed_type') == 'Other' ? 'selected' : '' }}>
                                    📦 Other
                                </option>
                            </select>
                            @error('feed_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Feed Type (shown when "Other" is selected) -->
                        <div class="col-md-6 mb-3" id="custom-feed-type" style="display: none;">
                            <label for="custom_feed_type" class="form-label">
                                <i class="fas fa-edit me-1"></i>Custom Feed Type
                            </label>
                            <input type="text" 
                                   class="form-control @error('custom_feed_type') is-invalid @enderror" 
                                   id="custom_feed_type" 
                                   name="custom_feed_type" 
                                   value="{{ old('custom_feed_type') }}" 
                                   placeholder="Enter custom feed type">
                            @error('custom_feed_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Unit (when not "Other") -->
                        <div class="col-md-6 mb-3" id="unit-field">
                            <label for="unit" class="form-label">
                                <i class="fas fa-balance-scale me-1"></i>Unit <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('unit') is-invalid @enderror" 
                                    id="unit" 
                                    name="unit" 
                                    required>
                                <option value="">Select Unit</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Grams (g)</option>
                                <option value="bags" {{ old('unit') == 'bags' ? 'selected' : '' }}>Bags</option>
                                <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>Tons</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quantity -->
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity') }}" 
                                   step="0.01"
                                   min="0.01"
                                   placeholder="e.g., 50"
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cost per Unit (Optional) -->
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_unit" class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Cost per Unit (Optional)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('cost_per_unit') is-invalid @enderror" 
                                       id="cost_per_unit" 
                                       name="cost_per_unit" 
                                       value="{{ old('cost_per_unit') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                            </div>
                            @error('cost_per_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Total Cost Display -->
                    <div class="row" id="total-cost-row" style="display: none;">
                        <div class="col-md-6 offset-md-6 mb-3">
                            <div class="alert alert-info">
                                <strong>Total Cost: $<span id="total-cost">0.00</span></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes (Optional)
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Additional notes about this feeding...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('batches.show', $batch) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Record Feed
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Feed Guidelines Card -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Feeding Guidelines for {{ ucfirst($batch->animal_type) }}
                </h6>
            </div>
            <div class="card-body">
                @if($batch->animal_type === 'broiler')
                    <ul class="mb-0">
                        <li><strong>Starter (0-10 days):</strong> 20-25g per bird per day</li>
                        <li><strong>Grower (11-24 days):</strong> 25-80g per bird per day</li>
                        <li><strong>Finisher (25+ days):</strong> 80-150g per bird per day</li>
                        <li><strong>Feed multiple times daily</strong> for better digestion</li>
                    </ul>
                @elseif($batch->animal_type === 'layer')
                    <ul class="mb-0">
                        <li><strong>Chick Starter (0-6 weeks):</strong> 15-25g per bird per day</li>
                        <li><strong>Grower (7-18 weeks):</strong> 25-80g per bird per day</li>
                        <li><strong>Layer (19+ weeks):</strong> 110-120g per bird per day</li>
                        <li><strong>Provide consistent access to clean water</strong></li>
                    </ul>
                @else
                    <ul class="mb-0">
                        <li>Check with your veterinarian for specific feeding guidelines</li>
                        <li>Monitor animal weight and adjust feed accordingly</li>
                        <li>Ensure clean water is always available</li>
                        <li>Keep feed in dry, pest-free storage</li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedDateInput = document.getElementById('feed_date');
    const ageDisplay = document.getElementById('age_display');
    const feedTypeSelect = document.getElementById('feed_type');
    const customFeedTypeDiv = document.getElementById('custom-feed-type');
    const unitField = document.getElementById('unit-field');
    const quantityInput = document.getElementById('quantity');
    const costPerUnitInput = document.getElementById('cost_per_unit');
    const totalCostRow = document.getElementById('total-cost-row');
    const totalCostSpan = document.getElementById('total-cost');
    
    const batchStartDate = new Date('{{ $batch->start_date->format("Y-m-d") }}');
    
    // Calculate and display age when date changes
    function updateAge() {
        if (feedDateInput.value) {
            const feedDate = new Date(feedDateInput.value);
            const diffTime = feedDate - batchStartDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays >= 0) {
                if (diffDays < 30) {
                    ageDisplay.value = `${diffDays} days old`;
                } else if (diffDays < 365) {
                    const weeks = Math.floor(diffDays / 7);
                    const remainingDays = diffDays % 7;
                    ageDisplay.value = `${weeks} weeks${remainingDays > 0 ? ' ' + remainingDays + ' days' : ''} old`;
                } else {
                    const years = Math.floor(diffDays / 365);
                    const remainingDays = diffDays % 365;
                    const months = Math.floor(remainingDays / 30);
                    ageDisplay.value = `${years} year${years > 1 ? 's' : ''}${months > 0 ? ' ' + months + ' month' + (months > 1 ? 's' : '') : ''} old`;
                }
            } else {
                ageDisplay.value = 'Invalid date (before batch start)';
            }
        } else {
            ageDisplay.value = '';
        }
    }
    
    // Show/hide custom feed type field
    function toggleCustomFeedType() {
        if (feedTypeSelect.value === 'Other') {
            customFeedTypeDiv.style.display = 'block';
            unitField.style.display = 'none';
        } else {
            customFeedTypeDiv.style.display = 'none';
            unitField.style.display = 'block';
        }
    }
    
    // Calculate total cost
    function updateTotalCost() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const costPerUnit = parseFloat(costPerUnitInput.value) || 0;
        const totalCost = quantity * costPerUnit;
        
        if (costPerUnit > 0 && quantity > 0) {
            totalCostSpan.textContent = totalCost.toFixed(2);
            totalCostRow.style.display = 'block';
        } else {
            totalCostRow.style.display = 'none';
        }
    }
    
    // Event listeners
    feedDateInput.addEventListener('change', updateAge);
    feedTypeSelect.addEventListener('change', toggleCustomFeedType);
    quantityInput.addEventListener('input', updateTotalCost);
    costPerUnitInput.addEventListener('input', updateTotalCost);
    
    // Initialize
    updateAge();
    toggleCustomFeedType();
    updateTotalCost();
});
</script>
@endsection
