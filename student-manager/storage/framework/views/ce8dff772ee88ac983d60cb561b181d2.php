

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-edit me-2"></i>Cập nhật Lớp học phần
                </h5>
            </div>
            
            <div class="card-body p-4">
                
                <form action="<?php echo e(route('admin.class.update', $class->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Lớp học</label>
                        <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('name', $class->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giáo viên giảng dạy</label>
                        <select name="teacher_id" class="form-select <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Chọn Giáo viên --</option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gv->id); ?>" <?php echo e(old('teacher_id', $class->teacher_id) == $gv->id ? 'selected' : ''); ?>>
                                    <?php echo e($gv->name); ?> (<?php echo e($gv->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sĩ số tối đa (MAX)</label>
                        <input type="number" name="max_quantity" class="form-control <?php $__errorArgs = ['max_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('max_quantity', $class->max_quantity)); ?>" min="1" required>
                        <div class="form-text">Lưu ý: Sĩ số không được nhỏ hơn sĩ số hiện tại (<?php echo e($class->current_quantity); ?>).</div>
                        <?php $__errorArgs = ['max_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Trạng thái Lớp học</label>

                        <?php
                            // Kiểm tra xem lớp có đầy không
                            $isFull = $class->current_quantity >= $class->max_quantity;
                        ?>

                        <?php if($isFull): ?>
                            
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white"><i class="fas fa-ban"></i></span>
                                <select class="form-select bg-light text-secondary" disabled>
                                    <option selected>🔴 Đã đóng (Lớp đã đầy sĩ số)</option>
                                </select>
                            </div>
                            
                            
                            <input type="hidden" name="status" value="0">

                            <div class="alert alert-warning mt-2 d-flex align-items-center shadow-sm" role="alert">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                                <div>
                                    <strong>Không thể mở lớp này!</strong><br>
                                    Sĩ số hiện tại <strong>(<?php echo e($class->current_quantity); ?>/<?php echo e($class->max_quantity); ?>)</strong> đã đầy. 
                                    Vui lòng tăng "Sĩ số tối đa" ở trên trước nếu muốn mở lại lớp.
                                </div>
                            </div>
                        <?php else: ?>
                            
                            <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="1" <?php echo e(old('status', $class->status) == 1 ? 'selected' : ''); ?>>
                                    🟢 Đang mở (Cho phép đăng ký)
                                </option>
                                <option value="0" <?php echo e(old('status', $class->status) == 0 ? 'selected' : ''); ?>>
                                    🔴 Đóng lớp (Ngưng tuyển sinh)
                                </option>
                            </select>
                            <div class="form-text text-muted">
                                Nếu đóng, sinh viên sẽ không thấy nút đăng ký nữa.
                            </div>
                        <?php endif; ?>

                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/admin/classes/edit.blade.php ENDPATH**/ ?>