<!-- Production Report Summary -->
<table class="summary-cards">
    <tr>
        <td>
            <div class="summary-card success">
                <h3>{{ $data['summary']['total_records'] }}</h3>
                <p>Total Records</p>
            </div>
        </td>
        <td>
            <div class="summary-card primary">
                <h3>{{ number_format($data['summary']['total_production'], 1) }}</h3>
                <p>Total Production</p>
            </div>
        </td>
        <td>
            <div class="summary-card info">
                <h3>{{ number_format($data['summary']['daily_average'], 1) }}</h3>
                <p>Daily Average</p>
            </div>
        </td>
        <td>
            <div class="summary-card warning">
                <h3>{{ $data['summary']['unique_types'] }}</h3>
                <p>Product Types</p>
            </div>
        </td>
    </tr>
</table>

<!-- Production by Type Summary -->
@if($data['production_by_type']->count() > 0)
<h5 class="section-title">Production by Type</h5>
<table>
    <thead>
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
            <td>{{ $type->product_type }}</td>
            <td>{{ $type->record_count }}</td>
            <td>{{ number_format($type->total_quantity, 1) }}</td>
            <td>{{ $type->unit ?? 'units' }}</td>
            <td>{{ number_format($type->total_quantity / $data['summary']['days_in_period'], 1) }}</td>
            <td>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $data['summary']['total_production'] > 0 ? ($type->total_quantity / $data['summary']['total_production']) * 100 : 0 }}%"></div>
                </div>
                {{ $data['summary']['total_production'] > 0 ? number_format(($type->total_quantity / $data['summary']['total_production']) * 100, 1) : 0 }}%
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- Detailed Production Records -->
@if($data['production_records']->count() > 0)
<h5 class="section-title">Production Records Detail</h5>
<table>
    <thead>
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
                <span class="badge primary">{{ $record->product_type }}</span>
            </td>
            <td>{{ number_format($record->quantity, 1) }}</td>
            <td>{{ $record->unit ?? 'units' }}</td>
            <td>
                @if($record->batch_id)
                    Batch #{{ $record->batch->batch_id ?? 'N/A' }}
                @else
                    Not specified
                @endif
            </td>
            <td>
                @if(isset($record->quality_grade) && $record->quality_grade)
                    <span class="badge 
                        @if($record->quality_grade === 'A') success
                        @elseif($record->quality_grade === 'B') warning
                        @else info
                        @endif">
                        Grade {{ $record->quality_grade }}
                    </span>
                @else
                    N/A
                @endif
            </td>
            <td>{{ Str::limit($record->notes ?? 'N/A', 40) }}</td>
            <td>{{ $record->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
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
@endif

@if($data['production_records']->count() === 0)
<div class="no-data">
    <h5>No production records found for the selected period</h5>
    <p>No production was recorded during this time period.</p>
</div>
@endif