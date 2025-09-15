<!-- Medicine Report Summary -->
<table class="summary-cards">
    <tr>
        <td>
            <div class="summary-card info">
                <h3>{{ $data['summary']['total_records'] }}</h3>
                <p>Total Treatments</p>
            </div>
        </td>
        <td>
            <div class="summary-card success">
                <h3>{{ $data['summary']['unique_medicines'] }}</h3>
                <p>Different Medicines</p>
            </div>
        </td>
        <td>
            <div class="summary-card warning">
                <h3>{{ $data['summary']['batch_treatments'] }}</h3>
                <p>Batch Treatments</p>
            </div>
        </td>
        <td>
            <div class="summary-card primary">
                <h3>{{ $data['summary']['individual_treatments'] }}</h3>
                <p>Individual Treatments</p>
            </div>
        </td>
    </tr>
</table>

@if($data['summary']['total_cost'] > 0)
<div class="meta-info">
    <table>
        <tr>
            <td class="label">Total Medicine Cost:</td>
            <td colspan="3">${{ number_format($data['summary']['total_cost'], 2) }}</td>
        </tr>
    </table>
</div>
@endif

<!-- Medicine Usage Summary -->
@if($data['medicine_usage'] && $data['medicine_usage']->count() > 0)
<h5 class="section-title">Medicine Usage Summary</h5>
<table>
    <thead>
        <tr>
            <th>Medicine Name</th>
            <th>Application Type</th>
            <th>Total Uses</th>
            <th>Total Dosage</th>
            <th>Total Cost</th>
            <th>Usage %</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['medicine_usage'] as $usage)
        <tr>
            <td>{{ $usage->medicine_name }}</td>
            <td>
                <span class="badge 
                    @if($usage->treatment_type === 'Batch') warning
                    @elseif($usage->treatment_type === 'Individual') success
                    @else info
                    @endif">
                    {{ $usage->treatment_type }}
                </span>
            </td>
            <td>{{ $usage->usage_count }}</td>
            <td>{{ number_format($usage->total_dosage, 2) }} {{ $usage->dosage_unit }}</td>
            <td>${{ number_format($usage->total_cost, 2) }}</td>
            <td>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $data['summary']['total_records'] > 0 ? ($usage->usage_count / $data['summary']['total_records']) * 100 : 0 }}%"></div>
                </div>
                {{ $data['summary']['total_records'] > 0 ? number_format(($usage->usage_count / $data['summary']['total_records']) * 100, 1) : 0 }}%
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<!-- Detailed Medicine Records -->
@if($data['medicine_records'] && $data['medicine_records']->count() > 0)
<h5 class="section-title">Medicine Administration Records</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Medicine</th>
            <th>Apply To</th>
            <th>Target</th>
            <th>Quantity</th>
            <th>Unit Cost</th>
            <th>Total Cost</th>
            <th>Administered By</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['medicine_records'] as $record)
        <tr>
            <td>{{ $record->medicine_date->format('M d, Y') }}</td>
            <td>
                <strong>{{ $record->medicine_name }}</strong>
            </td>
            <td>
                <span class="badge 
                    @if($record->apply_to === 'batch') warning
                    @elseif($record->apply_to === 'individual') success
                    @else info
                    @endif">
                    {{ ucfirst($record->apply_to) }}
                </span>
            </td>
            <td>
                @if($record->apply_to === 'batch' && $record->batch_id)
                    Batch #{{ $record->batch->batch_id ?? $record->batch_id }}
                    @if($record->batch && $record->batch->animal_type)
                        ({{ $record->batch->animal_type }})
                    @endif
                @elseif($record->apply_to === 'individual' && $record->animal_id)
                    Animal #{{ $record->individualAnimal->animal_id ?? $record->animal_id }}
                    @if($record->individualAnimal && $record->individualAnimal->animal_type)
                        ({{ $record->individualAnimal->animal_type }})
                    @endif
                @else
                    {{ $record->apply_to === 'batch' ? 'Batch #' . $record->batch_id : 'Animal #' . $record->animal_id }}
                @endif
            </td>
            <td>
                {{ number_format($record->quantity, 2) }} {{ $record->unit ?? 'units' }}
            </td>
            <td>
                @if($record->cost_per_unit)
                    ${{ number_format($record->cost_per_unit, 2) }}
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($record->cost_per_unit)
                    ${{ number_format($record->quantity * $record->cost_per_unit, 2) }}
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($record->administered_by)
                    {{ $record->administered_by }}
                @elseif($record->user)
                    {{ $record->user->name }}
                @else
                    N/A
                @endif
            </td>
            <td>{{ Str::limit($record->notes ?? 'No notes', 30) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">TOTALS</th>
            <th>{{ number_format($data['medicine_records']->sum('quantity'), 2) }} total quantity</th>
            <th></th>
            <th>
                ${{ number_format($data['medicine_records']->sum(function($record) { 
                    return $record->cost_per_unit ? $record->quantity * $record->cost_per_unit : 0; 
                }), 2) }}
            </th>
            <th colspan="2">{{ $data['medicine_records']->count() }} treatments</th>
        </tr>
    </tfoot>
</table>
@endif

@if($data['medicine_records']->count() === 0)
<div class="no-data">
    <h5>No medicine records found for the selected period</h5>
    <p>No medical treatments were administered during this time period.</p>
</div>
@endif