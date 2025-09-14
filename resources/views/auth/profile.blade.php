@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
@php $editMode = request('edit') === '1'; @endphp
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <div class="me-3">
                        <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('assets/images/default-profile.png') }}" class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover;">
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $user->name }}</h4>
                        <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                        <span class="text-light ms-2"><i class="fas fa-calendar-alt me-1"></i>Joined: {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(!$editMode)
                        <div class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-4 text-muted">Name:</div>
                                <div class="col-sm-8 fw-bold">{{ $user->name }}</div>
                                <div class="col-sm-4 text-muted">Email:</div>
                                <div class="col-sm-8 fw-bold">{{ $user->email }}</div>
                                <div class="col-sm-4 text-muted">Location:</div>
                                <div class="col-sm-8 fw-bold">{{ $user->location ?? '-' }}</div>
                                <div class="col-sm-4 text-muted">Role:</div>
                                <div class="col-sm-8 fw-bold">{{ ucfirst($user->role) }}</div>
                                <div class="col-sm-4 text-muted">Join Date:</div>
                                <div class="col-sm-8 fw-bold">{{ $user->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="?edit=1" class="btn btn-warning px-4">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $user->location) }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*">
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                                <a href="{{ route('profile') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
