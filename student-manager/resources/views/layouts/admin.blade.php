<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>

    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* CỠ CHỮ TỔNG THỂ */
        body { background-color: #f8f9fa; font-size: 0.95rem; } 
        .form-control, .form-select, .btn { font-size: 1rem !important; }

        /* Sidebar */
        .sidebar { min-height: 100vh; background: #212529; color: #adb5bd; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar .logo { padding: 18px 15px; font-size: 1.1rem; font-weight: 800; border-bottom: 1px solid #343a40; text-align: center; color: #ffc107; display: flex; align-items: center; justify-content: center; }
        .sidebar nav a { color: #adb5bd; text-decoration: none; padding: 14px 20px; display: block; border-bottom: 1px solid #2c3034; transition: 0.2s; font-weight: 500; font-size: 0.95rem; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #0d6efd; color: white; padding-left: 25px; }
        .sidebar nav a i { margin-right: 12px; width: 24px; text-align: center; font-size: 1.1rem; }

        /* Topbar */
        .top-bar { background: white; padding: 0 25px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; height: 65px; }
        
        /* Footer Animation */
        .animate-pulse { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
        
        /* Tắt mũi tên dropdown mặc định */
        .dropdown-toggle::after { display: none !important; }
        /* Cursor pointer cho avatar */
        .avatar-trigger { cursor: pointer; transition: transform 0.2s; }
        .avatar-trigger:hover { transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-2 sidebar p-0 d-none d-md-block">
                <div class="logo">
                    <i class="fas fa-university me-2"></i> QUẢN TRỊ VIÊN
                </div>
                <nav class="mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Tổng quan
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="{{ request()->routeIs('admin.classes.*') || request()->routeIs('admin.class.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard"></i> Quản lý Lớp học
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i> Quản lý Giáo viên
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i> Quản lý Sinh viên
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-danger mt-3" style="border-top: 1px solid #495057;">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </nav>
            </div>

            <div class="col-md-10 ms-auto p-0 d-flex flex-column min-vh-100">
                
                <div class="top-bar sticky-top shadow-sm">
                    <div>
                        <span class="fw-bold text-secondary" style="font-size: 1rem;">
                            <i class="fas fa-bars me-2 d-md-none"></i> Hệ thống Quản lý Đào tạo
                        </span>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="text-end me-3 d-none d-sm-block">
                            <div class="fw-bold" style="font-size: 0.95rem;">{{ Auth::user()->name }}</div>
                        </div>
                        
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none avatar-trigger" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/'.Auth::user()->avatar) }}" 
                                         class="rounded-circle border shadow-sm" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center fw-bold shadow-sm" 
                                         style="width: 40px; height: 40px; font-size: 1rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-circle me-2 text-primary"></i> Hồ sơ cá nhân
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger py-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="p-4 flex-grow-1">
                    @yield('content')
                </div>

                <footer class="bg-white border-top py-2 mt-auto">
                    <div class="container-fluid px-4">
                        <div class="row align-items-center" style="font-size: 0.85rem;">
                            <div class="col-md-6 text-center text-md-start text-muted">
                                Copyright &copy; {{ date('Y') }} <strong>Student Management System</strong>.
                            </div>
                            <div class="col-md-6 text-center text-md-end text-muted">
                                Version 2.0 <span class="mx-2">|</span> 
                                Made with <i class="fas fa-heart text-danger animate-pulse mx-1"></i> by <strong>Fresher Team</strong>
                            </div>
                        </div>
                    </div>
                </footer>

            </div> 
        </div>
    </div>
</body>
</html>