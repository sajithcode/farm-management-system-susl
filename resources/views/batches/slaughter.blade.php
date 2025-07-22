@extends('layouts.app')

@section('title', 'Record Slaughter - ' . $batch->batch_id)

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-cut me-2 text-dark"></i>Record Slaughter
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

        @if($batch->current_count <= 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Warning:</strong> This batch has no animals left to slaughter.
        </div>
        @endif

        <!-- Slaughter Form -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cut me-2"></i>Slaughter Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('batches.slaughter.store', $batch) }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Slaughter Date -->
                        <div class="col-md-6 mb-3">
                            <label for="slaughter_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Slaughter Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('slaughter_date') is-invalid @enderror" 
                                   id="slaughter_date" 
                                   name="slaughter_date" 
                                   value="{{ old('slaughter_date', date('Y-m-d')) }}" 
                                   min="{{ $batch->start_date->format('Y-m-d') }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('slaughter_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Auto-calculated Age -->
                        <div class="col-md-6 mb-3">
                            <label for="age_display" class="form-label">
                                <i class="fas fa-clock me-1"></i>Age at Slaughter
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="age_display" 
                                   readonly
                                   placeholder="Select slaughter date to calculate age">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Count -->
                        <div class="col-md-6 mb-3">
                            <label for="count" class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>Number to Slaughter <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('count') is-invalid @enderror" 
                                   id="count" 
                                   name="count" 
                                   value="{{ old('count') }}" 
                                   min="1"
                                   max="{{ $batch->current_count }}"
                                   placeholder="e.g., 100"
                                   required>
                            @error('count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Maximum: {{ number_format($batch->current_count) }} animals available
                            </div>
                        </div>

                        <!-- Average Weight -->
                        <div class="col-md-6 mb-3">
                            <label for="average_weight" class="form-label">
                                <i class="fas fa-weight me-1"></i>Average Weight per Animal
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('average_weight') is-invalid @enderror" 
                                       id="average_weight" 
                                       name="average_weight" 
                                       value="{{ old('average_weight') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                                <span class="input-group-text">kg</span>
                            </div>
                            @error('average_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Total Weight -->
                        <div class="col-md-6 mb-3">
                            <label for="total_weight" class="form-label">
                                <i class="fas fa-balance-scale me-1"></i>Total Weight
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('total_weight') is-invalid @enderror" 
                                       id="total_weight" 
                                       name="total_weight" 
                                       value="{{ old('total_weight') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                                <span class="input-group-text">kg</span>
                            </div>
                            @error('total_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" id="weight-calculation" style="display: none;">
                                Auto-calculated from count × average weight
                            </div>
                        </div>

                        <!-- Price per kg -->
                        <div class="col-md-6 mb-3">
                            <label for="price_per_kg" class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Price per kg
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('price_per_kg') is-invalid @enderror" 
                                       id="price_per_kg" 
                                       name="price_per_kg" 
                                       value="{{ old('price_per_kg') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                            </div>
                            @error('price_per_kg')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Revenue Summary -->
                    <div class="row" id="revenue-summary" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-success">
                                <h6><i class="fas fa-calculator me-2"></i>Revenue Summary</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Total Weight:</strong> <span id="summary-weight">0</span> kg
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Price per kg:</strong> $<span id="summary-price">0.00</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total Revenue:</strong> $<span id="summary-revenue">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Buyer/Customer -->
                        <div class="col-md-6 mb-3">
                            <label for="buyer" class="form-label">
                                <i class="fas fa-user-tie me-1"></i>Buyer/Customer
                            </label>
                            <input type="text" 
                                   class="form-control @error('buyer') is-invalid @enderror" 
                                   id="buyer" 
                                   name="buyer" 
                                   value="{{ old('buyer') }}" 
                                   placeholder="e.g., ABC Meat Market">
                            @error('buyer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slaughter Location -->
                        <div class="col-md-6 mb-3">
                            <label for="slaughter_location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Slaughter Location
                            </label>
                            <input type="text" 
                                   class="form-control @error('slaughter_location') is-invalid @enderror" 
                                   id="slaughter_location" 
                                   name="slaughter_location" 
                                   value="{{ old('slaughter_location') }}" 
                                   placeholder="e.g., On-Farm, Local Abattoir">
                            @error('slaughter_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                  placeholder="Additional notes about the slaughter, quality grades, processing details, etc.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Impact Summary -->
                    <div class="row" id="impact-summary" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Batch Impact</h6>
                                <ul class="mb-0">
                                    <li>Current count: <strong>{{ number_format($batch->current_count) }}</strong></li>
                                    <li>After slaughter: <strong><span id="new-count">-</span></strong></li>
                                    <li>Total harvested from batch: <strong><span id="total-harvested">-</span>%</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('batches.show', $batch) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-dark" id="submit-btn" {{ $batch->current_count <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i>Record Slaughter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Market Info Card -->
        <div class="card mt-4 border-success">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Market Information for {{ ucfirst($batch->animal_type) }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($batch->animal_type === 'broiler')
                            <h6><i class="fas fa-weight me-2 text-info"></i>Typical Weights</h6>
                            <ul>
                                <li><strong>6-7 weeks:</strong> 1.8-2.5 kg live weight</li>
                                <li><strong>8-9 weeks:</strong> 2.5-3.5 kg live weight</li>
                                <li><strong>Dressed weight:</strong> ~75% of live weight</li>
                            </ul>
                        @elseif($batch->animal_type === 'layer')
                            <h6><i class="fas fa-weight me-2 text-info"></i>Typical Weights</h6>
                            <ul>
                                <li><strong>Spent layers:</strong> 1.5-2.0 kg live weight</li>
                                <li><strong>Roosters:</strong> 2.0-3.0 kg live weight</li>
                                <li><strong>Dressed weight:</strong> ~70% of live weight</li>
                            </ul>
                        @else
                            <h6><i class="fas fa-weight me-2 text-info"></i>Weight Guidelines</h6>
                            <ul>
                                <li>Check local market standards</li>
                                <li>Consider seasonal price variations</li>
                                <li>Factor in processing costs</li>
                            </ul>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-dollar-sign me-2 text-success"></i>Pricing Tips</h6>
                        <ul>
                            <li>Check current market prices</li>
                            <li>Consider grade/quality premiums</li>
                            <li>Factor in transportation costs</li>
                            <li>Negotiate bulk pricing if applicable</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <strong>💡 Pro Tip:</strong> Keep detailed records for better price negotiations and to track profitability over time.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slaughterDateInput = document.getElementById('slaughter_date');
    const ageDisplay = document.getElementById('age_display');
    const countInput = document.getElementById('count');
    const averageWeightInput = document.getElementById('average_weight');
    const totalWeightInput = document.getElementById('total_weight');
    const pricePerKgInput = document.getElementById('price_per_kg');
    const weightCalculation = document.getElementById('weight-calculation');
    const revenueSummary = document.getElementById('revenue-summary');
    const impactSummary = document.getElementById('impact-summary');
    
    const batchStartDate = new Date('{{ $batch->start_date->format("Y-m-d") }}');
    const currentCount = {{ $batch->current_count }};
    const initialCount = {{ $batch->initial_count }};
    const totalSlaughtered = {{ $batch->slaughterRecords->sum('count') }};
    
    // Calculate and display age when date changes
    function updateAge() {
        if (slaughterDateInput.value) {
            const slaughterDate = new Date(slaughterDateInput.value);
            const diffTime = slaughterDate - batchStartDate;
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
    
    // Auto-calculate total weight from count and average weight
    function updateTotalWeight() {
        const count = parseInt(countInput.value) || 0;
        const avgWeight = parseFloat(averageWeightInput.value) || 0;
        
        if (count > 0 && avgWeight > 0) {
            const calculatedTotal = count * avgWeight;
            
            // Only update if user hasn't manually entered a different value
            if (!totalWeightInput.value || Math.abs(parseFloat(totalWeightInput.value) - calculatedTotal) < 0.01) {
                totalWeightInput.value = calculatedTotal.toFixed(2);
                weightCalculation.style.display = 'block';
            }
        } else {
            weightCalculation.style.display = 'none';
        }
        
        updateRevenueSummary();
        updateImpactSummary();
    }
    
    // Update revenue summary
    function updateRevenueSummary() {
        const totalWeight = parseFloat(totalWeightInput.value) || 0;
        const pricePerKg = parseFloat(pricePerKgInput.value) || 0;
        
        if (totalWeight > 0 && pricePerKg > 0) {
            const totalRevenue = totalWeight * pricePerKg;
            
            document.getElementById('summary-weight').textContent = totalWeight.toFixed(2);
            document.getElementById('summary-price').textContent = pricePerKg.toFixed(2);
            document.getElementById('summary-revenue').textContent = totalRevenue.toFixed(2);
            revenueSummary.style.display = 'block';
        } else {
            revenueSummary.style.display = 'none';
        }
    }
    
    // Update impact summary
    function updateImpactSummary() {
        const slaughterCount = parseInt(countInput.value) || 0;
        
        if (slaughterCount > 0) {
            const newCount = currentCount - slaughterCount;
            const totalHarvestedCount = totalSlaughtered + slaughterCount;
            const harvestedPercentage = initialCount > 0 ? (totalHarvestedCount / initialCount) * 100 : 0;
            
            document.getElementById('new-count').textContent = newCount.toLocaleString();
            document.getElementById('total-harvested').textContent = harvestedPercentage.toFixed(1);
            impactSummary.style.display = 'block';
        } else {
            impactSummary.style.display = 'none';
        }
    }
    
    // Event listeners
    slaughterDateInput.addEventListener('change', updateAge);
    countInput.addEventListener('input', updateTotalWeight);
    averageWeightInput.addEventListener('input', updateTotalWeight);
    totalWeightInput.addEventListener('input', updateRevenueSummary);
    pricePerKgInput.addEventListener('input', updateRevenueSummary);
    
    // Prevent total weight auto-calculation when user manually types
    totalWeightInput.addEventListener('focus', function() {
        weightCalculation.style.display = 'none';
    });
    
    // Initialize
    updateAge();
    updateTotalWeight();
    updateRevenueSummary();
    updateImpactSummary();
});
</script>
@endsection
