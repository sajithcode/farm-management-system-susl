<!-- Feed Report Summary -->
<div class="summary-container">
    <div class="summary-card bg-warning">
        <div class="card-value">{{ number_format($data['summary']['total_quantity'], 0) }} kg</div>
        <div class="card-label">Total Feed Used</div>
    </div>
    <div class="summary-card bg-primary">
        <div class="card-value">{{ $data['summary']['total_records'] }}</div>
        <div class="card-label">Feed Records</div>
    </div>
    <div class="summary-card bg-info">
        <div class="card-value">{{ number_format($data['summary']['total_quantity'] / max($data['summary']['total_records'], 1), 1) }} kg</div>
        <div class="card-label">Avg per Record</div>
    </div>
    <div class="summary-card bg-success">
        <div class="card-value">${{ number_format($data['summary']['total_cost'] ?? 0, 2) }}</div>
        <div class="card-label">Total Cost</div>
    </div>
</div>

<!-- Batch Feed Records -->
@if($data['batch_feeds']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-users"></i> Batch Feed Records</h5>
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Batch</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Feed Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantity (kg)</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Cost</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['batch_feeds'] as $feed)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $feed->feed_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">#{{ $feed->batch->batch_id ?? 'N/A' }} ({{ $feed->batch->animal_type ?? 'N/A' }})</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $feed->feedType->name ?? 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($feed->quantity, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">${{ number_format($feed->cost ?? 0, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $feed->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="3">BATCH TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ number_format($data['batch_feeds']->sum('quantity'), 2) }} kg</th>
                <th style="border: 1px solid #ddd; padding: 8px;">${{ number_format($data['batch_feeds']->sum('cost'), 2) }}</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ $data['batch_feeds']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<!-- Individual Animal Feed Records -->
@if($data['individual_feeds']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-paw"></i> Individual Animal Feed Records</h5>
<div>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Animal</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Feed Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantity (kg)</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Cost</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['individual_feeds'] as $feed)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $feed->feed_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">#{{ $feed->individualAnimal->animal_id ?? 'N/A' }} ({{ $feed->individualAnimal->animal_type ?? 'N/A' }})</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $feed->feedType->name ?? 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($feed->quantity, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">${{ number_format($feed->cost ?? 0, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $feed->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="3">INDIVIDUAL TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ number_format($data['individual_feeds']->sum('quantity'), 2) }} kg</th>
                <th style="border: 1px solid #ddd; padding: 8px;">${{ number_format($data['individual_feeds']->sum('cost'), 2) }}</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ $data['individual_feeds']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['batch_feeds']->count() === 0 && $data['individual_feeds']->count() === 0)
<div style="text-align: center; padding: 40px 0;">
    <i class="fas fa-seedling" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
    <h5 style="color: #6c757d; margin-bottom: 10px;">No feed records found for the selected period</h5>
</div>
@endif
