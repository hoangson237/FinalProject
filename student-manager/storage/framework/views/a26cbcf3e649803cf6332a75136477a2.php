<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <?php if(session('success')): ?>
                <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4 text-center fw-bold">
                    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập</h4>
                    <p class="mb-0 opacity-75 small">Hệ thống Quản lý Đào tạo</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-muted">Email đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input id="email" type="email" class="form-control border-start-0 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus
                                       placeholder="name@example.com">
                                
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>
                                            <?php if($message == 'These credentials do not match our records.'): ?>
                                                Tài khoản hoặc mật khẩu không đúng.
                                            <?php else: ?>
                                                <?php echo e($message); ?>

                                            <?php endif; ?>
                                        </strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-bold text-muted">Mật khẩu</label>
                                <?php if(Route::has('password.request')): ?>
                                    <a class="btn btn-link btn-sm p-0 text-decoration-none" href="<?php echo e(route('password.request')); ?>">
                                        Quên mật khẩu?
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input id="password" type="password" class="form-control border-start-0 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       name="password" required autocomplete="current-password" placeholder="********">
                                
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <label class="form-check-label text-muted" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm rounded-pill hover-scale">
                                ĐĂNG NHẬP NGAY
                            </button>
                        </div>

                        <div class="text-center text-muted">
                            Chưa có tài khoản? 
                            <a href="<?php echo e(route('register')); ?>" class="text-decoration-none fw-bold">Đăng ký mới</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted small">
                &copy; <?php echo e(date('Y')); ?> Student Management System
            </div>

        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.02); transition: 0.2s; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/auth/login.blade.php ENDPATH**/ ?>