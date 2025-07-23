@extends('layouts.app')

@section('title', 'Record Slaughter - ' . $individualAnimal->animal_id)

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-cut me-2 text-dark"></i>Record Animal Slaughter
        </h1>
        <p class="text-muted mb-0">
            Record slaughter for <strong>{{ $individualAnimal->animal_id }}</strong> 
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
    <strong>Warning:</strong> This animal is already marked as {{ $individualAnimal->status }}. You cannot record slaughter for animals that are not alive.
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

<!-- Slaughter Record Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-dark">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Record Slaughter Information
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Once you record a slaughter, this animal's status will be permanently changed to "slaughtered" and you will not be able to add feed records or other activities.
                </div>
                
                <form action="{{ route('individual-animals.slaughter.store', $individualAnimal) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="slaughter_date" class="form-label">
                                <i class="fas fa-calendar-day me-1"></i>Slaughter Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('slaughter_date') is-invalid @enderror" 
                                   id="slaughter_date" 
                                   name="slaughter_date" 
                                   value="{{ old('slaughter_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   min="{{ $individualAnimal->date_of_birth->format('Y-m-d') }}"
                                   required>
                            @error('slaughter_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Slaughter date cannot be before birth date or in the future</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="purpose" class="form-label">
                                <i class="fas fa-target me-1"></i>Purpose of Slaughter <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('purpose') is-invalid @enderror" id="purpose" name="purpose" required>
                                <option value="">Select purpose</option>
                                <option value="meat_production" {{ old('purpose') === 'meat_production' ? 'selected' : '' }}>Meat Production</option>
                                <option value="breeding_retirement" {{ old('purpose') === 'breeding_retirement' ? 'selected' : '' }}>Breeding Retirement</option>
                                <option value="health_issues" {{ old('purpose') === 'health_issues' ? 'selected' : '' }}>Health Issues</option>
                                <option value="age_related" {{ old('purpose') === 'age_related' ? 'selected' : '' }}>Age Related</option>
                                <option value="injury" {{ old('purpose') === 'injury' ? 'selected' : '' }}>Injury</option>
                                <option value="poor_performance" {{ old('purpose') === 'poor_performance' ? 'selected' : '' }}>Poor Performance</option>
                                <option value="surplus_reduction" {{ old('purpose') === 'surplus_reduction' ? 'selected' : '' }}>Surplus Reduction</option>
                                <option value="emergency" {{ old('purpose') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="other" {{ old('purpose') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="live_weight" class="form-label">
                                <i class="fas fa-weight me-1"></i>Live Weight (kg)
                            </label>
                            <input type="number" 
                                   class="form-control @error('live_weight') is-invalid @enderror" 
                                   id="live_weight" 
                                   name="live_weight" 
                                   value="{{ old('live_weight', $individualAnimal->current_weight) }}"
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                            @error('live_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Weight of the animal before slaughter</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="dressed_weight" class="form-label">
                                <i class="fas fa-balance-scale me-1"></i>Dressed Weight (kg)
                            </label>
                            <input type="number" 
                                   class="form-control @error('dressed_weight') is-invalid @enderror" 
                                   id="dressed_weight" 
                                   name="dressed_weight" 
                                   value="{{ old('dressed_weight') }}"
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                            @error('dressed_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Weight after processing (carcass weight)</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="medicine_used" class="form-label">
                            <i class="fas fa-pills me-1"></i>Medicine/Drugs Used
                        </label>
                        <textarea class="form-control @error('medicine_used') is-invalid @enderror" 
                                  id="medicine_used" 
                                  name="medicine_used" 
                                  rows="2"
                                  placeholder="List any medications, antibiotics, or drugs administered before slaughter">{{ old('medicine_used') }}</textarea>
                        @error('medicine_used')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Important for food safety and withdrawal periods</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Slaughter Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4"
                                  placeholder="Additional notes about the slaughter (condition of animal, processing details, quality observations, etc.)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Include any relevant details about the slaughter process or meat quality</div>
                    </div>
                    
                    <!-- Age and Yield Calculation Display -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <strong>Age at Slaughter:</strong> <span id="age-at-slaughter">{{ $individualAnimal->age_display }}</span>
                                <br><small>Based on birth date and slaughter date</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success" id="yield-calculation" style="display: none;">
                                <strong>Dressing Yield:</strong> <span id="dressing-yield">0%</span>
                                <br><small>Dressed weight ÷ Live weight × 100</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirmation Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('confirm_slaughter') is-invalid @enderror" 
                                   type="checkbox" 
                                   id="confirm_slaughter" 
                                   name="confirm_slaughter" 
                                   value="1"
                                   {{ old('confirm_slaughter') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="confirm_slaughter">
                                <strong>I confirm that this animal has been slaughtered and understand this action cannot be undone</strong>
                            </label>
                            @error('confirm_slaughter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('individual-animals.show', $individualAnimal) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-dark" id="submit-btn" disabled>
                            <i class="fas fa-cut me-2"></i>Record Slaughter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Animal Summary -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Animal Summary
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Animal ID:</strong></td>
                        <td>{{ $individualAnimal->animal_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>{{ ucfirst($individualAnimal->animal_type) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gender:</strong></td>
                        <td>
                            @if($individualAnimal->gender !== 'unknown')
                                {{ ucfirst($individualAnimal->gender) }}
                                <i class="fas fa-{{ $individualAnimal->gender === 'male' ? 'mars' : 'venus' }} ms-1"></i>
                            @else
                                Unknown
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Birth Date:</strong></td>
                        <td>{{ $individualAnimal->date_of_birth->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Current Age:</strong></td>
                        <td>{{ $individualAnimal->age_display }}</td>
                    </tr>
                    <tr>
                        <td><strong>Days Alive:</strong></td>
                        <td>{{ $individualAnimal->age_in_days }} days</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="badge {{ $individualAnimal->status_badge }}">{{ ucfirst($individualAnimal->status) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Production Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Production Statistics
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-seedling me-2 text-success"></i>Feed Records:</span>
                        <strong>{{ $individualAnimal->feedRecords->count() }}</strong>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-weight me-2 text-info"></i>Total Feed Given:</span>
                        <strong>{{ number_format($individualAnimal->feedRecords->sum('quantity'), 2) }} kg</strong>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-dollar-sign me-2 text-warning"></i>Feed Cost:</span>
                        <strong>
                            ${{ number_format($individualAnimal->feedRecords->sum(function($feed) {
                                return $feed->quantity * ($feed->cost_per_unit ?: 0);
                            }), 2) }}
                        </strong>
                    </li>
                    @if($individualAnimal->current_weight)
                    <li class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-balance-scale me-2 text-primary"></i>Current Weight:</span>
                        <strong>{{ number_format($individualAnimal->current_weight, 1) }} kg</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span><i class="fas fa-calculator me-2 text-secondary"></i>Feed Conversion:</span>
                        <strong>
                            @if($individualAnimal->current_weight > 0)
                                {{ number_format($individualAnimal->feedRecords->sum('quantity') / $individualAnimal->current_weight, 2) }}:1
                            @else
                                N/A
                            @endif
                        </strong>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Slaughter Guidelines -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Slaughter Guidelines
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Record accurate live and dressed weights</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Document any medicine withdrawal periods</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Include quality observations</small>
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Follow food safety protocols</small>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Important Notes -->
        <div class="card mt-3 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Important
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-ban text-danger me-2"></i>
                        <small>This action cannot be undone</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-lock text-warning me-2"></i>
                        <small>No more activities can be recorded</small>
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-archive text-info me-2"></i>
                        <small>Animal will be archived as slaughtered</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slaughterDateInput = document.getElementById('slaughter_date');
    const liveWeightInput = document.getElementById('live_weight');
    const dressedWeightInput = document.getElementById('dressed_weight');
    const ageAtSlaughterSpan = document.getElementById('age-at-slaughter');
    const yieldCalculation = document.getElementById('yield-calculation');
    const dressingYieldSpan = document.getElementById('dressing-yield');
    const confirmCheckbox = document.getElementById('confirm_slaughter');
    const submitBtn = document.getElementById('submit-btn');
    const birthDate = new Date('{{ $individualAnimal->date_of_birth->format('Y-m-d') }}');
    
    function calculateAge(slaughterDate) {
        const birth = new Date(birthDate);
        const slaughter = new Date(slaughterDate);
        const diffTime = slaughter - birth;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays < 0) {
            return 'Invalid date';
        } else if (diffDays < 30) {
            return diffDays + ' days';
        } else if (diffDays < 365) {
            const months = Math.floor(diffDays / 30);
            const days = diffDays % 30;
            return months + ' month' + (months !== 1 ? 's' : '') + 
                   (days > 0 ? ' ' + days + ' day' + (days !== 1 ? 's' : '') : '');
        } else {
            const years = Math.floor(diffDays / 365);
            const remainingDays = diffDays % 365;
            const months = Math.floor(remainingDays / 30);
            const days = remainingDays % 30;
            
            let ageString = years + ' year' + (years !== 1 ? 's' : '');
            if (months > 0) {
                ageString += ' ' + months + ' month' + (months !== 1 ? 's' : '');
            }
            if (days > 0) {
                ageString += ' ' + days + ' day' + (days !== 1 ? 's' : '');
            }
            return ageString;
        }
    }
    
    function updateAgeDisplay() {
        if (slaughterDateInput.value) {
            const ageDisplay = calculateAge(slaughterDateInput.value);
            ageAtSlaughterSpan.textContent = ageDisplay;
        }
    }
    
    function updateYieldCalculation() {
        const liveWeight = parseFloat(liveWeightInput.value) || 0;
        const dressedWeight = parseFloat(dressedWeightInput.value) || 0;
        
        if (liveWeight > 0 && dressedWeight > 0) {
            const yield = (dressedWeight / liveWeight) * 100;
            dressingYieldSpan.textContent = yield.toFixed(1) + '%';
            yieldCalculation.style.display = 'block';
        } else {
            yieldCalculation.style.display = 'none';
        }
    }
    
    function updateSubmitButton() {
        submitBtn.disabled = !confirmCheckbox.checked;
    }
    
    slaughterDateInput.addEventListener('change', updateAgeDisplay);
    liveWeightInput.addEventListener('input', updateYieldCalculation);
    dressedWeightInput.addEventListener('input', updateYieldCalculation);
    confirmCheckbox.addEventListener('change', updateSubmitButton);
    
    // Initial updates
    updateAgeDisplay();
    updateYieldCalculation();
    updateSubmitButton();
});
</script>
@endsection
