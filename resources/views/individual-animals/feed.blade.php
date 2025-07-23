@extends('layouts.app')

@section('title', 'Feed Animal - ' . $individualAnimal->animal_id)

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-seedling me-2 text-success"></i>Feed Animal
        </h1>
        <p class="text-muted mb-0">
            Record feeding for <strong>{{ $individualAnimal->animal_id }}</strong> 
            <span class="mx-2">•</span>
            <span class="badge {{ $individualAnimal->status_badge }}">{{ ucfirst($individualAnimal->status) }}</span>
        </p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('individual-animals.show', $individualAnimal) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Animal
        </a>
    </div>
</div>

@if($individualAnimal->status !== 'alive')
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Warning:</strong> This animal is marked as {{ $individualAnimal->status }}. You cannot add feed records for non-living animals.
</div>
@else

<!-- Animal Info Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Animal Information</h6>
                        <p class="mb-0">
                            <strong>{{ $individualAnimal->animal_id }}</strong>
                            @if($individualAnimal->gender !== 'unknown')
                                <i class="fas fa-{{ $individualAnimal->gender === 'male' ? 'mars' : 'venus' }} ms-1 text-{{ $individualAnimal->gender === 'male' ? 'primary' : 'danger' }}"></i>
                            @endif
                        </p>
                        <small class="text-muted">{{ ucfirst($individualAnimal->animal_type) }}</small>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Current Age</h6>
                        <p class="mb-0"><span class="badge bg-info">{{ $individualAnimal->age_display }}</span></p>
                        <small class="text-muted">Born {{ $individualAnimal->date_of_birth->format('M d, Y') }}</small>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Feed Records</h6>
                        <p class="mb-0"><strong>{{ $individualAnimal->feedRecords->count() }}</strong> records</p>
                        <small class="text-muted">{{ number_format($individualAnimal->feedRecords->sum('quantity'), 2) }} kg total</small>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Responsible Person</h6>
                        <p class="mb-0">{{ $individualAnimal->responsible_person }}</p>
                        @if($individualAnimal->current_weight)
                        <small class="text-muted">Current Weight: {{ number_format($individualAnimal->current_weight, 1) }} kg</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feed Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Add Feed Record
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('individual-animals.feed.store', $individualAnimal) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="feed_date" class="form-label">
                                <i class="fas fa-calendar-day me-1"></i>Feed Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('feed_date') is-invalid @enderror" 
                                   id="feed_date" 
                                   name="feed_date" 
                                   value="{{ old('feed_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   min="{{ $individualAnimal->date_of_birth->format('Y-m-d') }}"
                                   required>
                            @error('feed_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Feed date cannot be before birth date or in the future</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="feed_type" class="form-label">
                                <i class="fas fa-seedling me-1"></i>Feed Type <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('feed_type') is-invalid @enderror" 
                                   id="feed_type" 
                                   name="feed_type" 
                                   value="{{ old('feed_type') }}"
                                   placeholder="e.g., Starter Feed, Grower Feed, Finisher Feed"
                                   required>
                            @error('feed_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">
                                <i class="fas fa-weight me-1"></i>Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity') }}"
                                   step="0.01" 
                                   min="0.01"
                                   placeholder="0.00"
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label">
                                <i class="fas fa-balance-scale me-1"></i>Unit <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="g" {{ old('unit') === 'g' ? 'selected' : '' }}>Grams (g)</option>
                                <option value="lbs" {{ old('unit') === 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                                <option value="cups" {{ old('unit') === 'cups' ? 'selected' : '' }}>Cups</option>
                                <option value="scoops" {{ old('unit') === 'scoops' ? 'selected' : '' }}>Scoops</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="cost_per_unit" class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Cost per Unit
                            </label>
                            <input type="number" 
                                   class="form-control @error('cost_per_unit') is-invalid @enderror" 
                                   id="cost_per_unit" 
                                   name="cost_per_unit" 
                                   value="{{ old('cost_per_unit') }}"
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                            @error('cost_per_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Cost per unit of feed</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="administered_by" class="form-label">
                            <i class="fas fa-user me-1"></i>Administered By <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('administered_by') is-invalid @enderror" 
                               id="administered_by" 
                               name="administered_by" 
                               value="{{ old('administered_by', auth()->user()->name) }}"
                               placeholder="Name of person who administered the feed"
                               required>
                        @error('administered_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Optional notes about this feeding (e.g., animal behavior, appetite, etc.)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Cost Calculation Display -->
                    <div class="mb-3" id="cost-calculation" style="display: none;">
                        <div class="alert alert-info">
                            <strong>Total Cost: $<span id="total-cost">0.00</span></strong>
                            <br><small>Quantity × Cost per Unit</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('individual-animals.show', $individualAnimal) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Record Feed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Recent Feed Records -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Feed Records
                </h6>
            </div>
            <div class="card-body">
                @if($individualAnimal->feedRecords->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($individualAnimal->feedRecords->sortByDesc('feed_date')->take(5) as $feed)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $feed->feed_type }}</h6>
                                <p class="mb-1 text-muted small">
                                    {{ number_format($feed->quantity, 2) }} {{ $feed->unit }}
                                    @if($feed->cost_per_unit)
                                        • ${{ number_format($feed->cost_per_unit * $feed->quantity, 2) }}
                                    @endif
                                </p>
                                <small class="text-muted">{{ $feed->feed_date->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($individualAnimal->feedRecords->count() > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('individual-animals.show', $individualAnimal) }}#feed" class="btn btn-sm btn-outline-primary">
                        View All Records
                    </a>
                </div>
                @endif
                @else
                <div class="text-center py-3">
                    <i class="fas fa-seedling fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No feed records yet</p>
                    <small class="text-muted">This will be the first feed record for this animal.</small>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Feed Guidelines -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Feeding Guidelines
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Record feeds daily for accurate tracking</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Use consistent feed types and units</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Include cost information for budgeting</small>
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Note any unusual behavior or appetite</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const costPerUnitInput = document.getElementById('cost_per_unit');
    const costCalculation = document.getElementById('cost-calculation');
    const totalCostSpan = document.getElementById('total-cost');
    
    function updateCostCalculation() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const costPerUnit = parseFloat(costPerUnitInput.value) || 0;
        const totalCost = quantity * costPerUnit;
        
        if (quantity > 0 && costPerUnit > 0) {
            totalCostSpan.textContent = totalCost.toFixed(2);
            costCalculation.style.display = 'block';
        } else {
            costCalculation.style.display = 'none';
        }
    }
    
    quantityInput.addEventListener('input', updateCostCalculation);
    costPerUnitInput.addEventListener('input', updateCostCalculation);
    
    // Initial calculation
    updateCostCalculation();
});
</script>
@endsection
