<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>

    <style>
        /* CSS TÙY CHỈNH */
        html, body { height: 100%; }
        body { 
            background-color: #f8f9fa; 
            display: flex; 
            flex-direction: column; 
        }
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: #0d6efd !important; letter-spacing: 0.5px; }
        .nav-link { font-weight: 500; font-size: 0.95rem; }
        .nav-link.active { color: #0d6efd !important; font-weight: 700; border-bottom: 2px solid #0d6efd; }
        main { flex: 1; }
        .animate-pulse { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
    </style>
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        
        <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom shadow-sm sticky-top">
            <div class="container">
                
                <a class="navbar-brand d-flex align-items-center" href="<?php echo e(url('/')); ?>">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 text-primary">
                        <i class="fas fa-university fa-lg"></i>
                    </div>
                    <span>CỔNG ĐÀO TẠO</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto ms-4">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(Auth::user()->role == 0): ?>
                                <li class="nav-item me-2">
                                    <a class="nav-link <?php echo e(request()->routeIs('student.register') ? 'active' : ''); ?>" 
                                       href="<?php echo e(route('student.register')); ?>">
                                       <i class="fas fa-edit me-1 text-secondary"></i> Đăng ký môn
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(request()->routeIs('student.myClasses') ? 'active' : ''); ?>" 
                                       href="<?php echo e(route('student.myClasses')); ?>">
                                       <i class="fas fa-list-ul me-1 text-secondary"></i> Lớp của tôi
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->role == 2): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-success fw-bold <?php echo e(request()->routeIs('teacher.*') ? 'active' : ''); ?>" 
                                       href="<?php echo e(route('teacher.classes')); ?>">
                                       <i class="fas fa-chalkboard-teacher me-1"></i> Khu vực Giảng viên
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <?php if(auth()->guard()->guest()): ?>
                            <?php else: ?>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold text-dark d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center me-2 text-secondary fw-bold" 
                                         style="width: 32px; height: 32px;">
                                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                    </div>
                                    <?php echo e(Auth::user()->name); ?>

                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 rounded-3">
                                    <div class="px-3 py-2 border-bottom mb-2 bg-light">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Vai trò</small><br>
                                        <span class="fw-bold text-primary">
                                            <?php if(Auth::user()->role == 1): ?> Quản trị viên
                                            <?php elseif(Auth::user()->role == 2): ?> Giảng viên
                                            <?php else: ?> Sinh viên <?php endif; ?>
                                        </span>
                                    </div>
                                    
                                    <a class="dropdown-item py-2" href="<?php echo e(route('profile.edit')); ?>">
                                        <i class="fas fa-id-card me-2 text-secondary"></i> Hồ sơ cá nhân
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item text-danger py-2" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?></form>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="bg-white border-top py-3 mt-auto">
            <div class="container">
                <div class="row align-items-center small text-secondary">
                    <div class="col-md-6 text-center text-md-start">
                        Copyright &copy; <?php echo e(date('Y')); ?> <strong>Hệ thống Quản lý Đào tạo</strong>.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        Version 2.0 <span class="mx-2 text-muted">|</span> 
                        Made with <i class="fas fa-heart text-danger animate-pulse mx-1"></i> by <strong>Fresher Team</strong>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>
</html><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/layouts/app.blade.php ENDPATH**/ ?>