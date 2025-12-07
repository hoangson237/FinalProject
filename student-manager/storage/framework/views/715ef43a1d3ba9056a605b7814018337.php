

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-book-reader me-2"></i><?php echo e($class->name); ?>

                </h4>
                <div class="text-muted">
                    <i class="fas fa-users me-1"></i> Sĩ số: <strong><?php echo e($class->registrations->count()); ?>/<?php echo e($class->max_quantity); ?></strong>
                </div>
            </div>
            <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-list-ol me-2 text-primary"></i>Danh sách Sinh viên & Nhập điểm
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Thông tin Sinh viên</th>
                        <th class="text-center">Mã SV</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center" style="width: 180px;">Điểm số (0-10)</th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $class->registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr id="reg-<?php echo e($reg->id); ?>" class="student-row transition-bg">
                        
                        
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <?php if($reg->student && $reg->student->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $reg->student->avatar)); ?>" 
                                         class="rounded-circle me-3 border shadow-sm" width="45" height="45" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary bg-gradient text-white d-flex justify-content-center align-items-center me-3 fw-bold shadow-sm" 
                                         style="width: 45px; height: 45px; font-size: 1.2rem;">
                                        <?php echo e(substr($reg->student?->name ?? '?', 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <div class="fw-bold text-dark"><?php echo e($reg->student?->name ?? 'Sinh viên đã xóa'); ?></div>
                                    <small class="text-muted"><?php echo e($reg->student?->email ?? 'N/A'); ?></small>
                                </div>
                            </div>
                        </td>

                        
                        <td class="text-center fw-bold text-secondary">
                            <?php echo e($reg->student?->code ?? '---'); ?>

                        </td>

                        
                        <td class="text-center">
                             <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                Đang học
                            </span>
                        </td>

                        
                        <td class="text-center">
                            <form action="<?php echo e(route('teacher.score.update', $reg->id)); ?>" method="POST" id="form-score-<?php echo e($reg->id); ?>">
                                <?php echo csrf_field(); ?>
                                
                                
                                <input type="hidden" name="redirect_to" value="students">
                                
                                <input type="number" step="0.01" min="0" max="10" name="score" 
                                       class="form-control text-center fw-bold score-input <?php echo e($reg->score >= 5 ? 'text-success' : ($reg->score !== null ? 'text-danger' : '')); ?>" 
                                       value="<?php echo e($reg->score); ?>" 
                                       placeholder="..."
                                       <?php echo e(!$reg->student ? 'disabled' : ''); ?>>
                            </form>
                        </td>

                        
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" 
                                    onclick="document.getElementById('form-score-<?php echo e($reg->id); ?>').submit()"
                                    <?php echo e(!$reg->student ? 'disabled' : ''); ?>>
                                <i class="fas fa-save me-1"></i> Lưu
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-user-slash fa-2x mb-3 opacity-25"></i><br>
                            Lớp này chưa có sinh viên nào đăng ký.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    tr[id] { scroll-margin-top: 150px; }
    .student-row { transition: background-color 0.3s ease; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/teacher/show.blade.php ENDPATH**/ ?>