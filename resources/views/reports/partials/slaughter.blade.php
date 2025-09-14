<!-- Slaughter Report Summary -->
<div class="summary-container">
    <div class="summary-card bg-primary">
        <div class="card-value">{{ $data['summary']['total_slaughtered'] }}</div>
        <div class="card-label">Total Slaughtered</div>
    </div>
    <div class="summary-card bg-success">
        <div class="card-value">{{ number_format($data['summary']['total_weight'], 1) }} kg</div>
        <div class="card-label">Total Weight</div>
    </div>
    <div class="summary-card bg-info">
        <div class="card-value">{{ $data['summary']['batch_slaughter'] }}</div>
        <div class="card-label">Batch Slaughter</div>
    </div>
    <div class="summary-card bg-warning">
        <div class="card-value">{{ $data['summary']['individual_slaughter'] }}</div>
        <div class="card-label">Individual Slaughter</div>
    </div>
</div>

<!-- Batch Slaughter Records -->
@if($data['batch_slaughter']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-users"></i> Batch Slaughter Records</h5>
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Batch</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Count</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Total Weight (kg)</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Avg Weight (kg)</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Price/kg</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Total Amount</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Buyer</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['batch_slaughter'] as $slaughter)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $slaughter->slaughter_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">#{{ $slaughter->batch->batch_id ?? 'N/A' }} ({{ $slaughter->batch->animal_type ?? 'N/A' }})</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $slaughter->count }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ number_format($slaughter->total_weight, 1) }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ number_format($slaughter->total_weight / $slaughter->count, 1) }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $slaughter->price_per_kg ? '₦' . number_format($slaughter->price_per_kg, 2) : 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $slaughter->total_amount ? '₦' . number_format($slaughter->total_amount, 2) : 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $slaughter->buyer ?? 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $slaughter->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 6px;" colspan="2">BATCH TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 6px;">{{ $data['batch_slaughter']->sum('count') }}</th>
                <th style="border: 1px solid #ddd; padding: 6px;">{{ number_format($data['batch_slaughter']->sum('total_weight'), 1) }}</th>
                <th style="border: 1px solid #ddd; padding: 6px;">{{ number_format($data['batch_slaughter']->sum('total_weight') / $data['batch_slaughter']->sum('count'), 1) }}</th>
                <th style="border: 1px solid #ddd; padding: 6px;" colspan="2">₦{{ number_format($data['batch_slaughter']->sum('total_amount'), 2) }}</th>
                <th style="border: 1px solid #ddd; padding: 6px;" colspan="2">{{ $data['batch_slaughter']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<!-- Individual Animal Slaughter Records -->
@if($data['individual_slaughter']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-paw"></i> Individual Animal Slaughter Records</h5>
<div>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Animal</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Weight (kg)</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Price/kg</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Amount</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Buyer</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Notes</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['individual_slaughter'] as $slaughter)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $slaughter->slaughter_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">#{{ $slaughter->individualAnimal->animal_id ?? 'N/A' }} ({{ $slaughter->individualAnimal->animal_type ?? 'N/A' }})</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($slaughter->weight, 1) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $slaughter->price_per_kg ? '₦' . number_format($slaughter->price_per_kg, 2) : 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $slaughter->total_amount ? '₦' . number_format($slaughter->total_amount, 2) : 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $slaughter->buyer ?? 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ Str::limit($slaughter->notes ?? 'N/A', 50) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $slaughter->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">INDIVIDUAL TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ number_format($data['individual_slaughter']->sum('weight'), 1) }}</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">₦{{ number_format($data['individual_slaughter']->sum('total_amount'), 2) }}</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="3">{{ $data['individual_slaughter']->count() }} animals</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['batch_slaughter']->count() === 0 && $data['individual_slaughter']->count() === 0)
<div style="text-align: center; padding: 40px 0;">
    <i class="fas fa-cut" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
    <h5 style="color: #6c757d; margin-bottom: 10px;">No slaughter records found for the selected period</h5>
    <p style="color: #6c757d;">No animals were slaughtered during this time period.</p>
</div>
@endif
