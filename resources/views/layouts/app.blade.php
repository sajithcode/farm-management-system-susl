<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Farm Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .stats-card {
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .quick-link-btn {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: calc(100vh - 56px);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-tractor me-2"></i>Farm Management System
            </a>
            
            @auth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            <span class="badge bg-secondary ms-1">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            @auth
            @if(Request::routeIs('dashboard'))
            <div class="col-md-2 sidebar p-3">
                <h6 class="text-muted mb-3">NAVIGATION</h6>
                <div class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link active">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                    <a href="#" class="nav-link">
                        <i class="fas fa-users me-2"></i>Batches
                    </a>
                    <a href="#" class="nav-link">
                        <i class="fas fa-paw me-2"></i>Animals
                    </a>
                    <a href="#" class="nav-link">
                        <i class="fas fa-seedling me-2"></i>Feed Management
                    </a>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar me-2"></i>Reports
                    </a>
                    @if(Auth::user()->role === 'admin')
                    <hr>
                    <h6 class="text-muted mb-3">ADMIN</h6>
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-cog me-2"></i>User Management
                    </a>
                    <a href="#" class="nav-link">
                        <i class="fas fa-cogs me-2"></i>Settings
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-md-10 main-content p-4">
            @else
            <div class="col-12 main-content p-4">
            @endif
            @else
            <div class="col-12 main-content">
            @endauth
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js (for future use) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
</body>
</html>
