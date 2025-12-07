

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-success">
                <i class="fas fa-tasks me-2"></i>Lớp học đã đăng ký
            </h5>
        </div>

        <?php if(session('success') || session('error')): ?>
            <div class="p-3 bg-light border-bottom">
                <?php if(session('success')): ?>
                    <div class="alert alert-success shadow-sm border-0 rounded-3 mb-0">
                        <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-0 mt-2">
                        <i class="fas fa-exclamation-circle me-1"></i> <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase border-bottom">
                    <tr>
                        <th class="ps-4 py-3">Lớp học phần</th>
                        <th class="py-3">Giáo viên</th>
                        <th class="text-center bg-primary bg-opacity-10 text-primary py-3">Điểm số</th>
                        <th class="text-center py-3">Kết quả</th>
                        <th class="text-end pe-4 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <span class="fw-bold text-dark"><?php echo e($reg->classroom->name); ?></span>
                        </td>
                        <td>
                            <span class="text-muted fw-bold">
                                <?php echo e($reg->classroom->teacher->name ?? 'Chưa gán'); ?>

                            </span>
                        </td>
                        
                        <td class="text-center bg-light">
                            <?php if($reg->score !== null): ?>
                                <span class="fw-bold fs-5 text-primary"><?php echo e($reg->score); ?></span>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">-- Chưa chấm --</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php if($reg->score === null): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Đang học</span>
                            <?php elseif($reg->score >= 5.0): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">ĐẠT</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">HỌC LẠI</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-end pe-4">
                            <?php if($reg->score === null): ?>
                                <form action="<?php echo e(route('student.cancel', $reg->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn HỦY lớp này? Slot sẽ được nhường cho người khác.');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 hover-scale">
                                        <i class="fas fa-times me-1"></i> Hủy
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-light btn-sm text-muted rounded-pill px-3" disabled>
                                    <i class="fas fa-lock me-1"></i> Đã chốt
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-2x mb-3 opacity-25"></i><br>
                            Bạn chưa đăng ký lớp học nào.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.05); transition: 0.2s; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/student/my_classes.blade.php ENDPATH**/ ?>