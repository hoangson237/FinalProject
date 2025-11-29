<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body { background-color: #f8f9fa; }
        
        /* Sidebar bên trái */
        .sidebar {
            min-height: 100vh;
            background: #212529; /* Màu tối */
            color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .logo {
            padding: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid #343a40;
            text-align: center;
            color: #ffc107; /* Màu vàng */
        }

        .sidebar nav a {
            color: #adb5bd;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            border-bottom: 1px solid #2c3034;
            transition: 0.3s;
        }

        .sidebar nav a:hover, .sidebar nav a.active {
            background: #0d6efd; /* Màu xanh khi hover */
            color: white;
            padding-left: 25px; /* Hiệu ứng đẩy nhẹ sang phải */
        }
        
        .sidebar nav a i { margin-right: 10px; width: 20px; text-align: center; }

        /* Nội dung chính bên phải */
        .main-content {
            padding: 20px;
        }
        
        .top-bar {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-2 sidebar p-0">
                <div class="logo">
                    <i class="fas fa-university"></i> QUẢN LÝ ĐÀO TẠO
                </div>
                <nav>
                  <a href="{{ route('admin.dashboard') }}" 
   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fas fa-chart-pie"></i> Tổng quan
</a>

<a href="{{ route('admin.classes.index') }}" 
   class="{{ request()->routeIs('admin.classes.*') || request()->routeIs('admin.class.*') ? 'active' : '' }}">
    <i class="fas fa-chalkboard"></i> Quản lý Lớp học
</a>

<a href="{{ route('admin.teachers.index') }}" 
   class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
    <i class="fas fa-chalkboard-teacher"></i> Quản lý Giáo viên
</a>

                  <a href="{{ route('admin.students.index') }}" 
   class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
    <i class="fas fa-user-graduate"></i> Quản lý Sinh viên
</a>


                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-danger mt-3" style="border-top: 1px solid #495057;">
                       <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            </div>

            <div class="col-md-10 main-content">
                <div class="top-bar">
                    <h5 class="m-0 text-secondary">Hệ thống Đăng ký Lớp học</h5>
                    <div>
                        Xin chào, <span class="fw-bold text-primary">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                @yield('content')
            </div>

        </div>
    </div>
</body>
</html>