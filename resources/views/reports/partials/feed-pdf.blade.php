<!-- Feed Report Summary -->
<table class="summary-cards">
    <tr>
        <td>
            <div class="summary-card warning">
                <h3>{{ number_format($data['summary']['total_quantity'], 0) }} kg</h3>
                <p>Total Feed Used</p>
            </div>
        </td>
        <td>
            <div class="summary-card primary">
                <h3>{{ $data['summary']['total_records'] }}</h3>
                <p>Feed Records</p>
            </div>
        </td>
        <td>
            <div class="summary-card info">
                <h3>{{ number_format($data['summary']['total_quantity'] / max($data['summary']['total_records'], 1), 1) }} kg</h3>
                <p>Avg per Record</p>
            </div>
        </td>
        <td>
            <div class="summary-card success">
                <h3>${{ number_format($data['batch_feeds']->sum('cost') + $data['individual_feeds']->sum('cost'), 2) }}</h3>
                <p>Total Cost</p>
            </div>
        </td>
    </tr>
</table>

<!-- Batch Feed Records -->
@if($data['batch_feeds']->count() > 0)
<h5 class="section-title">Batch Feed Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Batch</th>
            <th>Feed Type</th>
            <th>Quantity (kg)</th>
            <th>Cost</th>
            <th>Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['batch_feeds'] as $feed)
        <tr>
            <td>{{ $feed->feed_date->format('M d, Y') }}</td>
            <td>#{{ $feed->batch->batch_id ?? 'N/A' }} ({{ $feed->batch->animal_type ?? 'N/A' }})</td>
            <td>{{ $feed->feedType->name ?? 'N/A' }}</td>
            <td>{{ number_format($feed->quantity, 2) }}</td>
            <td>${{ number_format($feed->cost ?? 0, 2) }}</td>
            <td>{{ $feed->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">BATCH TOTALS</th>
            <th>{{ number_format($data['batch_feeds']->sum('quantity'), 2) }} kg</th>
            <th>${{ number_format($data['batch_feeds']->sum('cost'), 2) }}</th>
            <th>{{ $data['batch_feeds']->count() }} records</th>
        </tr>
    </tfoot>
</table>
@endif

<!-- Individual Animal Feed Records -->
@if($data['individual_feeds']->count() > 0)
<h5 class="section-title">Individual Animal Feed Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Animal</th>
            <th>Feed Type</th>
            <th>Quantity (kg)</th>
            <th>Cost</th>
            <th>Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['individual_feeds'] as $feed)
        <tr>
            <td>{{ $feed->feed_date->format('M d, Y') }}</td>
            <td>#{{ $feed->individualAnimal->animal_id ?? 'N/A' }} ({{ $feed->individualAnimal->animal_type ?? 'N/A' }})</td>
            <td>{{ $feed->feedType->name ?? 'N/A' }}</td>
            <td>{{ number_format($feed->quantity, 2) }}</td>
            <td>${{ number_format($feed->cost ?? 0, 2) }}</td>
            <td>{{ $feed->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">INDIVIDUAL TOTALS</th>
            <th>{{ number_format($data['individual_feeds']->sum('quantity'), 2) }} kg</th>
            <th>${{ number_format($data['individual_feeds']->sum('cost'), 2) }}</th>
            <th>{{ $data['individual_feeds']->count() }} records</th>
        </tr>
    </tfoot>
</table>
@endif

@if($data['batch_feeds']->count() === 0 && $data['individual_feeds']->count() === 0)
<div class="no-data">
    <h5>No feed records found for the selected period</h5>
</div>
@endif