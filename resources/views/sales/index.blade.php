@extends('layouts.app')

@section('title', 'All Sales Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-money-bill-wave me-2 text-success"></i>All Sales Records</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add New Sale
    </a>
</div>

@if($sales->count() > 0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Source</th>
                        <th>Quantity</th>
                        <th>Price ($)</th>
                        <th>Buyer</th>
                        <th>Recorded By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $sale->date->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $sale->item === 'eggs' ? 'warning' : 'danger' }} text-dark">
                                <i class="fas fa-{{ $sale->item === 'eggs' ? 'egg' : 'drumstick-bite' }} me-1"></i>
                                {{ ucfirst($sale->item) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $sale->source_name }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">{{ number_format($sale->quantity, 2) }}</span>
                            <small class="text-muted">{{ $sale->item === 'eggs' ? 'pcs' : 'kg' }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">${{ number_format($sale->price, 2) }}</span>
                        </td>
                        <td>
                            @if($sale->buyer)
                                <span class="text-info">{{ $sale->buyer }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $sale->user->name }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('sales.destroy', $sale) }}" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this sale record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted">
                    Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} results
                </small>
            </div>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4>${{ number_format($sales->sum('price'), 2) }}</h4>
                <small>Total Sales Value</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h4>{{ $sales->where('item', 'eggs')->count() }}</h4>
                <small>Egg Sales</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h4>{{ $sales->where('item', 'meat')->count() }}</h4>
                <small>Meat Sales</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4>{{ $sales->count() }}</h4>
                <small>Total Records</small>
            </div>
        </div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">No Sales Records Found</h4>
        <p class="text-muted">Start by adding your first sale record.</p>
        <a href="{{ route('sales.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Sale
        </a>
    </div>
</div>
@endif
@endsection
