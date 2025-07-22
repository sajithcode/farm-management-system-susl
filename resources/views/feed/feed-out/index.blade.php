@extends('layouts.app')

@section('title', 'Feed Out - Stock Issue')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-minus-circle me-2 text-warning"></i>Feed Out - Stock Issue
        </h1>
        <p class="text-muted mb-0">Issue feed stock to locations</p>
    </div>
    <div class="col-md-4 text-md-end">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addFeedOutModal">
            <i class="fas fa-minus me-2"></i>Issue Feed
        </button>
    </div>
</div>

<!-- Feed Out Table -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Recent Feed Issues
        </h5>
    </div>
    <div class="card-body">
        @if($feedOuts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Feed Type</th>
                        <th>Quantity</th>
                        <th>Issued To</th>
                        <th>Location</th>
                        <th>Issued By</th>
                        <th>Notes</th>
                        <th>Issue Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedOuts as $feedOut)
                    <tr>
                        <td>
                            <strong>{{ $feedOut->date->format('M d, Y') }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $feedOut->feedType->name }}</span>
                        </td>
                        <td>
                            <strong class="text-warning">{{ number_format($feedOut->quantity, 2) }}</strong>
                            <small class="text-muted">{{ $feedOut->feedType->unit }}</small>
                        </td>
                        <td>{{ $feedOut->issued_to }}</td>
                        <td>
                            <span class="badge bg-info">{{ $feedOut->location }}</span>
                        </td>
                        <td>
                            <small>{{ $feedOut->user->name }}</small>
                        </td>
                        <td>
                            @if($feedOut->notes)
                                <small class="text-muted">{{ Str::limit($feedOut->notes, 50) }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $feedOut->created_at->format('M d, Y H:i') }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $feedOuts->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No feed issues yet</h5>
            <p class="text-muted">Click "Issue Feed" to record your first feed distribution.</p>
        </div>
        @endif
    </div>
</div>

<!-- Add Feed Out Modal -->
<div class="modal fade" id="addFeedOutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('feed.out.store') }}">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-minus-circle me-2"></i>Issue Feed Stock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Date
                            </label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="feed_type_id" class="form-label">
                                <i class="fas fa-seedling me-1"></i>Feed Type
                            </label>
                            <select class="form-select @error('feed_type_id') is-invalid @enderror" 
                                    id="feed_type_id" 
                                    name="feed_type_id" 
                                    required 
                                    onchange="updateStockInfo()">
                                <option value="">Select Feed Type</option>
                                @foreach($feedTypes as $feedType)
                                <option value="{{ $feedType->id }}" 
                                        data-stock="{{ $feedType->current_stock }}"
                                        data-unit="{{ $feedType->unit }}"
                                        {{ old('feed_type_id') == $feedType->id ? 'selected' : '' }}>
                                    {{ $feedType->name }} (Available: {{ number_format($feedType->current_stock, 2) }} {{ $feedType->unit }})
                                </option>
                                @endforeach
                            </select>
                            @error('feed_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="stock-info" class="mt-1"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">
                                <i class="fas fa-weight me-1"></i>Quantity
                            </label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity') }}" 
                                   step="0.01" 
                                   min="0.01" 
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="issued_to" class="form-label">
                                <i class="fas fa-user me-1"></i>Issued To
                            </label>
                            <input type="text" 
                                   class="form-control @error('issued_to') is-invalid @enderror" 
                                   id="issued_to" 
                                   name="issued_to" 
                                   value="{{ old('issued_to') }}" 
                                   placeholder="Person receiving the feed"
                                   required>
                            @error('issued_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>Location
                        </label>
                        <input type="text" 
                               class="form-control @error('location') is-invalid @enderror" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}" 
                               placeholder="e.g., Batch A-001, Section 2, Individual Animal ID"
                               required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes <span class="text-muted">(optional)</span>
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Any additional notes about this issue...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Issue Feed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show modal if there are validation errors
    @if($errors->any())
        var addModal = new bootstrap.Modal(document.getElementById('addFeedOutModal'));
        addModal.show();
    @endif

    function updateStockInfo() {
        const select = document.getElementById('feed_type_id');
        const stockInfo = document.getElementById('stock-info');
        
        if (select.value) {
            const option = select.options[select.selectedIndex];
            const stock = option.dataset.stock;
            const unit = option.dataset.unit;
            
            if (parseFloat(stock) <= 0) {
                stockInfo.innerHTML = '<small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>No stock available!</small>';
            } else if (parseFloat(stock) < 10) {
                stockInfo.innerHTML = '<small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Low stock: ' + parseFloat(stock).toFixed(2) + ' ' + unit + '</small>';
            } else {
                stockInfo.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Available: ' + parseFloat(stock).toFixed(2) + ' ' + unit + '</small>';
            }
        } else {
            stockInfo.innerHTML = '';
        }
    }

    // Update stock info on page load if feed type is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        updateStockInfo();
    });
</script>
@endsection
