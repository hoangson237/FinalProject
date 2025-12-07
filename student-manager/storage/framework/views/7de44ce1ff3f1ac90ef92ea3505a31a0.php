

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">📚 Thêm Lớp học phần mới</h5>
        </div>
        
        <div class="card-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.class.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Lớp học</label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="VD: Lập trình PHP - K15" value="<?php echo e(old('name')); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giáo viên giảng dạy</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Chọn Giáo viên --</option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($gv->id); ?>" <?php echo e(old('teacher_id') == $gv->id ? 'selected' : ''); ?>>
                                <?php echo e($gv->name); ?> (<?php echo e($gv->email); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sĩ số tối đa (MAX 20)</label>
                    <input type="number" name="max_quantity" class="form-control" 
                           value="<?php echo e(old('max_quantity', 20)); ?>" min="1" max="20">
                    <small class="text-muted">Hệ thống sẽ chặn đăng ký khi đạt con số này.</small>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu Lớp học
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/admin/create.blade.php ENDPATH**/ ?>