<!-- Sales Report Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="text-center p-3 bg-success text-white rounded">
            <h4>${{ number_format($data['summary']['total_revenue'], 2) }}</h4>
            <small>Total Revenue</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-primary text-white rounded">
            <h4>{{ $data['summary']['total_records'] }}</h4>
            <small>Sales Records</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-info text-white rounded">
            <h4>{{ number_format($data['summary']['total_quantity'], 0) }}</h4>
            <small>Total Quantity</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-warning text-dark rounded">
            <h4>${{ number_format($data['summary']['total_revenue'] / max($data['summary']['total_records'], 1), 2) }}</h4>
            <small>Avg Sale Value</small>
        </div>
    </div>
</div>

<!-- Sales Data Table -->
@if($data['data']->count() > 0)
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Item</th>
                <th>Source</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Buyer</th>
                <th>Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['data'] as $sale)
            <tr>
                <td>{{ $sale->date->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-{{ $sale->item === 'eggs' ? 'warning' : 'danger' }} text-dark">
                        {{ ucfirst($sale->item) }}
                    </span>
                </td>
                <td>{{ $sale->source_name }}</td>
                <td>{{ number_format($sale->quantity, 2) }} {{ $sale->item === 'eggs' ? 'pcs' : 'kg' }}</td>
                <td>${{ number_format($sale->price, 2) }}</td>
                <td>{{ $sale->buyer ?: 'N/A' }}</td>
                <td>{{ $sale->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="3">TOTALS</th>
                <th>{{ number_format($data['summary']['total_quantity'], 0) }}</th>
                <th>${{ number_format($data['summary']['total_revenue'], 2) }}</th>
                <th colspan="2">{{ $data['summary']['total_records'] }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No sales records found for the selected period</h5>
</div>
@endif
