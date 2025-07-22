@extends('layouts.app')

@section('title', 'Add New Batch - Animal Management')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Add New Batch
                </h1>
                <p class="text-muted mb-0">Create a new animal batch</p>
            </div>
            <a href="{{ route('batches.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Batches
            </a>
        </div>

        <!-- Add Batch Form -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Batch Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('batches.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Batch ID -->
                        <div class="col-md-6 mb-3">
                            <label for="batch_id" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>Batch ID <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('batch_id') is-invalid @enderror" 
                                   id="batch_id" 
                                   name="batch_id" 
                                   value="{{ old('batch_id') }}" 
                                   placeholder="e.g., BATCH001"
                                   required>
                            @error('batch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Batch Name (Optional) -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-1"></i>Batch Name (Optional)
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., Broiler Group A">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Animal Type -->
                        <div class="col-md-6 mb-3">
                            <label for="animal_type" class="form-label">
                                <i class="fas fa-paw me-1"></i>Animal Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('animal_type') is-invalid @enderror" 
                                    id="animal_type" 
                                    name="animal_type" 
                                    required>
                                <option value="">Select Animal Type</option>
                                <option value="broiler" {{ old('animal_type') == 'broiler' ? 'selected' : '' }}>
                                    🐔 Broiler Chicken
                                </option>
                                <option value="layer" {{ old('animal_type') == 'layer' ? 'selected' : '' }}>
                                    🥚 Layer Chicken
                                </option>
                                <option value="turkey" {{ old('animal_type') == 'turkey' ? 'selected' : '' }}>
                                    🦃 Turkey
                                </option>
                                <option value="goat" {{ old('animal_type') == 'goat' ? 'selected' : '' }}>
                                    🐐 Goat
                                </option>
                                <option value="pig" {{ old('animal_type') == 'pig' ? 'selected' : '' }}>
                                    🐷 Pig
                                </option>
                                <option value="cattle" {{ old('animal_type') == 'cattle' ? 'selected' : '' }}>
                                    🐄 Cattle
                                </option>
                                <option value="sheep" {{ old('animal_type') == 'sheep' ? 'selected' : '' }}>
                                    🐑 Sheep
                                </option>
                                <option value="duck" {{ old('animal_type') == 'duck' ? 'selected' : '' }}>
                                    🦆 Duck
                                </option>
                            </select>
                            @error('animal_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}" 
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Initial Count -->
                        <div class="col-md-6 mb-3">
                            <label for="initial_count" class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>Initial Count <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('initial_count') is-invalid @enderror" 
                                   id="initial_count" 
                                   name="initial_count" 
                                   value="{{ old('initial_count') }}" 
                                   min="1"
                                   placeholder="e.g., 500"
                                   required>
                            @error('initial_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Number of animals when batch started</div>
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label">
                                <i class="fas fa-truck me-1"></i>Supplier
                            </label>
                            <input type="text" 
                                   class="form-control @error('supplier') is-invalid @enderror" 
                                   id="supplier" 
                                   name="supplier" 
                                   value="{{ old('supplier') }}" 
                                   placeholder="e.g., ABC Hatchery">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Responsible Person -->
                        <div class="col-md-6 mb-3">
                            <label for="responsible_person" class="form-label">
                                <i class="fas fa-user me-1"></i>Responsible Person <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('responsible_person') is-invalid @enderror" 
                                   id="responsible_person" 
                                   name="responsible_person" 
                                   value="{{ old('responsible_person', auth()->user()->name) }}" 
                                   placeholder="Person responsible for this batch"
                                   required>
                            @error('responsible_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location/Farm -->
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Location/Farm
                            </label>
                            <input type="text" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', auth()->user()->location) }}" 
                                   placeholder="Farm location">
                            @error('location')
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
                                  placeholder="Additional notes about this batch...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('batches.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Batch
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Batch Creation Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Batch ID:</strong> Use a unique identifier (e.g., BATCH001, BR-2024-001)</li>
                    <li><strong>Start Date:</strong> The date when animals were received or hatched</li>
                    <li><strong>Initial Count:</strong> Total number of animals at the beginning</li>
                    <li><strong>Animal Type:</strong> Select the appropriate animal category</li>
                    <li><strong>Responsible Person:</strong> Who will manage and track this batch</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate batch ID suggestion
document.addEventListener('DOMContentLoaded', function() {
    const animalTypeSelect = document.getElementById('animal_type');
    const batchIdInput = document.getElementById('batch_id');
    const startDateInput = document.getElementById('start_date');
    
    function generateBatchId() {
        const animalType = animalTypeSelect.value;
        const startDate = startDateInput.value;
        
        if (animalType && startDate && !batchIdInput.value) {
            const date = new Date(startDate);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const typeCode = animalType.toUpperCase().substring(0, 3);
            
            // Generate suggestion (user can modify)
            const suggestion = `${typeCode}-${year}${month}-001`;
            batchIdInput.placeholder = `Suggestion: ${suggestion}`;
        }
    }
    
    animalTypeSelect.addEventListener('change', generateBatchId);
    startDateInput.addEventListener('change', generateBatchId);
});
</script>
@endsection
