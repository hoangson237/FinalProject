

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i>Cập nhật Lớp học phần</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="<?php echo e(route('admin.class.update', $class->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Lớp học</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $class->name)); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giáo viên</label>
                        <select name="teacher_id" class="form-select">
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gv->id); ?>" <?php echo e($class->teacher_id == $gv->id ? 'selected' : ''); ?>>
                                    <?php echo e($gv->name); ?> (<?php echo e($gv->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lịch học chi tiết</label>
                        <div class="card p-3 bg-light border-0">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="small fw-bold text-muted mb-2">Ngày trong tuần:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php $__currentLoopData = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php 
                                                $isChecked = str_contains($class->schedule, $day); 
                                            ?>
                                            <input type="checkbox" class="btn-check" name="days[]" id="editBtn<?php echo e($day); ?>" value="<?php echo e($day); ?>" 
                                                <?php echo e($isChecked ? 'checked' : ''); ?>>
                                            <label class="btn btn-outline-primary bg-white" for="editBtn<?php echo e($day); ?>"><?php echo e($day); ?></label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="small fw-bold text-muted mb-2">Ca học:</label>
                                    <select name="shift" class="form-select bg-white" required>
                                        <option value="Ca 1 (7h - 9h)" <?php echo e(str_contains($class->schedule, 'Ca 1') ? 'selected' : ''); ?>>Ca 1 (7h - 9h)</option>
                                        <option value="Ca 2 (9h - 11h)" <?php echo e(str_contains($class->schedule, 'Ca 2') ? 'selected' : ''); ?>>Ca 2 (9h - 11h)</option>
                                        <option value="Ca 3 (12h - 14h)" <?php echo e(str_contains($class->schedule, 'Ca 3') ? 'selected' : ''); ?>>Ca 3 (12h - 14h)</option>
                                        <option value="Ca 4 (14h - 16h)" <?php echo e(str_contains($class->schedule, 'Ca 4') ? 'selected' : ''); ?>>Ca 4 (14h - 16h)</option>
                                        <option value="Ca Tối (18h - 21h)" <?php echo e(str_contains($class->schedule, 'Ca Tối') ? 'selected' : ''); ?>>Ca Tối (18h - 21h)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phòng học</label>
                            <select name="room" class="form-select" required>
                                <?php $__currentLoopData = ['Phòng A101 (Tòa A)', 'Phòng A102 (Tòa A)', 'Phòng Lab 1 (Thực hành)', 'Phòng Lab 2 (Thực hành)', 'Hội trường B']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($r); ?>" <?php echo e(str_contains($class->room, $r) ? 'selected' : ''); ?>><?php echo e($r); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo e($class->start_date); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sĩ số tối đa</label>
                            <div class="input-group">
                                <input type="number" name="max_quantity" class="form-control" 
                                       value="<?php echo e($class->max_quantity); ?>" min="1" max="50">
                                <span class="input-group-text text-muted">Max: 50</span>
                            </div>
                            <div class="form-text">Hiện tại: <?php echo e($class->current_quantity); ?> SV.</div>
                        </div>
                    </div>

                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <?php $isFull = $class->current_quantity >= $class->max_quantity; ?>
                        <?php if($isFull): ?>
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white"><i class="fas fa-ban"></i></span>
                                <select class="form-select bg-light" disabled><option>🔴 Đã đóng (Full)</option></select>
                            </div>
                            <input type="hidden" name="status" value="0">
                        <?php else: ?>
                            <select name="status" class="form-select">
                                <option value="1" <?php echo e($class->status == 1 ? 'selected' : ''); ?>>🟢 Đang mở</option>
                                <option value="0" <?php echo e($class->status == 0 ? 'selected' : ''); ?>>🔴 Đóng lớp</option>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-secondary px-4">Quay lại</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/admin/classes/edit.blade.php ENDPATH**/ ?>