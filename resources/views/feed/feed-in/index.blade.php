@extends('layouts.app')

@section('title', 'Feed In - Stock Entry')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus-circle me-2 text-success"></i>Feed In - Stock Entry
        </h1>
        <p class="text-muted mb-0">Record incoming feed stock</p>
    </div>
    <div class="col-md-4 text-md-end">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFeedInModal">
            <i class="fas fa-plus me-2"></i>Add Stock Entry
        </button>
    </div>
</div>

<!-- Feed In Table -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Recent Feed Stock Entries
        </h5>
    </div>
    <div class="card-body">
        @if($feedIns->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Feed Type</th>
                        <th>Quantity</th>
                        <th>Supplier</th>
                        <th>Recorded By</th>
                        <th>Notes</th>
                        <th>Entry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedIns as $feedIn)
                    <tr>
                        <td>
                            <strong>{{ $feedIn->date->format('M d, Y') }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $feedIn->feedType->name }}</span>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($feedIn->quantity, 2) }}</strong>
                            <small class="text-muted">{{ $feedIn->feedType->unit }}</small>
                        </td>
                        <td>{{ $feedIn->supplier }}</td>
                        <td>
                            <small>{{ $feedIn->user->name }}</small>
                        </td>
                        <td>
                            @if($feedIn->notes)
                                <small class="text-muted">{{ Str::limit($feedIn->notes, 50) }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $feedIn->created_at->format('M d, Y H:i') }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $feedIns->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No feed stock entries yet</h5>
            <p class="text-muted">Click "Add Stock Entry" to record your first feed delivery.</p>
        </div>
        @endif
    </div>
</div>

<!-- Add Feed In Modal -->
<div class="modal fade" id="addFeedInModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('feed.in.store') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Add Feed Stock Entry
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                    required>
                                <option value="">Select Feed Type</option>
                                @foreach($feedTypes as $feedType)
                                <option value="{{ $feedType->id }}" {{ old('feed_type_id') == $feedType->id ? 'selected' : '' }}>
                                    {{ $feedType->name }} ({{ $feedType->unit }})
                                </option>
                                @endforeach
                            </select>
                            @error('feed_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label for="supplier" class="form-label">
                                <i class="fas fa-truck me-1"></i>Supplier
                            </label>
                            <input type="text" 
                                   class="form-control @error('supplier') is-invalid @enderror" 
                                   id="supplier" 
                                   name="supplier" 
                                   value="{{ old('supplier') }}" 
                                   required>
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>Notes <span class="text-muted">(optional)</span>
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Any additional notes about this delivery...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Entry
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
        var addModal = new bootstrap.Modal(document.getElementById('addFeedInModal'));
        addModal.show();
    @endif
</script>
@endsection
