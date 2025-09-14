@extends('layouts.app')

@section('title', 'Profile - Farm Management System')

@section('content')
<div class="container mt-4">
    <div class="card mx-auto shadow-lg border-0" style="max-width: 900px;">
        <!-- Cover Image -->
        <div class="position-relative" style="height: 240px; background: linear-gradient(90deg,#6dd5ed,#2193b0);">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=900&q=80" 
                 class="w-100 h-100 object-fit-cover" style="opacity:0.25;">
            <!-- Avatar -->
            <div class="position-absolute top-100 start-50 translate-middle" style="z-index:2;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2193b0&color=fff&size=140"
                     class="rounded-circle border border-4 border-white shadow" width="140" height="140" alt="Avatar">
            </div>
        </div>
        <div class="card-body pt-5">
            <div class="row">
                <div class="col-md-6 text-center mb-3 mb-md-0 d-flex flex-column justify-content-center">
                    <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'vc' ? 'warning' : 'info') }} mb-2 fs-6">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                    <p class="text-muted mb-0"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</p>
                    <p class="text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i>{{ $user->location ?? '-' }}</p>
                    <p class="text-muted mb-0"><i class="fas fa-calendar-alt me-1"></i>Joined: {{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
                    <table class="table table-borderless w-75">
                        <tr>
                            <th class="text-end">Name:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">Role:</th>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'vc' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end">Location:</th>
                            <td>{{ $user->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">Joined:</th>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button class="btn btn-outline-primary px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-1"></i>Edit Profile
                        </button>
                        <button class="btn btn-outline-secondary px-4" disabled>
                            <i class="fas fa-key me-1"></i>Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editProfileModalLabel"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
          </div>
          <div class="mb-3">
            <label for="location" class="form-label fw-bold">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $user->location }}">
          </div>
          <!-- Add more creative fields as needed -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection