<!-- Death Report Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="text-center p-3 bg-danger text-white rounded">
            <h4>{{ $data['summary']['total_deaths'] }}</h4>
            <small>Total Deaths</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-center p-3 bg-warning text-dark rounded">
            <h4>{{ $data['summary']['batch_deaths'] }}</h4>
            <small>Batch Deaths</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-center p-3 bg-info text-white rounded">
            <h4>{{ $data['summary']['individual_deaths'] }}</h4>
            <small>Individual Deaths</small>
        </div>
    </div>
</div>

<!-- Batch Death Records -->
@if($data['batch_deaths']->count() > 0)
<h5 class="mb-3"><i class="fas fa-users me-2"></i>Batch Death Records</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
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
        <tfoot class="table-light">
            <tr>
                <th colspan="2">BATCH TOTALS</th>
                <th>{{ $data['batch_deaths']->sum('count') }}</th>
                <th colspan="3">{{ $data['batch_deaths']->count() }} records</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<!-- Individual Animal Death Records -->
@if($data['individual_deaths']->count() > 0)
<h5 class="mb-3"><i class="fas fa-paw me-2"></i>Individual Animal Death Records</h5>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-dark">
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
        <tfoot class="table-light">
            <tr>
                <th colspan="2">INDIVIDUAL TOTALS</th>
                <th colspan="2">{{ $data['individual_deaths']->count() }} deaths</th>
                <th colspan="2">{{ number_format($data['individual_deaths']->sum('weight'), 1) }} kg total weight</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if($data['batch_deaths']->count() === 0 && $data['individual_deaths']->count() === 0)
<div class="text-center py-5">
    <i class="fas fa-heart-broken fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">No death records found for the selected period</h5>
    <p class="text-muted">This is good news - no mortality recorded!</p>
</div>
@endif
