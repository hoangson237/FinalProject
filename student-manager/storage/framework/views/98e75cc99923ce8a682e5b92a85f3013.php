

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    
    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Khu vực Giảng dạy</h2>
            <p class="text-muted mb-0">Giảng viên: <?php echo e(Auth::user()->name); ?></p>
        </div>
    </div>

    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            
            <a href="<?php echo e(route('teacher.classes')); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-white hover-lift" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-4 fw-bold mb-0"><?php echo e($stats['count_classes']); ?></h2>
                                <p class="mb-0 opacity-75">Lớp đang phụ trách</p>
                            </div>
                            <i class="fas fa-chalkboard-teacher fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            
            <a href="<?php echo e(route('teacher.students')); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-white hover-lift" style="background: linear-gradient(45deg, #1cc88a, #13855c);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-4 fw-bold mb-0"><?php echo e($stats['count_students']); ?></h2>
                                <p class="mb-0 opacity-75">Tiến độ Chấm điểm</p>
                            </div>
                            <i class="fas fa-user-graduate fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
             
            <a href="<?php echo e(route('teacher.students', ['filter' => 'graded'])); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-white hover-lift" style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-4 fw-bold mb-0"><?php echo e($stats['count_graded']); ?></h2>
                                <p class="mb-0 opacity-75">Bảng Thành Tích</p>
                            </div>
                            <i class="fas fa-check-circle fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2"></i>Sinh viên mới vào lớp</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Sinh viên</th>
                        <th>Vào lớp</th>
                        <th>Thời gian</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recent_activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                
                                <?php if($reg->student && $reg->student->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $reg->student->avatar)); ?>" 
                                         class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 fw-bold text-primary border" 
                                         style="width: 40px; height: 40px;">
                                        <?php echo e(substr($reg->student?->name ?? '?', 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    
                                    <div class="fw-bold text-dark"><?php echo e($reg->student?->name ?? 'Sinh viên đã xóa'); ?></div>
                                    <small class="text-muted"><?php echo e($reg->student?->code ?? '---'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            
                            <span class="fw-bold text-primary"><?php echo e($reg->classroom?->name ?? 'Lớp đã hủy'); ?></span>
                        </td>
                        <td class="text-muted small">
                            <?php echo e($reg->created_at->diffForHumans()); ?>

                        </td>
                        <td class="text-center">
                            <?php if($reg->score !== null): ?>
                                <span class="badge bg-success">Đã có điểm</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Chưa chấm</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Chưa có hoạt động nào.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-5px); }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>