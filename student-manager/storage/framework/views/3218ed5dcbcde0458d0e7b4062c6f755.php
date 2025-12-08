

<?php $__env->startSection('content'); ?>
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-trash-alt me-2"></i>Thùng rác Lớp học</h5>
        <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success shadow-sm mb-3 border-0">
                <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tên lớp</th>
                        <th>Ngày xóa (Giờ VN)</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $deletedClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3">
                            <span class="fw-bold text-dark"><?php echo e($class->name); ?></span>
                            <br> 
                            
                            <small class="text-muted">GV: <?php echo e($class->teacher->name ?? '---'); ?></small>
                        </td>
                        
                        
                        <td class="text-secondary">
                            <i class="far fa-clock me-1"></i>
                            <?php echo e($class->deleted_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i')); ?>

                        </td>
                        
                        <td class="text-end pe-3">
                            
                            <a href="<?php echo e(route('admin.classes.restore', $class->id)); ?>" class="btn btn-success btn-sm shadow-sm" title="Khôi phục">
                                <i class="fas fa-undo"></i>
                            </a>

                            
                            <button type="button" class="btn btn-danger btn-sm shadow-sm ms-1" 
                                    data-bs-toggle="modal" data-bs-target="#forceDeleteModal-<?php echo e($class->id); ?>" title="Xóa vĩnh viễn">
                                <i class="fas fa-times"></i>
                            </button>

                            
                            <div class="modal fade" id="forceDeleteModal-<?php echo e($class->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body text-center p-4">
                                            <div class="text-danger mb-3"><i class="fas fa-radiation fa-3x"></i></div>
                                            <h5 class="fw-bold mb-2 text-danger">Xóa vĩnh viễn?</h5>
                                            <p class="text-muted small mb-4">
                                                Lớp <strong><?php echo e($class->name); ?></strong> sẽ bị xóa khỏi cơ sở dữ liệu và <strong>KHÔNG THỂ</strong> khôi phục lại.
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                                                <form action="<?php echo e(route('admin.classes.forceDelete', $class->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Xóa ngay</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-5">
                            <i class="fas fa-trash-restore fa-3x mb-3 opacity-25"></i><br>
                            Thùng rác trống!
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($deletedClasses->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/admin/classes/trash.blade.php ENDPATH**/ ?>