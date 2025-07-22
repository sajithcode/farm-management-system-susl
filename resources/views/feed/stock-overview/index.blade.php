@extends('layouts.app')

@section('title', 'Feed Stock Overview')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-chart-bar me-2 text-info"></i>Feed Stock Overview
        </h1>
        <p class="text-muted mb-0">Monitor feed inventory levels and usage</p>
    </div>
    <div class="col-md-4 text-md-end">
        @if(Auth::user()->role === 'admin')
        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addFeedTypeModal">
            <i class="fas fa-plus me-2"></i>Add Feed Type
        </button>
        @endif
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter me-2"></i>Filter Data
        </button>
    </div>
</div>

<!-- Current Filters -->
@if($feedTypeFilter || $dateFrom || $dateTo)
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Active Filters:</strong>
    @if($feedTypeFilter)
        Feed Type: <span class="badge bg-primary">{{ $allFeedTypes->find($feedTypeFilter)->name ?? 'Unknown' }}</span>
    @endif
    @if($dateFrom)
        From: <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }}</span>
    @endif
    @if($dateTo)
        To: <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</span>
    @endif
    <a href="{{ route('feed.stock.overview') }}" class="btn btn-sm btn-outline-info ms-2">
        <i class="fas fa-times me-1"></i>Clear Filters
    </a>
</div>
@endif

<!-- Stock Overview Cards -->
<div class="row mb-4">
    @php
        $totalStockValue = collect($stockData)->sum('current_stock');
        $totalInValue = collect($stockData)->sum('total_in');
        $totalOutValue = collect($stockData)->sum('total_out');
        $lowStockItems = collect($stockData)->filter(function($item) { return $item['current_stock'] < 10; })->count();
    @endphp
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Total Stock In</h5>
                    <h2 class="mb-0">{{ number_format($totalInValue, 2) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-plus-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Total Stock Out</h5>
                    <h2 class="mb-0">{{ number_format($totalOutValue, 2) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-minus-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Current Stock</h5>
                    <h2 class="mb-0">{{ number_format($totalStockValue, 2) }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-warehouse fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1">Low Stock Items</h5>
                    <h2 class="mb-0">{{ $lowStockItems }}</h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Details Table -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="fas fa-table me-2"></i>Feed Stock Details
        </h5>
    </div>
    <div class="card-body">
        @if(count($stockData) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Feed Type</th>
                        <th>Unit</th>
                        <th class="text-center">Total In</th>
                        <th class="text-center">Total Out</th>
                        <th class="text-center">Current Stock</th>
                        <th class="text-center">Stock Status</th>
                        <th class="text-center">Usage Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockData as $data)
                    <tr>
                        <td>
                            <strong>{{ $data['feed_type']->name }}</strong>
                            @if($data['feed_type']->description)
                                <br><small class="text-muted">{{ $data['feed_type']->description }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $data['feed_type']->unit }}</span>
                        </td>
                        <td class="text-center">
                            <strong class="text-success">{{ number_format($data['total_in'], 2) }}</strong>
                        </td>
                        <td class="text-center">
                            <strong class="text-warning">{{ number_format($data['total_out'], 2) }}</strong>
                        </td>
                        <td class="text-center">
                            <strong class="text-info">{{ number_format($data['current_stock'], 2) }}</strong>
                        </td>
                        <td class="text-center">
                            @if($data['current_stock'] <= 0)
                                <span class="badge bg-danger">Out of Stock</span>
                            @elseif($data['current_stock'] < 10)
                                <span class="badge bg-warning">Low Stock</span>
                            @elseif($data['current_stock'] < 50)
                                <span class="badge bg-info">Medium Stock</span>
                            @else
                                <span class="badge bg-success">Good Stock</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $usageRate = $data['total_in'] > 0 ? ($data['total_out'] / $data['total_in']) * 100 : 0;
                            @endphp
                            <span class="text-muted">{{ number_format($usageRate, 1) }}%</span>
                            <div class="progress mt-1" style="height: 4px;">
                                <div class="progress-bar 
                                    @if($usageRate > 80) bg-danger 
                                    @elseif($usageRate > 60) bg-warning 
                                    @else bg-success @endif" 
                                    style="width: {{ min($usageRate, 100) }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No stock data available</h5>
            <p class="text-muted">Start by adding feed types and recording stock entries.</p>
            
            @if(Auth::user()->role === 'admin')
            <div class="mt-4">
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addFeedTypeModal">
                    <i class="fas fa-plus me-2"></i>Add Your First Feed Type
                </button>
            </div>
            @endif
            
            <!-- Show available feed types -->
            @if($allFeedTypes->count() > 0)
            <div class="mt-4">
                <h6 class="text-muted mb-3">Available Feed Types:</h6>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach($allFeedTypes as $feedType)
                    <span class="badge bg-secondary p-2">
                        {{ $feedType->name }} ({{ $feedType->unit }})
                    </span>
                    @endforeach
                </div>
                <small class="text-muted mt-2 d-block">
                    Go to <a href="{{ route('feed.in.index') }}">Feed In</a> to start recording stock entries.
                </small>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Add Feed Type Modal (Admin Only) -->
@if(Auth::user()->role === 'admin')
<div class="modal fade" id="addFeedTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('feed.types.store') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Add New Feed Type
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag me-1"></i>Feed Type Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g., Premium Starter Feed"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="unit" class="form-label">
                            <i class="fas fa-ruler me-1"></i>Unit of Measurement <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('unit') is-invalid @enderror" 
                                id="unit" 
                                name="unit" 
                                required>
                            <option value="">Select Unit</option>
                            <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                            <option value="lbs" {{ old('unit') === 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                            <option value="tons" {{ old('unit') === 'tons' ? 'selected' : '' }}>Tons</option>
                            <option value="bags" {{ old('unit') === 'bags' ? 'selected' : '' }}>Bags</option>
                            <option value="bales" {{ old('unit') === 'bales' ? 'selected' : '' }}>Bales</option>
                            <option value="litres" {{ old('unit') === 'litres' ? 'selected' : '' }}>Litres</option>
                            <option value="gallons" {{ old('unit') === 'gallons' ? 'selected' : '' }}>Gallons</option>
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>Description <span class="text-muted">(optional)</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Brief description of this feed type...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Once created, you can immediately start recording stock entries for this feed type.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Create Feed Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="{{ route('feed.stock.overview') }}">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-filter me-2"></i>Filter Stock Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="feed_type" class="form-label">
                            <i class="fas fa-seedling me-1"></i>Feed Type
                        </label>
                        <select class="form-select" id="feed_type" name="feed_type">
                            <option value="">All Feed Types</option>
                            @foreach($allFeedTypes as $feedType)
                            <option value="{{ $feedType->id }}" {{ $feedTypeFilter == $feedType->id ? 'selected' : '' }}>
                                {{ $feedType->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_from" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Date From
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ $dateFrom }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date_to" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Date To
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="{{ $dateTo }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-search me-2"></i>Apply Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show Add Feed Type modal if there are validation errors for feed type creation
    @if($errors->any() && (old('name') || old('unit') || old('description')))
        @if(Auth::user()->role === 'admin')
            var addFeedTypeModal = new bootstrap.Modal(document.getElementById('addFeedTypeModal'));
            addFeedTypeModal.show();
        @endif
    @endif

    // Auto-refresh page after successful feed type creation
    @if(session('success') && str_contains(session('success'), 'Feed type'))
        // Show success message and refresh the page to show new feed type
        setTimeout(function() {
            location.reload();
        }, 2000);
    @endif
</script>
@endsection
