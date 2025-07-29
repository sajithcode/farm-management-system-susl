<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" 
      xmlns:w="urn:schemas-microsoft-com:office:word" 
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>90</w:Zoom>
            <w:DoNotPromptForConvert/>
            <w:DoNotRelyOnCSS/>
        </w:WordDocument>
    </xml>
    <![endif]-->
    <style>
        @page {
            margin: 1in;
            mso-header-margin: 0.5in;
            mso-footer-margin: 0.5in;
        }
        body {
            font-family: 'Calibri', sans-serif;
            font-size: 11pt;
            line-height: 1.2;
            color: #000000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2pt solid #000000;
            padding-bottom: 12pt;
            margin-bottom: 18pt;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #1f4e79;
            margin: 0;
        }
        .header h2 {
            font-size: 14pt;
            color: #70ad47;
            margin: 6pt 0 0 0;
            font-weight: normal;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18pt;
            background-color: #f2f2f2;
        }
        .meta-table td {
            padding: 6pt 12pt;
            border: 1pt solid #d9d9d9;
            font-size: 10pt;
        }
        .meta-table .label {
            font-weight: bold;
            background-color: #e7e6e6;
            width: 120pt;
        }
        .summary-section {
            margin-bottom: 18pt;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
        }
        .summary-card {
            width: 23%;
            display: inline-block;
            text-align: center;
            padding: 12pt;
            margin: 6pt;
            border: 2pt solid #70ad47;
            background-color: #f2f2f2;
            vertical-align: top;
        }
        .summary-card h3 {
            font-size: 16pt;
            font-weight: bold;
            color: #1f4e79;
            margin: 0;
        }
        .summary-card p {
            font-size: 9pt;
            color: #595959;
            margin: 3pt 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18pt;
            font-size: 9pt;
        }
        table th, table td {
            border: 1pt solid #a6a6a6;
            padding: 4pt 6pt;
            text-align: left;
        }
        table th {
            background-color: #1f4e79;
            color: white;
            font-weight: bold;
            font-size: 9pt;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tfoot th, table tfoot td {
            background-color: #e7e6e6;
            font-weight: bold;
            border-top: 2pt solid #1f4e79;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1f4e79;
            border-bottom: 1pt solid #70ad47;
            padding-bottom: 3pt;
            margin: 24pt 0 12pt 0;
            page-break-after: avoid;
        }
        .no-data {
            text-align: center;
            padding: 36pt;
            color: #a6a6a6;
            font-style: italic;
            font-size: 12pt;
        }
        .badge {
            display: inline-block;
            padding: 2pt 6pt;
            border-radius: 3pt;
            font-size: 8pt;
            font-weight: bold;
            color: white;
            background-color: #70ad47;
        }
        .badge.primary { background-color: #1f4e79; }
        .badge.success { background-color: #70ad47; }
        .badge.warning { background-color: #ffc000; color: #000000; }
        .badge.danger { background-color: #c55a5a; }
        .badge.info { background-color: #5b9bd5; }
        .page-break {
            page-break-before: always;
        }
        .footer {
            border-top: 1pt solid #d9d9d9;
            padding-top: 6pt;
            margin-top: 24pt;
            text-align: center;
            font-size: 8pt;
            color: #a6a6a6;
        }
        .progress-indicator {
            font-size: 8pt;
            color: #70ad47;
        }
        /* Word-specific formatting */
        @media print {
            .summary-card {
                width: 22%;
                display: table-cell;
            }
        }
    </style>
</head>
<body>
    <!-- Document Header -->
    <div class="header">
        <h1>🐄 Farm Management System</h1>
        <h2>{{ $title }}</h2>
    </div>

    <!-- Report Metadata -->
    <table class="meta-table">
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
            <td class="label">Animal Filter:</td>
            <td colspan="3">{{ ucfirst($animalType) }}</td>
        </tr>
        @endif
    </table>

    <!-- Executive Summary -->
    @if(isset($data['summary']) && is_array($data['summary']))
    <div class="summary-section">
        <h3 style="color: #1f4e79; margin-bottom: 12pt;">Executive Summary</h3>
        <div style="text-align: center;">
            @foreach(array_slice($data['summary'], 0, 4) as $key => $value)
            <div class="summary-card">
                <h3>{{ is_numeric($value) ? number_format($value, 1) : $value }}</h3>
                <p>{{ ucwords(str_replace('_', ' ', $key)) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Report Content -->
    <div class="report-content">
        @if($reportType === 'sales')
            @include('reports.partials.sales', ['data' => $data])
        @elseif($reportType === 'feed')
            @include('reports.partials.feed', ['data' => $data])
        @elseif($reportType === 'death')
            @include('reports.partials.death', ['data' => $data])
        @elseif($reportType === 'slaughter')
            @include('reports.partials.slaughter', ['data' => $data])
        @elseif($reportType === 'production')
            @include('reports.partials.production', ['data' => $data])
        @elseif($reportType === 'medicine')
            @include('reports.partials.medicine', ['data' => $data])
        @else
            <!-- Comprehensive Report -->
            @if(isset($data['sales']) && $data['sales']['summary']['total_sales'] > 0)
                <div class="section-title">💰 Sales Performance Analysis</div>
                @include('reports.partials.sales', ['data' => $data['sales']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['feed']) && $data['feed']['summary']['total_consumption'] > 0)
                <div class="section-title">🌾 Feed Consumption Analysis</div>
                @include('reports.partials.feed', ['data' => $data['feed']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['death']) && $data['death']['summary']['total_deaths'] > 0)
                <div class="section-title">💀 Mortality Analysis</div>
                @include('reports.partials.death', ['data' => $data['death']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['slaughter']) && $data['slaughter']['summary']['total_slaughtered'] > 0)
                <div class="section-title">🔪 Slaughter Records</div>
                @include('reports.partials.slaughter', ['data' => $data['slaughter']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['production']) && $data['production']['summary']['total_records'] > 0)
                <div class="section-title">🏭 Production Summary</div>
                @include('reports.partials.production', ['data' => $data['production']])
                <div class="page-break"></div>
            @endif

            @if(isset($data['medicine']) && $data['medicine']['summary']['total_records'] > 0)
                <div class="section-title">💊 Medical Treatment Records</div>
                @include('reports.partials.medicine', ['data' => $data['medicine']])
            @endif
        @endif
    </div>

    <!-- Document Footer -->
    <div class="footer">
        <p><strong>Farm Management System</strong> | Report generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This report contains confidential farm management data. Please handle with appropriate care.</p>
    </div>
</body>
</html>
