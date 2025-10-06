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
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            color: #f8f9fa !important;
        }
        
        .navbar-logo {
            height: 45px;
            width: 112px;
            object-fit: contain;
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 3px solid rgba(255, 255, 255, 0.8);
            padding: 6px;
            margin-right: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover .navbar-logo {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 1);
        }
        
        .brand-text {
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white;
        }
        
        /* Alternative professional styles - choose one */
        .navbar-logo.style-dark {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .navbar-logo.style-green {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            border: 3px solid rgba(255, 255, 255, 0.4);
        }
        
        .navbar-logo.style-blue {
            background: linear-gradient(135deg, #3498db 0%, #5dade2 100%);
            border: 3px solid rgba(255, 255, 255, 0.4);
        }
        
        .navbar-logo.style-gold {
            background: linear-gradient(135deg, #f39c12 0%, #f4d03f 100%);
            border: 3px solid rgba(255, 255, 255, 0.5);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .navbar-logo {
                height: 40px;
                width: 100px;
                margin-right: 10px;
                padding: 5px;
            }
            .brand-text {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-logo {
                height: 36px;
                width: 90px;
                margin-right: 8px;
                padding: 4px;
                border-radius: 10px;
            }
            .brand-text {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-logo {
                height: 32px;
                width: 80px;
                margin-right: 0;
                padding: 3px;
                border-radius: 8px;
            }
            .brand-text {
                display: none;
            }
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
            min-height: 80px;
            transition: all 0.3s ease;
        }
        .quick-link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-group .quick-link-btn {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
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
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo-uni.png') }}" alt="Farm Logo" class="navbar-logo me-2">
                <span class="d-none d-md-inline">Farm Management System</span>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="animalsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-paw me-1"></i>Animals
                        </a>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">Batch Management</h6></li>
                            <li><a class="dropdown-item" href="{{ route('batches.index') }}">
                                <i class="fas fa-users me-2"></i>All Batches
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('batches.create') }}">
                                <i class="fas fa-plus-circle me-2"></i>Add New Batch
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Individual Animals</h6></li>
                            <li><a class="dropdown-item" href="{{ route('individual-animals.index') }}">
                                <i class="fas fa-paw me-2"></i>All Individual Animals
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('individual-animals.create') }}">
                                <i class="fas fa-plus-circle me-2"></i>Add New Animal
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="feedDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-seedling me-1"></i>Feed Management
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('feed.in.index') }}">
                                <i class="fas fa-plus-circle me-2 text-success"></i>Feed In
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('feed.out.index') }}">
                                <i class="fas fa-minus-circle me-2 text-warning"></i>Feed Out
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('feed.stock.overview') }}">
                                <i class="fas fa-chart-bar me-2 text-info"></i>Stock Overview
                            </a></li>
                            @if(Auth::user()->role === 'admin')
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('feed.types.index') }}">
                                <i class="fas fa-cogs me-2"></i>Feed Types
                            </a></li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="medicineDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-pills me-1"></i>Medicine
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('medicines.index') }}">
                                <i class="fas fa-list me-2"></i>All Medicine Records
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('medicines.create') }}">
                                <i class="fas fa-plus-circle me-2"></i>Add Medicine Record
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="productionDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-egg me-1"></i>Production
                        </a>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">🥚 PRODUCTION</h6></li>
                            <li><a class="dropdown-item" href="{{ route('production.index') }}">
                                <i class="fas fa-clipboard-list me-2"></i>All Production Records
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('production.create') }}">
                                <i class="fas fa-plus-circle me-2"></i>Add Production Record
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="salesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-money-bill-wave me-1"></i>Sales
                        </a>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">💰 SALES</h6></li>
                            <li><a class="dropdown-item" href="{{ route('sales.index') }}">
                                <i class="fas fa-clipboard-list me-2"></i>All Sales Records
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('sales.create') }}">
                                <i class="fas fa-plus-circle me-2"></i>Add New Sale
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar me-1"></i>Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">📊 REPORTS</h6></li>
                            <li><a class="dropdown-item" href="{{ route('reports.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Reports Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reports.generate') }}">
                                <i class="fas fa-file-export me-2"></i>Generate Report
                            </a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            <span class="badge bg-secondary ms-1">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
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
            @if(Request::routeIs('dashboard') || Request::routeIs('batches.*') || Request::routeIs('feed.*') || Request::routeIs('individual-animals.*') || Request::routeIs('medicines.*') || Request::routeIs('production.*') || Request::routeIs('sales.*') || Request::routeIs('reports.*'))
            <div class="col-md-2 sidebar p-3">
                <h6 class="text-muted mb-3">MAIN</h6>
                <div class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                    
                    <!-- Animal Management Section -->
                    <hr>
                    <h6 class="text-muted mb-3">BATCH MANAGEMENT</h6>
                    <a href="{{ route('batches.index') }}" class="nav-link {{ Request::routeIs('batches.index') ? 'active' : '' }}">
                        <i class="fas fa-users me-2 text-primary"></i>All Batches
                    </a>
                    <a href="{{ route('batches.create') }}" class="nav-link {{ Request::routeIs('batches.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Add New Batch
                    </a>
                    
                    <!-- Individual Animal Management Section -->
                    <hr>
                    <h6 class="text-muted mb-3">INDIVIDUAL ANIMALS</h6>
                    <a href="{{ route('individual-animals.index') }}" class="nav-link {{ Request::routeIs('individual-animals.index') ? 'active' : '' }}">
                        <i class="fas fa-paw me-2 text-primary"></i>All Animals
                    </a>
                    <a href="{{ route('individual-animals.create') }}" class="nav-link {{ Request::routeIs('individual-animals.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Add New Animal
                    </a>
                    
                    <!-- Feed Management Section -->
                    <hr>
                    <h6 class="text-muted mb-3">FEED MANAGEMENT</h6>
                    <a href="{{ route('feed.in.index') }}" class="nav-link {{ Request::routeIs('feed.in.*') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Feed In
                    </a>
                    <a href="{{ route('feed.out.index') }}" class="nav-link {{ Request::routeIs('feed.out.*') ? 'active' : '' }}">
                        <i class="fas fa-minus-circle me-2 text-warning"></i>Feed Out
                    </a>
                    <a href="{{ route('feed.stock.overview') }}" class="nav-link {{ Request::routeIs('feed.stock.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar me-2 text-info"></i>Stock Overview
                    </a>
                    
                    <!-- Medicine Management Section -->
                    <hr>
                    <h6 class="text-muted mb-3">MEDICINE MANAGEMENT</h6>
                    <a href="{{ route('medicines.index') }}" class="nav-link {{ Request::routeIs('medicines.index') ? 'active' : '' }}">
                        <i class="fas fa-list me-2 text-primary"></i>All Medicine Records
                    </a>
                    <a href="{{ route('medicines.create') }}" class="nav-link {{ Request::routeIs('medicines.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Add Medicine Record
                    </a>
                    
                    <!-- Production Management Section -->
                    <hr>
                    <h6 class="text-muted mb-3">🥚 PRODUCTION</h6>
                    <a href="{{ route('production.index') }}" class="nav-link {{ Request::routeIs('production.index') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>All Production Records
                    </a>
                    <a href="{{ route('production.create') }}" class="nav-link {{ Request::routeIs('production.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Add Production Record
                    </a>
                    
                    <!-- Sales Management Section -->
                    <hr>
                    <h6 class="text-muted mb-3">💰 SALES</h6>
                    <a href="{{ route('sales.index') }}" class="nav-link {{ Request::routeIs('sales.index') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>All Sales Records
                    </a>
                    <a href="{{ route('sales.create') }}" class="nav-link {{ Request::routeIs('sales.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Add New Sale
                    </a>
                    
                    <!-- Reports Section -->
                    <hr>
                    <h6 class="text-muted mb-3">📊 REPORTS</h6>
                    <a href="{{ route('reports.dashboard') }}" class="nav-link {{ Request::routeIs('reports.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2 text-info"></i>Reports Dashboard
                    </a>
                    <a href="{{ route('reports.generate') }}" class="nav-link {{ Request::routeIs('reports.generate') ? 'active' : '' }}">
                        <i class="fas fa-file-export me-2 text-warning"></i>Generate Report
                    </a>
                    
                    <hr>
                    @if(Auth::user()->role === 'admin')
                    <hr>
                    <h6 class="text-muted mb-3">ADMIN</h6>
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-cog me-2"></i>User Management
                    </a>
                    <a href="{{ route('feed.types.index') }}" class="nav-link {{ Request::routeIs('feed.types.*') ? 'active' : '' }}">
                        <i class="fas fa-seedling me-2"></i>Feed Types
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
