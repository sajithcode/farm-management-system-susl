<!-- Slaughter Report Summary -->
<table class="summary-cards">
    <tr>
        <td>
            <div class="summary-card primary">
                <h3>{{ $data['summary']['total_slaughtered'] }}</h3>
                <p>Total Slaughtered</p>
            </div>
        </td>
        <td>
            <div class="summary-card success">
                <h3>{{ number_format($data['summary']['total_weight'], 1) }} kg</h3>
                <p>Total Weight</p>
            </div>
        </td>
        <td>
            <div class="summary-card info">
                <h3>{{ $data['summary']['batch_slaughter'] }}</h3>
                <p>Batch Slaughter</p>
            </div>
        </td>
        <td>
            <div class="summary-card warning">
                <h3>{{ $data['summary']['individual_slaughter'] }}</h3>
                <p>Individual Slaughter</p>
            </div>
        </td>
    </tr>
</table>

<!-- Batch Slaughter Records -->
@if($data['batch_slaughter']->count() > 0)
<h5 class="section-title">Batch Slaughter Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Batch</th>
            <th>Count</th>
            <th>Total Weight (kg)</th>
            <th>Avg Weight (kg)</th>
            <th>Price/kg</th>
            <th>Total Amount</th>
            <th>Buyer</th>
            <th>Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['batch_slaughter'] as $slaughter)
        <tr>
            <td>{{ $slaughter->slaughter_date->format('M d, Y') }}</td>
            <td>#{{ $slaughter->batch->batch_id ?? 'N/A' }} ({{ $slaughter->batch->animal_type ?? 'N/A' }})</td>
            <td>{{ $slaughter->count }}</td>
            <td>{{ number_format($slaughter->total_weight, 1) }}</td>
            <td>{{ number_format($slaughter->total_weight / $slaughter->count, 1) }}</td>
            <td>{{ $slaughter->price_per_kg ? '₦' . number_format($slaughter->price_per_kg, 2) : 'N/A' }}</td>
            <td>{{ $slaughter->total_amount ? '₦' . number_format($slaughter->total_amount, 2) : 'N/A' }}</td>
            <td>{{ $slaughter->buyer ?? 'N/A' }}</td>
            <td>{{ $slaughter->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">BATCH TOTALS</th>
            <th>{{ $data['batch_slaughter']->sum('count') }}</th>
            <th>{{ number_format($data['batch_slaughter']->sum('total_weight'), 1) }}</th>
            <th>{{ number_format($data['batch_slaughter']->sum('total_weight') / $data['batch_slaughter']->sum('count'), 1) }}</th>
            <th colspan="2">₦{{ number_format($data['batch_slaughter']->sum('total_amount'), 2) }}</th>
            <th colspan="2">{{ $data['batch_slaughter']->count() }} records</th>
        </tr>
    </tfoot>
</table>
@endif

<!-- Individual Animal Slaughter Records -->
@if($data['individual_slaughter']->count() > 0)
<h5 class="section-title">Individual Animal Slaughter Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Animal</th>
            <th>Weight (kg)</th>
            <th>Price/kg</th>
            <th>Total Amount</th>
            <th>Buyer</th>
            <th>Notes</th>
            <th>Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['individual_slaughter'] as $slaughter)
        <tr>
            <td>{{ $slaughter->slaughter_date->format('M d, Y') }}</td>
            <td>#{{ $slaughter->individualAnimal->animal_id ?? 'N/A' }} ({{ $slaughter->individualAnimal->animal_type ?? 'N/A' }})</td>
            <td>{{ number_format($slaughter->weight, 1) }}</td>
            <td>{{ $slaughter->price_per_kg ? '₦' . number_format($slaughter->price_per_kg, 2) : 'N/A' }}</td>
            <td>{{ $slaughter->total_amount ? '₦' . number_format($slaughter->total_amount, 2) : 'N/A' }}</td>
            <td>{{ $slaughter->buyer ?? 'N/A' }}</td>
            <td>{{ Str::limit($slaughter->notes ?? 'N/A', 50) }}</td>
            <td>{{ $slaughter->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">INDIVIDUAL TOTALS</th>
            <th>{{ number_format($data['individual_slaughter']->sum('weight'), 1) }}</th>
            <th colspan="2">₦{{ number_format($data['individual_slaughter']->sum('total_amount'), 2) }}</th>
            <th colspan="3">{{ $data['individual_slaughter']->count() }} animals</th>
        </tr>
    </tfoot>
</table>
@endif

@if($data['batch_slaughter']->count() === 0 && $data['individual_slaughter']->count() === 0)
<div class="no-data">
    <h5>No slaughter records found for the selected period</h5>
    <p>No animals were slaughtered during this time period.</p>
</div>
@endif