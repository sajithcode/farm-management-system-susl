<!-- Slaughter Report Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="text-center p-3 bg-primary text-white rounded">
            <h4>{{ $data['summary']['total_slaughtered'] }}</h4>
            <small>Total Slaughtered</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-success text-white rounded">
            <h4>{{ number_format($data['summary']['total_weight'], 1) }} kg</h4>
            <small>Total Weight</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-info text-white rounded">
            <h4>{{ $data['summary']['batch_slaughter'] }}</h4>
            <small>Batch Slaughter</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="text-center p-3 bg-warning text-dark rounded">
            <h4>{{ $data['summary']['individual_slaughter'] }}</h4>
            <small>Individual Slaughter</small>
        </div>
    </div>
</div>

<!-- Batch Slaughter Records -->
@if($data['batch_slaughter']->count() > 0)
<h5 class="mb-3"><i class="fas fa-users me-2"></i>Batch Slaughter Records</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
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
        <tfoot class="table-light">
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
</div>
@endif

<!-- Individual Animal Slaughter Records -->
@if($data['individual_slaughter']->count() > 0)
<h5 class="mb-3"><i class="fas fa-paw me-2"></i>Individual Animal Slaughter Records</h5>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
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
        <tfoot class="table-light">
            <tr>
                <th colspan="2">INDIVIDUAL TOTALS</th>
                <th>{{ number_format($data['individual_slaughter']->sum('weight'), 1) }}</th>
                <th colspan="2">₦{{ number_format($data['individual_slaughter']->sum('total_amount'), 2) }}</th>
                <th colspan="3">{{ $data['individual_slaughter']->count() }} animals</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['batch_slaughter']->count() === 0 && $data['individual_slaughter']->count() === 0)
<div class="text-center py-5">
    <i class="fas fa-cut fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No slaughter records found for the selected period</h5>
    <p class="text-muted">No animals were slaughtered during this time period.</p>
</div>
@endif
