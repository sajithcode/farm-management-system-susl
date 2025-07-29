<!-- Production Report Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="text-center p-3 bg-success text-white rounded">
            <h4>{{ $data['summary']['total_records'] }}</h4>
            <small>Total Records</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-primary text-white rounded">
            <h4>{{ number_format($data['summary']['total_production'], 1) }}</h4>
            <small>Total Production</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-info text-white rounded">
            <h4>{{ number_format($data['summary']['daily_average'], 1) }}</h4>
            <small>Daily Average</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-warning text-dark rounded">
            <h4>{{ $data['summary']['unique_types'] }}</h4>
            <small>Product Types</small>
        </div>
    </div>
</div>

<!-- Production by Type Summary -->
@if($data['production_by_type']->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Production by Type</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Product Type</th>
                        <th>Total Records</th>
                        <th>Total Quantity</th>
                        <th>Unit</th>
                        <th>Daily Average</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['production_by_type'] as $type)
                    <tr>
                        <td><i class="fas fa-cube me-1"></i>{{ $type->product_type }}</td>
                        <td>{{ $type->record_count }}</td>
                        <td>{{ number_format($type->total_quantity, 1) }}</td>
                        <td>{{ $type->unit ?? 'units' }}</td>
                        <td>{{ number_format($type->total_quantity / $data['summary']['days_in_period'], 1) }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ ($type->total_quantity / $data['summary']['total_production']) * 100 }}%">
                                    {{ number_format(($type->total_quantity / $data['summary']['total_production']) * 100, 1) }}%
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

<!-- Detailed Production Records -->
@if($data['production_records']->count() > 0)
<h5 class="mb-3"><i class="fas fa-list me-2"></i>Production Records Detail</h5>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Product Type</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Source</th>
                <th>Quality Grade</th>
                <th>Notes</th>
                <th>Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['production_records'] as $record)
            <tr>
                <td>{{ $record->production_date->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-primary">{{ $record->product_type }}</span>
                </td>
                <td>{{ number_format($record->quantity, 1) }}</td>
                <td>{{ $record->unit ?? 'units' }}</td>
                <td>
                    @if($record->batch_id)
                        <i class="fas fa-users me-1"></i>Batch #{{ $record->batch->batch_id ?? 'N/A' }}
                    @elseif($record->animal_id)
                        <i class="fas fa-paw me-1"></i>Animal #{{ $record->individualAnimal->animal_id ?? 'N/A' }}
                    @else
                        <i class="fas fa-question-circle me-1"></i>Not specified
                    @endif
                </td>
                <td>
                    @if($record->quality_grade)
                        <span class="badge 
                            @if($record->quality_grade === 'A') bg-success
                            @elseif($record->quality_grade === 'B') bg-warning
                            @else bg-secondary
                            @endif">
                            Grade {{ $record->quality_grade }}
                        </span>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ Str::limit($record->notes ?? 'N/A', 40) }}</td>
                <td>{{ $record->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="2">PRODUCTION TOTALS</th>
                <th>{{ number_format($data['production_records']->sum('quantity'), 1) }}</th>
                <th colspan="2">{{ $data['production_records']->count() }} records</th>
                <th colspan="3">
                    Avg: {{ number_format($data['production_records']->avg('quantity'), 1) }} per record
                </th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['production_records']->count() === 0)
<div class="text-center py-5">
    <i class="fas fa-industry fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No production records found for the selected period</h5>
    <p class="text-muted">No production was recorded during this time period.</p>
</div>
@endif
