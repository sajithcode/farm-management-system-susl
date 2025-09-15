<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 16px;
            font-weight: normal;
        }
        .meta-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-info td {
            padding: 5px 10px;
            border: none;
        }
        .meta-info .label {
            font-weight: bold;
            color: #2c3e50;
            width: 120px;
        }
        .summary-cards {
            width: 100%;
            margin-bottom: 15px;
            page-break-inside: avoid;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        .summary-cards td {
            width: 25%;
            vertical-align: middle;
            padding: 0;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-card {
            background: #f8f9fa;
            color: #333;
            padding: 8px 10px;
            text-align: center;
            width: 100%;
            min-height: 40px;
            display: block;
            border: none;
            vertical-align: middle;
        }
        .summary-card.success { background: #f8f9fa; color: #333; }
        .summary-card.warning { background: #f8f9fa; color: #333; }
        .summary-card.danger { background: #f8f9fa; color: #333; }
        .summary-card.info { background: #f8f9fa; color: #333; }
        .summary-card.primary { background: #f8f9fa; color: #333; }
        .summary-card h3 {
            margin: 0;
            font-size: 16px;
            color: #1a1a1a;
            font-weight: bold;
        }
        .summary-card p {
            margin: 3px 0 0 0;
            font-size: 9px;
            color: #333333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tfoot th, table tfoot td {
            background-color: #ecf0f1;
            font-weight: bold;
            border-top: 2px solid #34495e;
            color: black;
        }
        .section-title {
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin: 25px 0 15px 0;
            font-size: 16px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: black;
        }
        .badge.primary { background: #3498db; }
        .badge.success { background: #27ae60; }
        .badge.warning { background: #f39c12; }
        .badge.danger { background: #e74c3c; }
        .badge.info { background: #17a2b8; }
        .page-break {
            page-break-before: always;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
        .progress-bar {
            background: #ecf0f1;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin: 2px 0;
        }
        .progress-fill {
            background: #3498db;
            height: 100%;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Farm Management System</h1>
        <h2>{{ $title }}</h2>
    </div>

    <!-- Report Meta Information -->
    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Report Type:</td>
                <td>{{ ucfirst($reportType) }} Report</td>
                <td class="label">Date Range:</td>
                <td>{{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Generated On:</td>
                <td>{{ now()->format('M d, Y h:i A') }}</td>
                <td class="label">Generated By:</td>
                <td>{{ Auth::user()->name }}</td>
            </tr>
            @if($animalType !== 'all')
            <tr>
                <td class="label">Animal Type:</td>
                <td colspan="3">{{ ucfirst($animalType) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Summary Cards -->
    @if(isset($data['summary']) && is_array($data['summary']))
    <table class="summary-cards">
        <tr>
        @foreach(array_slice($data['summary'], 0, 4) as $key => $value)
            <td>
                <div class="summary-card {{ $loop->index % 4 === 0 ? 'primary' : ($loop->index % 4 === 1 ? 'success' : ($loop->index % 4 === 2 ? 'warning' : 'info')) }}">
                    <h3>{{ is_numeric($value) ? number_format($value, 1) : $value }}</h3>
                    <p>{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                </div>
            </td>
        @endforeach
        </tr>
    </table>
    @endif

    <!-- Report Content -->
    <div class="report-content">
        @if($reportType === 'sales')
            @include('reports.partials.sales-pdf', ['data' => $data])
        @elseif($reportType === 'feed')
            @include('reports.partials.feed-pdf', ['data' => $data])
        @elseif($reportType === 'death')
            @include('reports.partials.death-pdf', ['data' => $data])
        @elseif($reportType === 'slaughter')
            @include('reports.partials.slaughter-pdf', ['data' => $data])
        @elseif($reportType === 'production')
            @include('reports.partials.production-pdf', ['data' => $data])
        @elseif($reportType === 'medicine')
            @include('reports.partials.medicine-pdf', ['data' => $data])
        @else
            <!-- Combined Report -->
            @if(isset($data['sales']) && $data['sales']['summary']['total_sales'] > 0)
                <h3 class="section-title">Sales Report</h3>
                @include('reports.partials.sales-pdf', ['data' => $data['sales']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['feed']) && $data['feed']['summary']['total_consumption'] > 0)
                <h3 class="section-title">Feed Report</h3>
                @include('reports.partials.feed-pdf', ['data' => $data['feed']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['death']) && $data['death']['summary']['total_deaths'] > 0)
                <h3 class="section-title">Death Report</h3>
                @include('reports.partials.death-pdf', ['data' => $data['death']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['slaughter']) && $data['slaughter']['summary']['total_slaughtered'] > 0)
                <h3 class="section-title">Slaughter Report</h3>
                @include('reports.partials.slaughter-pdf', ['data' => $data['slaughter']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['production']) && $data['production']['summary']['total_records'] > 0)
                <h3 class="section-title">Production Report</h3>
                @include('reports.partials.production-pdf', ['data' => $data['production']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['medicine']) && $data['medicine']['summary']['total_records'] > 0)
                <h3 class="section-title">Medicine Report</h3>
                @include('reports.partials.medicine-pdf', ['data' => $data['medicine']])
            @endif
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Farm Management System | Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
