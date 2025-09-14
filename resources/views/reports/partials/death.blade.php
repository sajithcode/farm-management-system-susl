<!-- Death Report Summary -->
<div class="summary-container">
    <div class="summary-card bg-danger">
        <div class="card-value">{{ $data['summary']['total_deaths'] }}</div>
        <div class="card-label">Total Deaths</div>
    </div>
    <div class="summary-card bg-warning">
        <div class="card-value">{{ $data['summary']['batch_deaths'] }}</div>
        <div class="card-label">Batch Deaths</div>
    </div>
    <div class="summary-card bg-info">
        <div class="card-value">{{ $data['summary']['individual_deaths'] }}</div>
        <div class="card-label">Individual Deaths</div>
    </div>
    <div class="summary-card bg-secondary">
        <div class="card-value">{{ number_format($data['summary']['total_weight'] ?? 0, 1) }} kg</div>
        <div class="card-label">Total Weight Lost</div>
    </div>
</div>

<!-- Batch Death Records -->
@if($data['batch_deaths']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-users"></i> Batch Death Records</h5>
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Batch</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Count</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Cause</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Notes</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['batch_deaths'] as $death)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->death_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">#{{ $death->batch->batch_id ?? 'N/A' }} ({{ $death->batch->animal_type ?? 'N/A' }})</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->count }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->cause ?? 'Not specified' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ Str::limit($death->notes ?? 'N/A', 50) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">BATCH TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 8px;">{{ $data['batch_deaths']->sum('count') }}</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="3">{{ $data['batch_deaths']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<!-- Individual Animal Death Records -->
@if($data['individual_deaths']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-paw"></i> Individual Animal Death Records</h5>
<div>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Animal</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Cause</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Weight</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Notes</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['individual_deaths'] as $death)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->death_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">#{{ $death->individualAnimal->animal_id ?? 'N/A' }} ({{ $death->individualAnimal->animal_type ?? 'N/A' }})</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->cause ?? 'Not specified' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->weight ? number_format($death->weight, 1) . ' kg' : 'N/A' }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ Str::limit($death->notes ?? 'N/A', 50) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $death->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">INDIVIDUAL TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">{{ $data['individual_deaths']->count() }} deaths</th>
                <th style="border: 1px solid #ddd; padding: 8px;" colspan="2">{{ number_format($data['individual_deaths']->sum('weight'), 1) }} kg total weight</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['batch_deaths']->count() === 0 && $data['individual_deaths']->count() === 0)
<div style="text-align: center; padding: 40px 0;">
    <i class="fas fa-heart-broken" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
    <h5 style="color: #6c757d; margin-bottom: 10px;">No death records found for the selected period</h5>
    <p style="color: #6c757d;">This is good news - no mortality recorded!</p>
</div>
@endif
