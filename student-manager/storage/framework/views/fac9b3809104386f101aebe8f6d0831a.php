


<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9"> <div class="card shadow border-0 rounded-4">
                <div class="card-header text-white py-3 
                    <?php echo e(Auth::user()->role == 1 ? 'bg-primary' : (Auth::user()->role == 2 ? 'bg-success' : 'bg-info')); ?>">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-id-card me-2"></i>Hồ sơ cá nhân
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <ul class="mb-0 ps-3">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-4 text-center mb-4 border-end">
                                <div class="mb-3 mt-2">
                                    <?php if($user->avatar): ?>
                                        <img src="<?php echo e(asset('storage/'.$user->avatar)); ?>" 
                                             class="rounded-circle border shadow-sm" 
                                             width="160" height="160" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary text-white d-inline-flex justify-content-center align-items-center shadow-sm" 
                                             style="width: 160px; height: 160px; font-size: 3.5rem; font-weight: bold;">
                                            <?php echo e(substr($user->name, 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <label class="btn btn-outline-primary btn-sm w-75 rounded-pill fw-bold">
                                    <i class="fas fa-camera me-1"></i> Đổi ảnh
                                    <input type="file" name="avatar" class="d-none">
                                </label>
                                <div class="mt-2 small text-muted fst-italic">Dung lượng < 2MB</div>
                            </div>

                            <div class="col-md-8 ps-md-4">
                                
                                <div class="mb-3">
                                    <label class="fw-bold text-secondary">
                                        <?php if($user->role == 0): ?> Mã Sinh viên
                                        <?php elseif($user->role == 2): ?> Mã Giáo viên
                                        <?php else: ?> Mã Quản trị <?php endif; ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-id-badge"></i></span>
                                        <input type="text" class="form-control bg-light text-muted fw-bold" value="<?php echo e($user->code); ?>" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold text-secondary">Email đăng nhập</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                        <input type="text" class="form-control bg-light text-muted" value="<?php echo e($user->email); ?>" readonly>
                                    </div>
                                    <small class="text-muted fst-italic" style="font-size: 0.8rem;">* Không thể thay đổi thông tin định danh.</small>
                                </div>

                                <hr class="my-4 border-light">

                                <div class="mb-3">
                                    <label class="fw-bold text-dark">Họ và Tên</label>
                                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('name', $user->name)); ?>" 
                                           <?php echo e($user->role != 1 ? 'readonly bg-light' : ''); ?>> 
                                    <?php if($user->role != 1): ?> <small class="text-muted">Liên hệ Admin nếu sai tên.</small> <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold text-dark">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold text-dark">Địa chỉ</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $user->address)); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-light rounded-3 mt-2 border">
                                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-lock me-2"></i>Đổi mật khẩu</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <input type="password" name="password" class="form-control" 
                                                   placeholder="Mật khẩu mới (8 ký tự)" maxlength="8" minlength="8">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" name="password_confirmation" class="form-control" 
                                                   placeholder="Nhập lại xác nhận" maxlength="8">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <?php
                                        // Logic nút Quay lại thông minh
                                        $backLink = '#';
                                        if(Auth::user()->role == 1) $backLink = route('admin.dashboard');
                                        if(Auth::user()->role == 2) $backLink = route('teacher.classes');
                                        if(Auth::user()->role == 0) $backLink = route('student.register'); // SV về trang chủ
                                    ?>
                                    
                                    <a href="<?php echo e($backLink); ?>" class="btn btn-secondary shadow-sm fw-bold px-3">
                                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                                    </a>

                                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                                        <i class="fas fa-save me-2"></i> Lưu Hồ sơ
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make(Auth::user()->role == 1 ? 'layouts.admin' : 'layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/profile/edit.blade.php ENDPATH**/ ?>