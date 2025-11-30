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
        /* 1. CỠ CHỮ TỔNG THỂ */
        body { background-color: #f8f9fa; font-size: 0.95rem; } 
        
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: #adb5bd;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .logo {
            padding: 18px 15px;
            font-size: 1.1rem;
            font-weight: 800;
            border-bottom: 1px solid #343a40;
            text-align: center;
            color: #ffc107;
            display: flex; align-items: center; justify-content: center;
        }

        .sidebar nav a {
            color: #adb5bd;
            text-decoration: none;
            padding: 14px 20px;
            display: block;
            border-bottom: 1px solid #2c3034;
            transition: 0.2s;
            font-weight: 500;
            font-size: 0.95rem;
        }

        /* Class active sẽ được thêm tự động nhờ code Blade */
        .sidebar nav a:hover, .sidebar nav a.active {
            background: #0d6efd;
            color: white;
            padding-left: 25px;
        }
        
        .sidebar nav a i { margin-right: 12px; width: 24px; text-align: center; font-size: 1.1rem; }

        /* Topbar */
        .top-bar {
            background: white;
            padding: 0 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 65px;
        }

        /* Animation Footer */
        .animate-pulse { animation: pulse 1.5s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
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
                    {{-- 1. Link Tổng quan (Chính xác route dashboard) --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Tổng quan
                    </a>

                    {{-- 2. Link Quản lý Lớp học (Sử dụng dấu * để active cho cả trang con như create, edit) --}}
                    <a href="{{ route('admin.classes.index') }}" 
                       class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard"></i> Quản lý Lớp học
                    </a>

                    {{-- 3. Link Quản lý Giáo viên --}}
                    <a href="{{ route('admin.teachers.index') }}" 
                       class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i> Quản lý Giáo viên
                    </a>

                    {{-- 4. Link Quản lý Sinh viên --}}
                    <a href="{{ route('admin.students.index') }}" 
                       class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i> Quản lý Sinh viên
                    </a>

                    {{-- Nút Đăng xuất --}}
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

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                            <div class="text-end me-3 d-none d-sm-block">
                                {{-- Kiểm tra nếu user đã đăng nhập thì mới hiển thị tên --}}
                                <div class="fw-bold" style="font-size: 0.95rem;">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                                <div class="text-muted small">Administrator</div>
                            </div>
                            
                            @if(Auth::check() && Auth::user()->avatar)
                                <img src="{{ asset('storage/'.Auth::user()->avatar) }}" 
                                     class="rounded-circle border shadow-sm" 
                                     style="width: 38px; height: 38px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center fw-bold shadow-sm" 
                                     style="width: 38px; height: 38px; font-size: 1rem;">
                                     {{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'G' }}
                                </div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                            <li>
                                <a class="dropdown-item text-danger py-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
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