@extends('layouts.app')

@section('title', 'Add New Individual Animal')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus me-2 text-primary"></i>Add New Individual Animal
        </h1>
        <p class="text-muted mb-0">Register a new individual animal in your farm</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('individual-animals.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Animals
        </a>
    </div>
</div>

<!-- Add Animal Form -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-paw me-2"></i>Animal Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('individual-animals.store') }}">
                    @csrf
                    
                    <div class="row">
                        <!-- Animal ID -->
                        <div class="col-md-6 mb-3">
                            <label for="animal_id" class="form-label">
                                <i class="fas fa-id-card me-1"></i>Animal ID <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('animal_id') is-invalid @enderror" 
                                   id="animal_id" 
                                   name="animal_id" 
                                   value="{{ old('animal_id') }}" 
                                   placeholder="e.g., COW001, PIG001" 
                                   required>
                            @error('animal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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
                                <option value="cow" {{ old('animal_type') == 'cow' ? 'selected' : '' }}>Cow</option>
                                <option value="pig" {{ old('animal_type') == 'pig' ? 'selected' : '' }}>Pig</option>
                                <option value="goat" {{ old('animal_type') == 'goat' ? 'selected' : '' }}>Goat</option>
                                <option value="sheep" {{ old('animal_type') == 'sheep' ? 'selected' : '' }}>Sheep</option>
                                <option value="chicken" {{ old('animal_type') == 'chicken' ? 'selected' : '' }}>Chicken</option>
                                <option value="duck" {{ old('animal_type') == 'duck' ? 'selected' : '' }}>Duck</option>
                                <option value="other" {{ old('animal_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('animal_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date of Birth -->
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">
                                <i class="fas fa-birthday-cake me-1"></i>Date of Birth <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth') }}" 
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">
                                <i class="fas fa-venus-mars me-1"></i>Gender <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" 
                                    name="gender" 
                                    required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                    <i class="fas fa-mars"></i> Male
                                </option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                    <i class="fas fa-venus"></i> Female
                                </option>
                                <option value="unknown" {{ old('gender') == 'unknown' ? 'selected' : '' }}>
                                    Unknown
                                </option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
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
                                   placeholder="Where was this animal purchased from?">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Weight -->
                        <div class="col-md-6 mb-3">
                            <label for="current_weight" class="form-label">
                                <i class="fas fa-weight me-1"></i>Current Weight (kg)
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0"
                                   class="form-control @error('current_weight') is-invalid @enderror" 
                                   id="current_weight" 
                                   name="current_weight" 
                                   value="{{ old('current_weight') }}" 
                                   placeholder="e.g., 45.5">
                            @error('current_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Responsible Person -->
                    <div class="mb-3">
                        <label for="responsible_person" class="form-label">
                            <i class="fas fa-user me-1"></i>Responsible Person <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('responsible_person') is-invalid @enderror" 
                               id="responsible_person" 
                               name="responsible_person" 
                               value="{{ old('responsible_person') }}" 
                               placeholder="Who is responsible for this animal?"
                               required>
                        @error('responsible_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Any additional information about this animal...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('individual-animals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Add Animal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate Animal ID suggestion
    const animalTypeSelect = document.getElementById('animal_type');
    const animalIdInput = document.getElementById('animal_id');
    
    animalTypeSelect.addEventListener('change', function() {
        if (this.value && !animalIdInput.value) {
            const typePrefix = this.value.toUpperCase().substr(0, 3);
            const randomNum = Math.floor(Math.random() * 999) + 1;
            const suggestion = typePrefix + String(randomNum).padStart(3, '0');
            animalIdInput.placeholder = `Suggestion: ${suggestion}`;
        }
    });
});
</script>
@endsection
