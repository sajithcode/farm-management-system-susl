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

<!-- Medicine Usage Summary -->
@if($data['medicine_usage']->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3"><i class="fas fa-pills me-2"></i>Medicine Usage Summary</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Medicine Name</th>
                        <th>Treatment Type</th>
                        <th>Total Uses</th>
                        <th>Total Dosage</th>
                        <th>Most Common Reason</th>
                        <th>Usage Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['medicine_usage'] as $usage)
                    <tr>
                        <td><i class="fas fa-capsules me-1"></i>{{ $usage->medicine_name }}</td>
                        <td>
                            <span class="badge 
                                @if($usage->treatment_type === 'Treatment') bg-warning
                                @elseif($usage->treatment_type === 'Prevention') bg-success
                                @elseif($usage->treatment_type === 'Vaccination') bg-primary
                                @else bg-secondary
                                @endif">
                                {{ $usage->treatment_type ?? 'General' }}
                            </span>
                        </td>
                        <td>{{ $usage->usage_count }}</td>
                        <td>{{ number_format($usage->total_dosage, 2) }} {{ $usage->dosage_unit ?? 'ml' }}</td>
                        <td>{{ $usage->common_reason ?? 'Not specified' }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: {{ ($usage->usage_count / $data['summary']['total_records']) * 100 }}%">
                                    {{ number_format(($usage->usage_count / $data['summary']['total_records']) * 100, 1) }}%
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
@if($data['medicine_records']->count() > 0)
<h5 class="mb-3"><i class="fas fa-list-alt me-2"></i>Medicine Administration Records</h5>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Medicine</th>
                <th>Treatment Type</th>
                <th>Target</th>
                <th>Dosage</th>
                <th>Reason</th>
                <th>Administered By</th>
                <th>Next Due</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['medicine_records'] as $record)
            <tr>
                <td>{{ $record->medicine_date->format('M d, Y') }}</td>
                <td>
                    <strong>{{ $record->medicine_name }}</strong>
                    @if($record->medicine_type)
                        <br><small class="text-muted">{{ $record->medicine_type }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge 
                        @if($record->treatment_type === 'Treatment') bg-warning
                        @elseif($record->treatment_type === 'Prevention') bg-success
                        @elseif($record->treatment_type === 'Vaccination') bg-primary
                        @else bg-secondary
                        @endif">
                        {{ $record->treatment_type ?? 'General' }}
                    </span>
                </td>
                <td>
                    @if($record->batch_id)
                        <i class="fas fa-users me-1"></i>Batch #{{ $record->batch->batch_id ?? 'N/A' }}
                        <br><small class="text-muted">{{ $record->batch->animal_type ?? 'N/A' }}</small>
                    @elseif($record->animal_id)
                        <i class="fas fa-paw me-1"></i>Animal #{{ $record->individualAnimal->animal_id ?? 'N/A' }}
                        <br><small class="text-muted">{{ $record->individualAnimal->animal_type ?? 'N/A' }}</small>
                    @else
                        <i class="fas fa-question-circle me-1"></i>Not specified
                    @endif
                </td>
                <td>
                    {{ $record->dosage }} {{ $record->dosage_unit ?? 'ml' }}
                    @if($record->administration_method)
                        <br><small class="text-muted">{{ $record->administration_method }}</small>
                    @endif
                </td>
                <td>{{ Str::limit($record->reason ?? 'Routine', 30) }}</td>
                <td>
                    {{ $record->user->name }}
                    @if($record->veterinarian)
                        <br><small class="text-success"><i class="fas fa-user-md me-1"></i>{{ $record->veterinarian }}</small>
                    @endif
                </td>
                <td>
                    @if($record->next_dose_date)
                        {{ $record->next_dose_date->format('M d, Y') }}
                        @if($record->next_dose_date->isPast())
                            <br><small class="text-danger">Overdue</small>
                        @elseif($record->next_dose_date->isToday())
                            <br><small class="text-warning">Due Today</small>
                        @endif
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if($record->completed)
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-warning">Ongoing</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="4">MEDICINE TOTALS</th>
                <th>{{ number_format($data['medicine_records']->sum('dosage'), 2) }} total dosage</th>
                <th colspan="2">{{ $data['medicine_records']->count() }} treatments</th>
                <th colspan="2">
                    {{ $data['medicine_records']->where('completed', true)->count() }} completed
                </th>
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
