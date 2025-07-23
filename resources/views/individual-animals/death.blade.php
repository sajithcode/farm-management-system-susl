@extends('layouts.app')

@section('title', 'Record Death - ' . $individualAnimal->animal_id)

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-heart-broken me-2 text-danger"></i>Record Animal Death
        </h1>
        <p class="text-muted mb-0">
            Record death for <strong>{{ $individualAnimal->animal_id }}</strong> 
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
    <strong>Warning:</strong> This animal is already marked as {{ $individualAnimal->status }}. You cannot record death for animals that are not alive.
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

<!-- Death Record Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Record Death Information
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Once you record a death, this animal's status will be permanently changed to "dead" and you will not be able to add feed records or other activities.
                </div>
                
                <form action="{{ route('individual-animals.death.store', $individualAnimal) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="death_date" class="form-label">
                                <i class="fas fa-calendar-day me-1"></i>Death Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('death_date') is-invalid @enderror" 
                                   id="death_date" 
                                   name="death_date" 
                                   value="{{ old('death_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   min="{{ $individualAnimal->date_of_birth->format('Y-m-d') }}"
                                   required>
                            @error('death_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Death date cannot be before birth date or in the future</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">
                                <i class="fas fa-weight me-1"></i>Weight at Death (kg)
                            </label>
                            <input type="number" 
                                   class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight', $individualAnimal->current_weight) }}"
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Final weight of the animal</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cause" class="form-label">
                            <i class="fas fa-stethoscope me-1"></i>Cause of Death
                        </label>
                        <select class="form-select @error('cause') is-invalid @enderror" id="cause" name="cause">
                            <option value="">Select cause (optional)</option>
                            <option value="disease" {{ old('cause') === 'disease' ? 'selected' : '' }}>Disease</option>
                            <option value="injury" {{ old('cause') === 'injury' ? 'selected' : '' }}>Injury</option>
                            <option value="old_age" {{ old('cause') === 'old_age' ? 'selected' : '' }}>Old Age</option>
                            <option value="predator_attack" {{ old('cause') === 'predator_attack' ? 'selected' : '' }}>Predator Attack</option>
                            <option value="accident" {{ old('cause') === 'accident' ? 'selected' : '' }}>Accident</option>
                            <option value="poisoning" {{ old('cause') === 'poisoning' ? 'selected' : '' }}>Poisoning</option>
                            <option value="malnutrition" {{ old('cause') === 'malnutrition' ? 'selected' : '' }}>Malnutrition</option>
                            <option value="stress" {{ old('cause') === 'stress' ? 'selected' : '' }}>Stress</option>
                            <option value="unknown" {{ old('cause') === 'unknown' ? 'selected' : '' }}>Unknown</option>
                            <option value="other" {{ old('cause') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('cause')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Death Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4"
                                  placeholder="Detailed notes about the death (symptoms observed, circumstances, veterinary findings, etc.)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Include any relevant details that might help with future prevention or record keeping</div>
                    </div>
                    
                    <!-- Age Calculation Display -->
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <strong>Age at Death:</strong> <span id="age-at-death">{{ $individualAnimal->age_display }}</span>
                            <br><small>Based on birth date and death date</small>
                        </div>
                    </div>
                    
                    <!-- Confirmation Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('confirm_death') is-invalid @enderror" 
                                   type="checkbox" 
                                   id="confirm_death" 
                                   name="confirm_death" 
                                   value="1"
                                   {{ old('confirm_death') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="confirm_death">
                                <strong>I confirm that this animal has died and understand this action cannot be undone</strong>
                            </label>
                            @error('confirm_death')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('individual-animals.show', $individualAnimal) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-danger" id="submit-btn" disabled>
                            <i class="fas fa-heart-broken me-2"></i>Record Death
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
        
        <!-- Lifecycle Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Lifecycle Statistics
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
                    <li class="d-flex justify-content-between">
                        <span><i class="fas fa-balance-scale me-2 text-primary"></i>Current Weight:</span>
                        <strong>{{ number_format($individualAnimal->current_weight, 1) }} kg</strong>
                    </li>
                    @endif
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
                        <small>Animal will be archived as deceased</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deathDateInput = document.getElementById('death_date');
    const ageAtDeathSpan = document.getElementById('age-at-death');
    const confirmCheckbox = document.getElementById('confirm_death');
    const submitBtn = document.getElementById('submit-btn');
    const birthDate = new Date('{{ $individualAnimal->date_of_birth->format('Y-m-d') }}');
    
    function calculateAge(deathDate) {
        const birth = new Date(birthDate);
        const death = new Date(deathDate);
        const diffTime = death - birth;
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
        if (deathDateInput.value) {
            const ageDisplay = calculateAge(deathDateInput.value);
            ageAtDeathSpan.textContent = ageDisplay;
        }
    }
    
    function updateSubmitButton() {
        submitBtn.disabled = !confirmCheckbox.checked;
    }
    
    deathDateInput.addEventListener('change', updateAgeDisplay);
    confirmCheckbox.addEventListener('change', updateSubmitButton);
    
    // Initial updates
    updateAgeDisplay();
    updateSubmitButton();
});
</script>
@endsection
