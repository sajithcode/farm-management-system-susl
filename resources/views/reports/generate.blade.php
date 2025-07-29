@extends('layouts.app')

@section('title', 'Generate Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-alt me-2 text-success"></i>Generate Report</h1>
    <a href="{{ route('reports.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Report Configuration</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reports.preview') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="report_type" class="form-label">Report Type *</label>
                        <select class="form-select @error('report_type') is-invalid @enderror" 
                                id="report_type" name="report_type" required>
                            <option value="">Select Report Type</option>
                            <option value="sales" {{ old('report_type') === 'sales' ? 'selected' : '' }}>
                                💰 Sales Report
                            </option>
                            <option value="feed" {{ old('report_type') === 'feed' ? 'selected' : '' }}>
                                🌾 Feed Usage Report
                            </option>
                            <option value="death" {{ old('report_type') === 'death' ? 'selected' : '' }}>
                                💀 Mortality Report
                            </option>
                            <option value="slaughter" {{ old('report_type') === 'slaughter' ? 'selected' : '' }}>
                                🔪 Slaughter Report
                            </option>
                            <option value="production" {{ old('report_type') === 'production' ? 'selected' : '' }}>
                                🥚 Production Report
                            </option>
                            <option value="medicine" {{ old('report_type') === 'medicine' ? 'selected' : '' }}>
                                💊 Medicine Report
                            </option>
                        </select>
                        @error('report_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', date('Y-m-01')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" 
                                       value="{{ old('end_date', date('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label">Location Filter (Optional)</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location') }}" 
                               placeholder="Enter location name or leave blank for all locations">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Filter results by specific location or leave blank to include all.</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('reports.dashboard') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-eye me-2"></i>Preview Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Report Types</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">💰 Sales Report</h6>
                    <small class="text-muted">
                        Complete sales transactions including revenue, quantities, and buyer information.
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-warning">🌾 Feed Usage Report</h6>
                    <small class="text-muted">
                        Feed consumption data for both batches and individual animals.
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-danger">💀 Mortality Report</h6>
                    <small class="text-muted">
                        Death records with causes and affected animals.
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-secondary">🔪 Slaughter Report</h6>
                    <small class="text-muted">
                        Slaughter data including weights and processing information.
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-info">🥚 Production Report</h6>
                    <small class="text-muted">
                        Egg production records and daily output statistics.
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-success">💊 Medicine Report</h6>
                    <small class="text-muted">
                        Medicine administration records and health treatments.
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-download me-2"></i>Export Options</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">After previewing your report, you can:</p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-file-pdf text-danger me-2"></i>
                        <strong>Export as PDF</strong> - For printing and archiving
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-file-word text-primary me-2"></i>
                        <strong>Export as Word</strong> - For editing and sharing
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-print text-secondary me-2"></i>
                        <strong>Print Preview</strong> - Direct printing option
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Ensure end date is not before start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    // Set initial min date for end date
    if (startDateInput.value) {
        endDateInput.min = startDateInput.value;
    }
});
</script>
@endsection
