<!-- Feed Report Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="text-center p-3 bg-warning text-dark rounded">
            <h4>{{ number_format($data['summary']['total_quantity'], 0) }} kg</h4>
            <small>Total Feed Used</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-center p-3 bg-primary text-white rounded">
            <h4>{{ $data['summary']['total_records'] }}</h4>
            <small>Feed Records</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-center p-3 bg-info text-white rounded">
            <h4>{{ number_format($data['summary']['total_quantity'] / max($data['summary']['total_records'], 1), 1) }} kg</h4>
            <small>Avg per Record</small>
        </div>
    </div>
</div>

<!-- Batch Feed Records -->
@if($data['batch_feeds']->count() > 0)
<h5 class="mb-3"><i class="fas fa-users me-2"></i>Batch Feed Records</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
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
        <tfoot class="table-light">
            <tr>
                <th colspan="3">BATCH TOTALS</th>
                <th>{{ number_format($data['batch_feeds']->sum('quantity'), 2) }} kg</th>
                <th>${{ number_format($data['batch_feeds']->sum('cost'), 2) }}</th>
                <th>{{ $data['batch_feeds']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<!-- Individual Animal Feed Records -->
@if($data['individual_feeds']->count() > 0)
<h5 class="mb-3"><i class="fas fa-paw me-2"></i>Individual Animal Feed Records</h5>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
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
        <tfoot class="table-light">
            <tr>
                <th colspan="3">INDIVIDUAL TOTALS</th>
                <th>{{ number_format($data['individual_feeds']->sum('quantity'), 2) }} kg</th>
                <th>${{ number_format($data['individual_feeds']->sum('cost'), 2) }}</th>
                <th>{{ $data['individual_feeds']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['batch_feeds']->count() === 0 && $data['individual_feeds']->count() === 0)
<div class="text-center py-5">
    <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No feed records found for the selected period</h5>
</div>
@endif
