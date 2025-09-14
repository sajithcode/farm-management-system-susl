<!-- Medicine Report Summary -->
<div class="summary-container">
    <div class="summary-card bg-info">
        <div class="card-value">{{ $data['summary']['total_records'] }}</div>
        <div class="card-label">Total Treatments</div>
    </div>
    <div class="summary-card bg-success">
        <div class="card-value">{{ $data['summary']['unique_medicines'] ?? 0 }}</div>
        <div class="card-label">Different Medicines</div>
    </div>
    <div class="summary-card bg-warning">
        <div class="card-value">{{ $data['summary']['batch_treatments'] ?? 0 }}</div>
        <div class="card-label">Batch Treatments</div>
    </div>
    <div class="summary-card bg-primary">
        <div class="card-value">${{ number_format($data['summary']['total_cost'], 2) }}</div>
        <div class="card-label">Total Cost</div>
    </div>
</div>

<!-- Medicine Usage Summary -->
@if(isset($data['medicine_usage']) && $data['medicine_usage']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-pills"></i> Medicine Usage Summary</h5>
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Medicine Name</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Application Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Uses</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Dosage</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Cost</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Usage Frequency</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['medicine_usage'] as $usage)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 8px;"><i class="fas fa-capsules"></i> {{ $usage->medicine_name }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">
                    <span style="padding: 2px 6px; border-radius: 3px; font-size: 10px; color: white; background-color: 
                        @if($usage->treatment_type === 'Batch') #ffc107
                        @elseif($usage->treatment_type === 'Individual') #28a745
                        @else #6c757d
                        @endif;">
                        {{ $usage->treatment_type }}
                    </span>
                </td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $usage->usage_count }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($usage->total_dosage, 2) }} {{ $usage->dosage_unit }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">${{ number_format($usage->total_cost, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">
                    <div style="background-color: #e9ecef; height: 20px; border-radius: 4px; position: relative;">
                        <div style="background-color: #17a2b8; height: 20px; border-radius: 4px; width: {{ $data['summary']['total_records'] > 0 ? ($usage->usage_count / $data['summary']['total_records']) * 100 : 0 }}%; color: white; text-align: center; line-height: 20px; font-size: 11px;">
                            {{ $data['summary']['total_records'] > 0 ? number_format(($usage->usage_count / $data['summary']['total_records']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Detailed Medicine Records -->
@if(isset($data['medicine_records']) && $data['medicine_records']->count() > 0)
<h5 style="margin: 20px 0 15px 0;"><i class="fas fa-list-alt"></i> Medicine Administration Records</h5>
<div>
    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
        <thead>
            <tr style="background-color: #343a40; color: white;">
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Date</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Medicine</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Apply To</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Target</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Quantity</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Unit Cost</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Total Cost</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Administered By</th>
                <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['medicine_records'] as $record)
            <tr style="background-color: {{ $loop->even ? '#f8f9fa' : 'white' }};">
                <td style="border: 1px solid #ddd; padding: 6px;">{{ $record->medicine_date->format('M d, Y') }}</td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    <strong>{{ $record->medicine_name }}</strong>
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    <span style="padding: 2px 6px; border-radius: 3px; font-size: 10px; color: white; background-color: 
                        @if($record->apply_to === 'batch') #ffc107
                        @elseif($record->apply_to === 'individual') #28a745
                        @else #6c757d
                        @endif;">
                        {{ ucfirst($record->apply_to) }}
                    </span>
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    @if($record->apply_to === 'batch' && $record->batch_id)
                        <i class="fas fa-users"></i> Batch #{{ $record->batch->batch_id ?? $record->batch_id }}
                        @if($record->batch && $record->batch->animal_type)
                            <br><small style="color: #6c757d;">{{ $record->batch->animal_type }}</small>
                        @endif
                    @elseif($record->apply_to === 'individual' && $record->animal_id)
                        <i class="fas fa-paw"></i> Animal #{{ $record->individualAnimal->animal_id ?? $record->animal_id }}
                        @if($record->individualAnimal && $record->individualAnimal->animal_type)
                            <br><small style="color: #6c757d;">{{ $record->individualAnimal->animal_type }}</small>
                        @endif
                    @else
                        <i class="fas fa-question-circle"></i> {{ $record->apply_to === 'batch' ? 'Batch #' . $record->batch_id : 'Animal #' . $record->animal_id }}
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    {{ number_format($record->quantity, 2) }} {{ $record->unit ?? 'units' }}
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    @if($record->cost_per_unit)
                        ${{ number_format($record->cost_per_unit, 2) }}
                    @else
                        <span style="color: #6c757d;">N/A</span>
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    @if($record->cost_per_unit)
                        ${{ number_format($record->quantity * $record->cost_per_unit, 2) }}
                    @else
                        <span style="color: #6c757d;">N/A</span>
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">
                    @if($record->administered_by)
                        {{ $record->administered_by }}
                    @elseif($record->user)
                        {{ $record->user->name }}
                    @else
                        <span style="color: #6c757d;">N/A</span>
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 6px;">{{ Str::limit($record->notes ?? 'No notes', 30) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <th style="border: 1px solid #ddd; padding: 6px;" colspan="4">TOTALS</th>
                <th style="border: 1px solid #ddd; padding: 6px;">{{ number_format($data['medicine_records']->sum('quantity'), 2) }} total quantity</th>
                <th style="border: 1px solid #ddd; padding: 6px;"></th>
                <th style="border: 1px solid #ddd; padding: 6px;">
                    ${{ number_format($data['medicine_records']->sum(function($record) { 
                        return $record->cost_per_unit ? $record->quantity * $record->cost_per_unit : 0; 
                    }), 2) }}
                </th>
                <th style="border: 1px solid #ddd; padding: 6px;" colspan="2">{{ $data['medicine_records']->count() }} treatments</th>
            </tr>
        </tfoot>
    </table>
</div>
@endif

@if(!isset($data['medicine_records']) || $data['medicine_records']->count() === 0)
<div style="text-align: center; padding: 40px 0;">
    <i class="fas fa-first-aid" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
    <h5 style="color: #6c757d; margin-bottom: 10px;">No medicine records found for the selected period</h5>
    <p style="color: #6c757d;">No medical treatments were administered during this time period.</p>
</div>
@endif
