<!-- Sales Report Summary -->
<table style="width: 100%; border-collapse: separate; border-spacing: 10px; margin-bottom: 20px;">
    <tr>
        <td style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; text-align: center; width: 25%;">
            <h4 style="margin: 0; font-size: 20px;">${{ number_format($data['summary']['total_revenue'], 2) }}</h4>
            <small style="font-size: 11px;">Total Revenue</small>
        </td>
        <td style="background: #3498db; color: white; padding: 15px; border-radius: 5px; text-align: center; width: 25%;">
            <h4 style="margin: 0; font-size: 20px;">{{ $data['summary']['sales_records'] ?? $data['summary']['total_records'] ?? 0 }}</h4>
            <small style="font-size: 11px;">Sales Records</small>
        </td>
        <td style="background: #17a2b8; color: white; padding: 15px; border-radius: 5px; text-align: center; width: 25%;">
            <h4 style="margin: 0; font-size: 20px;">{{ number_format($data['summary']['total_quantity'], 0) }}</h4>
            <small style="font-size: 11px;">Total Quantity</small>
        </td>
        <td style="background: #f39c12; color: #333; padding: 15px; border-radius: 5px; text-align: center; width: 25%;">
            <h4 style="margin: 0; font-size: 20px;">${{ number_format($data['summary']['avg_sale_value'] ?? ($data['summary']['total_revenue'] / max(($data['summary']['sales_records'] ?? $data['summary']['total_records'] ?? 1), 1)), 2) }}</h4>
            <small style="font-size: 11px;">Avg Sale Value</small>
        </td>
    </tr>
</table>

<!-- Sales Data Table -->
@if($data['data']->count() > 0)
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr style="background-color: #2c3e50; color: white;">
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Date</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Item</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Source</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Quantity</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Price</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Buyer</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px;">Recorded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['data'] as $sale)
        <tr style="{{ $loop->even ? 'background-color: #f9f9f9;' : '' }}">
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">{{ $sale->date->format('M d, Y') }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">
                <span style="background: {{ $sale->item === 'eggs' ? '#f39c12' : '#e74c3c' }}; color: {{ $sale->item === 'eggs' ? '#333' : 'white' }}; padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold;">
                    {{ ucfirst($sale->item) }}
                </span>
            </td>
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">{{ $sale->source_name }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">{{ number_format($sale->quantity, 2) }} {{ $sale->item === 'eggs' ? 'pcs' : 'kg' }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">${{ number_format($sale->price, 2) }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">{{ $sale->buyer ?: 'N/A' }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">{{ $sale->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #ecf0f1; font-weight: bold; border-top: 2px solid #34495e;">
            <th style="border: 1px solid #ddd; padding: 8px; font-size: 11px;" colspan="3">TOTALS</th>
            <th style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">{{ number_format($data['summary']['total_quantity'], 0) }}</th>
            <th style="border: 1px solid #ddd; padding: 8px; font-size: 11px;">${{ number_format($data['summary']['total_revenue'], 2) }}</th>
            <th style="border: 1px solid #ddd; padding: 8px; font-size: 11px;" colspan="2">{{ $data['summary']['sales_records'] ?? $data['summary']['total_records'] ?? 0 }} records</th>
        </tr>
    </tfoot>
</table>
@else
<div style="text-align: center; padding: 40px; color: #7f8c8d; font-style: italic;">
    <p style="font-size: 18px; margin-bottom: 10px;">📥</p>
    <h5 style="color: #7f8c8d;">No sales records found for the selected period</h5>
</div>
@endif
