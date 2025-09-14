<!-- Production Report Summary -->
<div class="summary-container">
    <div class="summary-card bg-success">
        <div class="card-value">{{ $data['summary']['total_records'] }}</div>
        <div class="card-label">Total Records</div>
    </div>
    <div class="summary-card bg-primary">
        <div class="card-value">{{ number_format($data['summary']['total_production'], 1) }}</div>
        <div class="card-label">Total Production</div>
    </div>
    <div class="summary-card bg-info">
        <div class="card-value">{{ number_format($data['summary']['daily_average'], 1) }}</div>
        <div class="card-label">Daily Average</div>
    </div>
    <div class="summary-card bg-warning">
        <div class="card-value">{{ $data['summary']['unique_types'] }}</div>
        <div class="card-label">Product Types</div>
    </div>
</div>

<!-- Production by Type Summary -->
@if($data['production_by_type']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-chart-pie"></i> Production by Type</h5>
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Records</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Quantity</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Unit</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Daily Average</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['production_by_type'] as $type)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;"><i class="fas fa-cube"></i> {{ $type->product_type }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $type->record_count }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($type->total_quantity, 1) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $type->unit ?? 'units' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($type->total_quantity / $data['summary']['days_in_period'], 1) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">
                    <div style="background-color: #e9ecef; height: 20px; border-radius: 4px; position: relative;">
                        <div style="background-color: #007bff; height: 20px; border-radius: 4px; width: {{ $data['summary']['total_production'] > 0 ? ($type->total_quantity / $data['summary']['total_production']) * 100 : 0 }}%; color: white; text-align: center; line-height: 20px; font-size: 11px;">
                            {{ $data['summary']['total_production'] > 0 ? number_format(($type->total_quantity / $data['summary']['total_production']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Detailed Production Records -->
@if($data['production_records']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-list"></i> Production Records Detail</h5>
<div>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantity</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Unit</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Source</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quality Grade</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Notes</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['production_records'] as $record)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $record->production_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">
                    <span style="background-color: #007bff; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px;">{{ $record->product_type }}</span>
                </td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($record->quantity, 1) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $record->unit ?? 'units' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">
                    @if($record->batch_id)
                        <i class="fas fa-users"></i> Batch #{{ $record->batch->batch_id ?? 'N/A' }}
                    @else
                        <i class="fas fa-question-circle"></i> Not specified
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 8px;">
                    @if(isset($record->quality_grade) && $record->quality_grade)
                        <span style="padding: 2px 6px; border-radius: 3px; font-size: 10px; color: white; background-color: 
                            @if($record->quality_grade === 'A') #28a745
                            @elseif($record->quality_grade === 'B') #ffc107
                            @else #6c757d
                            @endif;">
                            Grade {{ $record->quality_grade }}
                        </span>
                    @else
                        <span style="color: #6c757d;">N/A</span>
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ Str::limit($record->notes ?? 'N/A', 40) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $record->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">PRODUCTION TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ number_format($data['production_records']->sum('quantity'), 1) }}</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">{{ $data['production_records']->count() }} records</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="3">
                    Avg: {{ number_format($data['production_records']->avg('quantity'), 1) }} per record
                </th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['production_records']->count() === 0)
<div style="text-align: center; padding: 40px 0;">
    <i class="fas fa-industry" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
    <h5 style="color: #6c757d; margin-bottom: 10px;">No production records found for the selected period</h5>
    <p style="color: #6c757d;">No production was recorded during this time period.</p>
</div>
@endif
