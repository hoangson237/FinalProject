

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    
    
    <?php
        $isGradedPage = request('filter') == 'graded';
        // Logic màu: Nếu là trang điểm (graded) thì màu Vàng, còn lại là Xanh (primary)
        $themeColor = $isGradedPage ? 'warning' : 'primary'; 
    ?>

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        
        
        <div>
            <?php if($isGradedPage): ?>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-trophy me-2 text-warning"></i>Bảng Thành Tích
                </h3>
                <p class="text-muted mb-0">Danh sách sinh viên đã có điểm.</p>
            <?php else: ?>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-tasks me-2 text-primary"></i>Tiến độ Chấm điểm
                </h3>
                <p class="text-muted mb-0">Danh sách cần rà soát và nhập điểm.</p>
            <?php endif; ?>
        </div>

            
            <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-secondary shadow-sm rounded-circle p-2" title="Quay về Dashboard" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-home"></i>
            </a>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm border-top border-4 border-<?php echo e($themeColor); ?>">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary text-uppercase small">
                    <tr>
                        <th class="ps-4">Thông tin Sinh viên</th>
                        <th>Lớp học phần</th>
                        
                        
                        <th class="text-center">
                            <?php if($isGradedPage): ?>
                                ĐIỂM SỐ
                            <?php else: ?>
                                TRẠNG THÁI
                            <?php endif; ?>
                        </th>
                        
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    
                    <tr class="<?php echo e($isGradedPage ? 'bg-warning bg-opacity-10' : ''); ?>">
                        
                        
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                
                                <div class="rounded-circle text-white d-flex justify-content-center align-items-center me-3 fw-bold shadow-sm" 
                                     style="width: 45px; height: 45px; font-size: 1.2rem; background-color: <?php echo e($isGradedPage ? '#f6c23e' : '#4e73df'); ?>">
                                    <?php echo e(substr($reg->student?->name ?? '?', 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo e($reg->student?->name ?? 'Sinh viên đã xóa'); ?></div>
                                    <small class="text-muted"><?php echo e($reg->student?->code ?? '---'); ?></small>
                                </div>
                            </div>
                        </td>

                        
                        <td>
                            <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                                <?php echo e($reg->classroom?->name ?? 'Lớp đã hủy'); ?>

                            </span>
                        </td>
                        
                        
                        <td class="text-center">
                            <?php if($isGradedPage): ?>
                                
                                <div class="d-inline-block px-3 py-1 rounded-pill bg-warning text-dark fw-bold border border-warning shadow-sm">
                                    <?php echo e(number_format($reg->score, 2)); ?>

                                </div>
                            <?php else: ?>
                                
                                <?php if($reg->score !== null): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                        <i class="fas fa-check me-1"></i> Đã châ
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                        <i class="fas fa-clock me-1"></i> Chờ chấm
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>

                        
                        <td class="text-center">
                            <?php if($isGradedPage): ?>
                                <a href="<?php echo e(route('teacher.class.show', $reg->classroom_id)); ?>#reg-<?php echo e($reg->id); ?>" 
                                   class="btn btn-sm btn-outline-dark shadow-sm px-3">
                                    <i class="fas fa-search me-1"></i> Xem lại
                                </a>
                            <?php else: ?>
                                <?php if($reg->score === null): ?>
                                    <a href="<?php echo e(route('teacher.class.show', $reg->classroom_id)); ?>#reg-<?php echo e($reg->id); ?>" 
                                       class="btn btn-sm btn-primary shadow-sm px-3">
                                        <i class="fas fa-pen-nib me-1"></i> Chấm ngay
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('teacher.class.show', $reg->classroom_id)); ?>#reg-<?php echo e($reg->id); ?>" 
                                       class="btn btn-sm btn-outline-secondary shadow-sm px-3">
                                        <i class="fas fa-edit me-1"></i> Sửa điểm
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3 opacity-25"></i><br>
                            Không tìm thấy dữ liệu phù hợp.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if($students->hasPages()): ?>
        <div class="card-footer bg-white py-3">
             
            <?php echo e($students->appends(['filter' => request('filter')])->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/teacher/students.blade.php ENDPATH**/ ?>