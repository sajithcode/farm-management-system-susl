<!-- Sales Report Summary -->
<table class="summary-cards">
    <tr>
        <td>
            <div class="summary-card success">
                <h3>${{ number_format($data['summary']['total_revenue'], 2) }}</h3>
                <p>Total Revenue</p>
            </div>
        </td>
        <td>
            <div class="summary-card primary">
                <h3>{{ $data['summary']['total_records'] }}</h3>
                <p>Sales Records</p>
            </div>
        </td>
        <td>
            <div class="summary-card info">
                <h3>{{ number_format($data['summary']['total_quantity'], 0) }}</h3>
                <p>Total Quantity</p>
            </div>
        </td>
        <td>
            <div class="summary-card warning">
                <h3>${{ number_format($data['summary']['total_revenue'] / max($data['summary']['total_records'], 1), 2) }}</h3>
                <p>Avg Sale Value</p>
            </div>
        </td>
    </tr>
</table>

<!-- Sales Data Table -->
@if($data['data']->count() > 0)
<table>
    <thead>
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
                <span class="badge {{ $sale->item === 'eggs' ? 'warning' : 'danger' }}">
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
    <tfoot>
        <tr>
            <th colspan="3">TOTALS</th>
            <th>{{ number_format($data['summary']['total_quantity'], 0) }}</th>
            <th>${{ number_format($data['summary']['total_revenue'], 2) }}</th>
            <th colspan="2">{{ $data['summary']['total_records'] }} records</th>
        </tr>
    </tfoot>
</table>
@else
<div class="no-data">
    <h5>No sales records found for the selected period</h5>
</div>
@endif