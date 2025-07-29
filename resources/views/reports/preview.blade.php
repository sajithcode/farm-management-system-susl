@extends('layouts.app')

@section('title', 'Report Preview')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-eye me-2 text-info"></i>Report Preview</h1>
    <div>
        <a href="{{ route('reports.generate') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Generate
        </a>
        <div class="btn-group">
            <form method="POST" action="{{ route('reports.export.pdf') }}" class="d-inline">
                @csrf
                <input type="hidden" name="report_type" value="{{ $validatedData['report_type'] }}">
                <input type="hidden" name="start_date" value="{{ $validatedData['start_date'] }}">
                <input type="hidden" name="end_date" value="{{ $validatedData['end_date'] }}">
                <input type="hidden" name="location" value="{{ $validatedData['location'] ?? '' }}">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </button>
            </form>
            <form method="POST" action="{{ route('reports.export.word') }}" class="d-inline">
                @csrf
                <input type="hidden" name="report_type" value="{{ $validatedData['report_type'] }}">
                <input type="hidden" name="start_date" value="{{ $validatedData['start_date'] }}">
                <input type="hidden" name="end_date" value="{{ $validatedData['end_date'] }}">
                <input type="hidden" name="location" value="{{ $validatedData['location'] ?? '' }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-word me-2"></i>Export Word
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Report Header -->
<div class="card mb-4">
    <div class="card-body bg-light">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-1">{{ $reportData['title'] }}</h3>
                <p class="text-muted mb-0">
                    Period: {{ Carbon\Carbon::parse($validatedData['start_date'])->format('M d, Y') }} - 
                    {{ Carbon\Carbon::parse($validatedData['end_date'])->format('M d, Y') }}
                </p>
                @if($validatedData['location'])
                    <p class="text-muted mb-0">Location: {{ $validatedData['location'] }}</p>
                @endif
            </div>
            <div class="col-md-4 text-md-end">
                <p class="mb-0"><strong>Generated:</strong> {{ now()->format('M d, Y g:i A') }}</p>
                <p class="mb-0"><strong>By:</strong> {{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Report Content -->
<div class="card">
    <div class="card-body">
        @if($validatedData['report_type'] === 'sales')
            @include('reports.partials.sales', ['data' => $reportData])
        @elseif($validatedData['report_type'] === 'feed')
            @include('reports.partials.feed', ['data' => $reportData])
        @elseif($validatedData['report_type'] === 'death')
            @include('reports.partials.death', ['data' => $reportData])
        @elseif($validatedData['report_type'] === 'slaughter')
            @include('reports.partials.slaughter', ['data' => $reportData])
        @elseif($validatedData['report_type'] === 'production')
            @include('reports.partials.production', ['data' => $reportData])
        @elseif($validatedData['report_type'] === 'medicine')
            @include('reports.partials.medicine', ['data' => $reportData])
        @endif
    </div>
</div>

<!-- Print Button -->
<div class="text-center mt-4">
    <button onclick="window.print()" class="btn btn-outline-secondary">
        <i class="fas fa-print me-2"></i>Print Preview
    </button>
</div>
@endsection

@section('styles')
<style>
@media print {
    .btn, .card-header, .navbar, .sidebar {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .main-content {
        padding: 0 !important;
    }
}
</style>
@endsection
