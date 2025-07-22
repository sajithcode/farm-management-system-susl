@extends('layouts.app')

@section('title', 'Record Death - ' . $batch->batch_id)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-heart-broken me-2 text-danger"></i>Record Death
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
            <strong>Warning:</strong> This batch has no animals left to record deaths for.
        </div>
        @endif

        <!-- Death Form -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-heart-broken me-2"></i>Death Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('batches.death.store', $batch) }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Death Date -->
                        <div class="col-md-6 mb-3">
                            <label for="death_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Death Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('death_date') is-invalid @enderror" 
                                   id="death_date" 
                                   name="death_date" 
                                   value="{{ old('death_date', date('Y-m-d')) }}" 
                                   min="{{ $batch->start_date->format('Y-m-d') }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('death_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Auto-calculated Age -->
                        <div class="col-md-6 mb-3">
                            <label for="age_display" class="form-label">
                                <i class="fas fa-clock me-1"></i>Age at Death Date
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="age_display" 
                                   readonly
                                   placeholder="Select death date to calculate age">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Count -->
                        <div class="col-md-6 mb-3">
                            <label for="count" class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>Number of Deaths <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('count') is-invalid @enderror" 
                                   id="count" 
                                   name="count" 
                                   value="{{ old('count') }}" 
                                   min="1"
                                   max="{{ $batch->current_count }}"
                                   placeholder="e.g., 5"
                                   required>
                            @error('count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Maximum: {{ number_format($batch->current_count) }} animals available
                            </div>
                        </div>

                        <!-- Cause -->
                        <div class="col-md-6 mb-3">
                            <label for="cause" class="form-label">
                                <i class="fas fa-stethoscope me-1"></i>Cause of Death
                            </label>
                            <select class="form-select @error('cause') is-invalid @enderror" 
                                    id="cause" 
                                    name="cause">
                                <option value="">Select Cause (Optional)</option>
                                <option value="Disease" {{ old('cause') == 'Disease' ? 'selected' : '' }}>
                                    🦠 Disease
                                </option>
                                <option value="Predator Attack" {{ old('cause') == 'Predator Attack' ? 'selected' : '' }}>
                                    🐺 Predator Attack
                                </option>
                                <option value="Weather/Environmental" {{ old('cause') == 'Weather/Environmental' ? 'selected' : '' }}>
                                    🌡️ Weather/Environmental
                                </option>
                                <option value="Accident" {{ old('cause') == 'Accident' ? 'selected' : '' }}>
                                    ⚠️ Accident
                                </option>
                                <option value="Old Age" {{ old('cause') == 'Old Age' ? 'selected' : '' }}>
                                    👴 Old Age
                                </option>
                                <option value="Malnutrition" {{ old('cause') == 'Malnutrition' ? 'selected' : '' }}>
                                    🍽️ Malnutrition
                                </option>
                                <option value="Stress" {{ old('cause') == 'Stress' ? 'selected' : '' }}>
                                    😰 Stress
                                </option>
                                <option value="Unknown" {{ old('cause') == 'Unknown' ? 'selected' : '' }}>
                                    ❓ Unknown
                                </option>
                                <option value="Other" {{ old('cause') == 'Other' ? 'selected' : '' }}>
                                    📋 Other
                                </option>
                            </select>
                            @error('cause')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Custom Cause (shown when "Other" is selected) -->
                    <div class="row" id="custom-cause-row" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label for="custom_cause" class="form-label">
                                <i class="fas fa-edit me-1"></i>Custom Cause
                            </label>
                            <input type="text" 
                                   class="form-control @error('custom_cause') is-invalid @enderror" 
                                   id="custom_cause" 
                                   name="custom_cause" 
                                   value="{{ old('custom_cause') }}" 
                                   placeholder="Describe the cause of death">
                            @error('custom_cause')
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
                                  rows="4" 
                                  placeholder="Additional details about the deaths, symptoms observed, actions taken, preventive measures, etc.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Impact Summary -->
                    <div class="row" id="impact-summary" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-calculator me-2"></i>Impact Summary</h6>
                                <ul class="mb-0">
                                    <li>Current count: <strong>{{ number_format($batch->current_count) }}</strong></li>
                                    <li>After recording deaths: <strong><span id="new-count">-</span></strong></li>
                                    <li>New survival rate: <strong><span id="new-survival-rate">-</span></strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('batches.show', $batch) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-danger" id="submit-btn" {{ $batch->current_count <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i>Record Deaths
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Prevention Tips Card -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Death Prevention Tips
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-heartbeat me-2 text-success"></i>Health Monitoring</h6>
                        <ul>
                            <li>Daily health checks</li>
                            <li>Monitor eating and drinking</li>
                            <li>Watch for behavioral changes</li>
                            <li>Regular weight monitoring</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-home me-2 text-primary"></i>Environmental Control</h6>
                        <ul>
                            <li>Maintain proper temperature</li>
                            <li>Ensure adequate ventilation</li>
                            <li>Keep housing clean and dry</li>
                            <li>Provide adequate space</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <strong>⚠️ High Mortality Alert:</strong> If death rate exceeds 5% in a day or 10% total, contact a veterinarian immediately.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deathDateInput = document.getElementById('death_date');
    const ageDisplay = document.getElementById('age_display');
    const countInput = document.getElementById('count');
    const causeSelect = document.getElementById('cause');
    const customCauseRow = document.getElementById('custom-cause-row');
    const impactSummary = document.getElementById('impact-summary');
    const newCountSpan = document.getElementById('new-count');
    const newSurvivalRateSpan = document.getElementById('new-survival-rate');
    
    const batchStartDate = new Date('{{ $batch->start_date->format("Y-m-d") }}');
    const currentCount = {{ $batch->current_count }};
    const initialCount = {{ $batch->initial_count }};
    
    // Calculate and display age when date changes
    function updateAge() {
        if (deathDateInput.value) {
            const deathDate = new Date(deathDateInput.value);
            const diffTime = deathDate - batchStartDate;
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
    
    // Show/hide custom cause field
    function toggleCustomCause() {
        if (causeSelect.value === 'Other') {
            customCauseRow.style.display = 'block';
        } else {
            customCauseRow.style.display = 'none';
        }
    }
    
    // Update impact summary
    function updateImpactSummary() {
        const deathCount = parseInt(countInput.value) || 0;
        
        if (deathCount > 0) {
            const newCount = currentCount - deathCount;
            const newSurvivalRate = initialCount > 0 ? (newCount / initialCount) * 100 : 0;
            
            newCountSpan.textContent = newCount.toLocaleString();
            newSurvivalRateSpan.textContent = newSurvivalRate.toFixed(1) + '%';
            impactSummary.style.display = 'block';
        } else {
            impactSummary.style.display = 'none';
        }
    }
    
    // Event listeners
    deathDateInput.addEventListener('change', updateAge);
    causeSelect.addEventListener('change', toggleCustomCause);
    countInput.addEventListener('input', updateImpactSummary);
    
    // Initialize
    updateAge();
    toggleCustomCause();
    updateImpactSummary();
});
</script>
@endsection
