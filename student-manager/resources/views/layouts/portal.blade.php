<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cổng Đào Tạo</title>

    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* 1. CHỈNH CỠ CHỮ TỔNG THỂ (COMPACT BASE SIZE: 0.9rem) */
        body { background-color: #f3f4f6; font-size: 0.9rem; } 
        
        /* FIX CHỮ BÉ TRONG Ô INPUT/SELECT (Giữ nguyên 1rem để dễ gõ) */
        .form-control, .form-select, .btn {
            font-size: 1rem !important; 
        }

        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: #adb5bd;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .logo {
            padding: 15px;
            font-size: 1rem;
            font-weight: 800;
            border-bottom: 1px solid #343a40;
            color: #ffc107;
            display: flex; align-items: center; justify-content: center;
        }

        .sidebar nav a {
            color: #adb5bd;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            border-bottom: 1px solid #2c3034;
            transition: 0.2s;
            font-weight: 500;
            font-size: 0.9rem; /* Chữ menu đồng bộ */
        }

        .sidebar nav a:hover, .sidebar nav a.active {
            background: #0d6efd;
            color: #fff;
            padding-left: 25px;
        }
        
        .sidebar nav a i { margin-right: 12px; width: 20px; text-align: center; }

        .nav-header {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            padding: 15px 20px 5px 20px;
            font-weight: bold;
        }

        /* Topbar */
        .top-bar {
            background: #fff;
            padding: 0 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 65px;
        }

        /* Footer Animation */
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
                    <i class="fas fa-university fa-lg me-2"></i> CỔNG ĐÀO TẠO
                </div>
                
                <nav class="mt-2">
                    @auth
                        @if(Auth::user()->role == 0)
                            <div class="nav-header">Sinh viên</div>
                            <a href="{{ route('student.register') }}" 
   class="{{ request()->routeIs('student.register') ? 'active' : '' }}"
   style="font-size: 0.9rem;">  <i class="fas fa-edit"></i> Đăng ký môn học
</a>
                            <a href="{{ route('student.myClasses') }}" class="{{ request()->routeIs('student.myClasses') ? 'active' : '' }}"  style="font-size: 0.9rem;">
                                <i class="fas fa-list-ul"></i> Lớp của tôi
                            </a>
                        @endif

                        @if(Auth::user()->role == 2)
                            <div class="nav-header">Giảng viên</div>
                            <a href="{{ route('teacher.classes') }}" class="{{ request()->routeIs('teacher.*') ? 'active' : '' }}">
                                <i class="fas fa-chalkboard-teacher"></i> Lớp giảng dạy
                            </a>
                        @endif
                    @endauth

                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-danger mt-3" style="border-top: 1px solid #343a40;">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </nav>
            </div>

            <div class="col-md-10 ms-auto p-0 d-flex flex-column min-vh-100">
                
                <div class="top-bar sticky-top shadow-sm">
                    <div>
                        <span class="fw-bold text-secondary" style="font-size: 1rem;">
                            @if(Auth::user()->role == 0) <i class="fas fa-user-graduate me-2"></i>Khu vực Sinh viên 
                            @else <i class="fas fa-chalkboard-teacher me-2"></i>Khu vực Giảng viên @endif
                        </span>
                    </div>

                      <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                            <div class="text-end me-3 d-none d-sm-block">
                                <div class="fw-bold" style="font-size: 0.95rem;">{{ Auth::user()->name }}</div>
                              <div class="text-muted small text-center">Student</div>
                              


                            </div>
                            
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/'.Auth::user()->avatar) }}" 
                                     class="rounded-circle border shadow-sm" 
                                     style="width: 38px; height: 38px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center fw-bold shadow-sm" 
                                     style="width: 38px; height: 38px; font-size: 1rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
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


            </div> </div>
    </div>
</body>
</html>