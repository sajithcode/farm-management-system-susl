<!-- Death Report Summary -->
<table class="summary-cards">
    <tr>
        <td>
            <div class="summary-card danger">
                <h3>{{ $data['summary']['total_deaths'] }}</h3>
                <p>Total Deaths</p>
            </div>
        </td>
        <td>
            <div class="summary-card warning">
                <h3>{{ $data['summary']['batch_deaths'] }}</h3>
                <p>Batch Deaths</p>
            </div>
        </td>
        <td>
            <div class="summary-card info">
                <h3>{{ $data['summary']['individual_deaths'] }}</h3>
                <p>Individual Deaths</p>
            </div>
        </td>
        <td>
            <div class="summary-card primary">
                <h3>{{ $data['batch_deaths']->count() + $data['individual_deaths']->count() }}</h3>
                <p>Total Records</p>
            </div>
        </td>
    </tr>
</table>

<!-- Batch Death Records -->
@if($data['batch_deaths']->count() > 0)
<h5 class="section-title">Batch Death Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Batch</th>
            <th>Count</th>
            <th>Cause</th>
            <th>Notes</th>
            <th>Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['batch_deaths'] as $death)
        <tr>
            <td>{{ $death->death_date->format('M d, Y') }}</td>
            <td>#{{ $death->batch->batch_id ?? 'N/A' }} ({{ $death->batch->animal_type ?? 'N/A' }})</td>
            <td>{{ $death->count }}</td>
            <td>{{ $death->cause ?? 'Not specified' }}</td>
            <td>{{ Str::limit($death->notes ?? 'N/A', 50) }}</td>
            <td>{{ $death->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">BATCH TOTALS</th>
            <th>{{ $data['batch_deaths']->sum('count') }}</th>
            <th colspan="3">{{ $data['batch_deaths']->count() }} records</th>
        </tr>
    </tfoot>
</table>
@endif

<!-- Individual Animal Death Records -->
@if($data['individual_deaths']->count() > 0)
<h5 class="section-title">Individual Animal Death Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Animal</th>
            <th>Cause</th>
            <th>Weight</th>
            <th>Notes</th>
            <th>Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['individual_deaths'] as $death)
        <tr>
            <td>{{ $death->death_date->format('M d, Y') }}</td>
            <td>#{{ $death->individualAnimal->animal_id ?? 'N/A' }} ({{ $death->individualAnimal->animal_type ?? 'N/A' }})</td>
            <td>{{ $death->cause ?? 'Not specified' }}</td>
            <td>{{ $death->weight ? number_format($death->weight, 1) . ' kg' : 'N/A' }}</td>
            <td>{{ Str::limit($death->notes ?? 'N/A', 50) }}</td>
            <td>{{ $death->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">INDIVIDUAL TOTALS</th>
            <th colspan="2">{{ $data['individual_deaths']->count() }} deaths</th>
            <th colspan="2">{{ number_format($data['individual_deaths']->sum('weight'), 1) }} kg total weight</th>
        </tr>
    </tfoot>
</table>
@endif

@if($data['batch_deaths']->count() === 0 && $data['individual_deaths']->count() === 0)
<div class="no-data">
    <h5>No death records found for the selected period</h5>
    <p>This is good news - no mortality recorded!</p>
</div>
@endif