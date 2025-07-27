@extends('layouts.app')

@section('title', 'Add Medicine Record')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus me-2 text-success"></i>Add Medicine Record
        </h1>
        <p class="text-muted mb-0">Record medicine application for batch or individual animals</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Records
        </a>
    </div>
</div>

<!-- Medicine Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-pills me-2"></i>Medicine Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('medicines.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medicine_date" class="form-label">
                                <i class="fas fa-calendar-day me-1"></i>Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('medicine_date') is-invalid @enderror" 
                                   id="medicine_date" 
                                   name="medicine_date" 
                                   value="{{ old('medicine_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('medicine_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="apply_to" class="form-label">
                                <i class="fas fa-target me-1"></i>Apply To <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('apply_to') is-invalid @enderror" 
                                    id="apply_to" 
                                    name="apply_to" 
                                    required>
                                <option value="">Select Application Type</option>
                                <option value="batch" {{ old('apply_to') === 'batch' ? 'selected' : '' }}>Batch Animals</option>
                                <option value="individual" {{ old('apply_to') === 'individual' ? 'selected' : '' }}>Individual Animal</option>
                            </select>
                            @error('apply_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="target_id" class="form-label">
                            <i class="fas fa-search me-1"></i>
                            <span id="target-label">Animal ID or Batch ID</span> <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('target_id') is-invalid @enderror" 
                                id="target_id" 
                                name="target_id" 
                                required>
                            <option value="">Select target first by choosing application type above</option>
                        </select>
                        @error('target_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="target-help">
                            Please select the application type first to see available targets.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="medicine_name" class="form-label">
                            <i class="fas fa-pills me-1"></i>Medicine Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('medicine_name') is-invalid @enderror" 
                               id="medicine_name" 
                               name="medicine_name" 
                               value="{{ old('medicine_name') }}"
                               placeholder="e.g., Antibiotics, Vitamins, Vaccines"
                               required>
                        @error('medicine_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
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
                                   placeholder="0.00"
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label">
                                <i class="fas fa-ruler me-1"></i>Unit <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <option value="ml" {{ old('unit') === 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                                <option value="cc" {{ old('unit') === 'cc' ? 'selected' : '' }}>Cubic Centimeters (cc)</option>
                                <option value="mg" {{ old('unit') === 'mg' ? 'selected' : '' }}>Milligrams (mg)</option>
                                <option value="g" {{ old('unit') === 'g' ? 'selected' : '' }}>Grams (g)</option>
                                <option value="tablets" {{ old('unit') === 'tablets' ? 'selected' : '' }}>Tablets</option>
                                <option value="capsules" {{ old('unit') === 'capsules' ? 'selected' : '' }}>Capsules</option>
                                <option value="doses" {{ old('unit') === 'doses' ? 'selected' : '' }}>Doses</option>
                                <option value="injections" {{ old('unit') === 'injections' ? 'selected' : '' }}>Injections</option>
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
                            <div class="form-text">Optional: Cost per unit of medicine</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="administered_by" class="form-label">
                            <i class="fas fa-user-md me-1"></i>Administered By <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('administered_by') is-invalid @enderror" 
                               id="administered_by" 
                               name="administered_by" 
                               value="{{ old('administered_by', auth()->user()->name) }}"
                               placeholder="Name of person who administered the medicine"
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
                                  placeholder="Optional notes about the medicine application (purpose, symptoms, veterinary instructions, etc.)">{{ old('notes') }}</textarea>
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
                        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Medicine Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Quick Reference -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Medicine Guidelines
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Record accurate dosage and administration method</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Include withdrawal periods for food safety</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Document veterinary instructions if applicable</small>
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Keep track of medicine costs for budgeting</small>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Target Summary -->
        <div class="card mt-3" id="target-summary" style="display: none;">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-target me-2"></i>Target Summary
                </h6>
            </div>
            <div class="card-body" id="target-summary-content">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
        
        <!-- Common Medicines -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-pills me-2"></i>Common Medicines
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <button type="button" class="list-group-item list-group-item-action p-2 medicine-suggestion" 
                            data-medicine="Antibiotics" data-unit="ml">
                        <small><strong>Antibiotics</strong> - Bacterial infections</small>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action p-2 medicine-suggestion" 
                            data-medicine="Vitamins" data-unit="ml">
                        <small><strong>Vitamins</strong> - Nutritional supplement</small>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action p-2 medicine-suggestion" 
                            data-medicine="Vaccines" data-unit="doses">
                        <small><strong>Vaccines</strong> - Disease prevention</small>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action p-2 medicine-suggestion" 
                            data-medicine="Dewormer" data-unit="ml">
                        <small><strong>Dewormer</strong> - Parasite control</small>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action p-2 medicine-suggestion" 
                            data-medicine="Pain Relief" data-unit="mg">
                        <small><strong>Pain Relief</strong> - Pain management</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyToSelect = document.getElementById('apply_to');
    const targetSelect = document.getElementById('target_id');
    const targetLabel = document.getElementById('target-label');
    const targetHelp = document.getElementById('target-help');
    const targetSummary = document.getElementById('target-summary');
    const targetSummaryContent = document.getElementById('target-summary-content');
    const quantityInput = document.getElementById('quantity');
    const costPerUnitInput = document.getElementById('cost_per_unit');
    const costCalculation = document.getElementById('cost-calculation');
    const totalCostSpan = document.getElementById('total-cost');
    const medicineNameInput = document.getElementById('medicine_name');
    const unitSelect = document.getElementById('unit');
    
    // Handle application type change
    applyToSelect.addEventListener('change', function() {
        const applyTo = this.value;
        targetSelect.innerHTML = '<option value="">Loading...</option>';
        targetSummary.style.display = 'none';
        
        if (applyTo === 'batch') {
            targetLabel.textContent = 'Batch ID';
            targetHelp.textContent = 'Select the batch to apply medicine to.';
            loadTargets('batch');
        } else if (applyTo === 'individual') {
            targetLabel.textContent = 'Animal ID';
            targetHelp.textContent = 'Select the individual animal to apply medicine to.';
            loadTargets('individual');
        } else {
            targetSelect.innerHTML = '<option value="">Select application type first</option>';
            targetHelp.textContent = 'Please select the application type first to see available targets.';
        }
    });
    
    // Handle target selection change
    targetSelect.addEventListener('change', function() {
        if (this.value) {
            loadTargetSummary();
        } else {
            targetSummary.style.display = 'none';
        }
    });
    
    // Load targets based on application type
    function loadTargets(type) {
        fetch(`{{ route('medicines.ajax.targets') }}?type=${type}`)
            .then(response => response.json())
            .then(data => {
                targetSelect.innerHTML = '<option value="">Select ' + (type === 'batch' ? 'batch' : 'animal') + '</option>';
                data.forEach(target => {
                    const option = document.createElement('option');
                    option.value = target.id;
                    option.textContent = target.text;
                    if ('{{ old('target_id') }}' === target.id) {
                        option.selected = true;
                    }
                    targetSelect.appendChild(option);
                });
                
                // If there's an old value, trigger summary load
                if ('{{ old('target_id') }}' && targetSelect.value) {
                    loadTargetSummary();
                }
            })
            .catch(error => {
                console.error('Error loading targets:', error);
                targetSelect.innerHTML = '<option value="">Error loading targets</option>';
            });
    }
    
    // Load target summary
    function loadTargetSummary() {
        const applyTo = applyToSelect.value;
        const targetId = targetSelect.value;
        
        if (!applyTo || !targetId) return;
        
        // Show basic summary (you can enhance this with AJAX call for more details)
        const selectedOption = targetSelect.options[targetSelect.selectedIndex];
        targetSummaryContent.innerHTML = `
            <p class="mb-1"><strong>Selected ${applyTo}:</strong></p>
            <p class="mb-0">${selectedOption.textContent}</p>
        `;
        targetSummary.style.display = 'block';
    }
    
    // Cost calculation
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
    
    // Medicine suggestions
    document.querySelectorAll('.medicine-suggestion').forEach(button => {
        button.addEventListener('click', function() {
            const medicine = this.dataset.medicine;
            const unit = this.dataset.unit;
            
            medicineNameInput.value = medicine;
            unitSelect.value = unit;
        });
    });
    
    // Initialize if there are old values
    if (applyToSelect.value) {
        applyToSelect.dispatchEvent(new Event('change'));
    }
    
    // Initial cost calculation
    updateCostCalculation();
});
</script>
@endsection
