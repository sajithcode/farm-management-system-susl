<!-- Medicine Report Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="text-center p-3 bg-info text-white rounded">
            <h4>{{ $data['summary']['total_records'] }}</h4>
            <small>Total Treatments</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-success text-white rounded">
            <h4>{{ $data['summary']['unique_medicines'] }}</h4>
            <small>Different Medicines</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-warning text-dark rounded">
            <h4>{{ $data['summary']['batch_treatments'] }}</h4>
            <small>Batch Treatments</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-primary text-white rounded">
            <h4>{{ $data['summary']['individual_treatments'] }}</h4>
            <small>Individual Treatments</small>
        </div>
    </div>
</div>

@if($data['summary']['total_cost'] > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h5><i class="fas fa-dollar-sign me-2"></i>Total Medicine Cost: ${{ number_format($data['summary']['total_cost'], 2) }}</h5>
        </div>
    </div>
</div>
@endif

<!-- Medicine Usage Summary -->
@if($data['medicine_usage'] && $data['medicine_usage']->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3"><i class="fas fa-pills me-2"></i>Medicine Usage Summary</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Medicine Name</th>
                        <th>Application Type</th>
                        <th>Total Uses</th>
                        <th>Total Dosage</th>
                        <th>Total Cost</th>
                        <th>Usage Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['medicine_usage'] as $usage)
                    <tr>
                        <td><i class="fas fa-capsules me-1"></i>{{ $usage->medicine_name }}</td>
                        <td>
                            <span class="badge 
                                @if($usage->treatment_type === 'Batch') bg-warning
                                @elseif($usage->treatment_type === 'Individual') bg-success
                                @else bg-secondary
                                @endif">
                                {{ $usage->treatment_type }}
                            </span>
                        </td>
                        <td>{{ $usage->usage_count }}</td>
                        <td>{{ number_format($usage->total_dosage, 2) }} {{ $usage->dosage_unit }}</td>
                        <td>${{ number_format($usage->total_cost, 2) }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: {{ $data['summary']['total_records'] > 0 ? ($usage->usage_count / $data['summary']['total_records']) * 100 : 0 }}%">
                                    {{ $data['summary']['total_records'] > 0 ? number_format(($usage->usage_count / $data['summary']['total_records']) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Detailed Medicine Records -->
@if($data['medicine_records'] && $data['medicine_records']->count() > 0)
<h5 class="mb-3"><i class="fas fa-list-alt me-2"></i>Medicine Administration Records</h5>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Medicine</th>
                <th>Apply To</th>
                <th>Target</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Administered By</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['medicine_records'] as $record)
            <tr>
                <td>{{ $record->medicine_date->format('M d, Y') }}</td>
                <td>
                    <strong>{{ $record->medicine_name }}</strong>
                </td>
                <td>
                    <span class="badge 
                        @if($record->apply_to === 'batch') bg-warning
                        @elseif($record->apply_to === 'individual') bg-success
                        @else bg-secondary
                        @endif">
                        {{ ucfirst($record->apply_to) }}
                    </span>
                </td>
                <td>
                    @if($record->apply_to === 'batch' && $record->batch_id)
                        <i class="fas fa-users me-1"></i>Batch #{{ $record->batch->batch_id ?? $record->batch_id }}
                        @if($record->batch && $record->batch->animal_type)
                            <br><small class="text-muted">{{ $record->batch->animal_type }}</small>
                        @endif
                    @elseif($record->apply_to === 'individual' && $record->animal_id)
                        <i class="fas fa-paw me-1"></i>Animal #{{ $record->individualAnimal->animal_id ?? $record->animal_id }}
                        @if($record->individualAnimal && $record->individualAnimal->animal_type)
                            <br><small class="text-muted">{{ $record->individualAnimal->animal_type }}</small>
                        @endif
                    @else
                        <i class="fas fa-question-circle me-1"></i>{{ $record->apply_to === 'batch' ? 'Batch #' . $record->batch_id : 'Animal #' . $record->animal_id }}
                    @endif
                </td>
                <td>
                    {{ number_format($record->quantity, 2) }} {{ $record->unit ?? 'units' }}
                </td>
                <td>
                    @if($record->cost_per_unit)
                        ${{ number_format($record->cost_per_unit, 2) }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if($record->cost_per_unit)
                        ${{ number_format($record->quantity * $record->cost_per_unit, 2) }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if($record->administered_by)
                        {{ $record->administered_by }}
                    @elseif($record->user)
                        {{ $record->user->name }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ Str::limit($record->notes ?? 'No notes', 30) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="4">TOTALS</th>
                <th>{{ number_format($data['medicine_records']->sum('quantity'), 2) }} total quantity</th>
                <th></th>
                <th>
                    ${{ number_format($data['medicine_records']->sum(function($record) { 
                        return $record->cost_per_unit ? $record->quantity * $record->cost_per_unit : 0; 
                    }), 2) }}
                </th>
                <th colspan="2">{{ $data['medicine_records']->count() }} treatments</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['medicine_records']->count() === 0)
<div class="text-center py-5">
    <i class="fas fa-first-aid fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No medicine records found for the selected period</h5>
    <p class="text-muted">No medical treatments were administered during this time period.</p>
</div>
@endif
