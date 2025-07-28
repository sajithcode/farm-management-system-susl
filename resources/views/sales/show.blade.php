@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-money-bill-wave me-2 text-success"></i>Sale Details</h1>
    <div>
        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Sales
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Sale Information
                    <span class="badge bg-{{ $sale->item === 'eggs' ? 'warning' : 'danger' }} text-dark ms-2">
                        <i class="fas fa-{{ $sale->item === 'eggs' ? 'egg' : 'drumstick-bite' }} me-1"></i>
                        {{ ucfirst($sale->item) }}
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Sale Date:</td>
                                <td>{{ $sale->date->format('F d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Item:</td>
                                <td>
                                    <span class="badge bg-{{ $sale->item === 'eggs' ? 'warning' : 'danger' }} text-dark">
                                        <i class="fas fa-{{ $sale->item === 'eggs' ? 'egg' : 'drumstick-bite' }} me-1"></i>
                                        {{ ucfirst($sale->item) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Source:</td>
                                <td>{{ $sale->source_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Quantity:</td>
                                <td>
                                    <span class="fw-bold">{{ number_format($sale->quantity, 2) }}</span>
                                    <small class="text-muted">{{ $sale->item === 'eggs' ? 'pieces' : 'kg' }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Price:</td>
                                <td>
                                    <span class="fw-bold text-success fs-4">${{ number_format($sale->price, 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Buyer:</td>
                                <td>
                                    @if($sale->buyer)
                                        <span class="text-info">{{ $sale->buyer }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Recorded By:</td>
                                <td>{{ $sale->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Recorded On:</td>
                                <td>{{ $sale->created_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($sale->notes)
                <div class="mt-4">
                    <h6 class="fw-bold">Notes:</h6>
                    <div class="alert alert-light">
                        {{ $sale->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Source Details -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-{{ $sale->source_type === 'batch' ? 'users' : 'paw' }} me-2"></i>
                    {{ $sale->source_type === 'batch' ? 'Batch' : 'Animal' }} Information
                </h5>
            </div>
            <div class="card-body">
                @if($sale->source_type === 'batch')
                    @php
                        $batch = \App\Models\Batch::find($sale->source_id);
                    @endphp
                    @if($batch)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Batch ID:</td>
                                        <td>#{{ $batch->batch_id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Animal Type:</td>
                                        <td>{{ ucfirst($batch->animal_type) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Current Count:</td>
                                        <td>{{ $batch->current_count }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Initial Count:</td>
                                        <td>{{ $batch->initial_count }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Start Date:</td>
                                        <td>{{ $batch->start_date->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            <span class="badge bg-{{ $batch->current_count > 0 ? 'success' : 'secondary' }}">
                                                {{ $batch->current_count > 0 ? 'Active' : 'Depleted' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Batch information not available</p>
                    @endif
                @else
                    @php
                        $animal = \App\Models\IndividualAnimal::find($sale->source_id);
                    @endphp
                    @if($animal)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Animal ID:</td>
                                        <td>#{{ $animal->animal_id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Animal Type:</td>
                                        <td>{{ ucfirst($animal->animal_type) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            <span class="badge bg-{{ $animal->status === 'alive' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($animal->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Birth Date:</td>
                                        <td>{{ $animal->date_of_birth ? $animal->date_of_birth->format('M d, Y') : 'Not recorded' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Weight:</td>
                                        <td>{{ $animal->current_weight ? $animal->current_weight . ' kg' : 'Not recorded' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Added:</td>
                                        <td>{{ $animal->created_at->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Animal information not available</p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit me-2"></i>Edit Sale
                </a>
                <form method="POST" action="{{ route('sales.destroy', $sale) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this sale record?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash me-2"></i>Delete Sale
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Value:</span>
                    <span class="fw-bold text-success">${{ number_format($sale->price, 2) }}</span>
                </div>
                @if($sale->quantity > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Unit Price:</span>
                    <span class="fw-bold">${{ number_format($sale->price / $sale->quantity, 2) }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Record ID:</span>
                    <span class="text-muted">#{{ $sale->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
