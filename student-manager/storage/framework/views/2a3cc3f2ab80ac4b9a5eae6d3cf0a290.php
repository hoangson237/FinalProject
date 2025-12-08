

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
                        <th class="text-center py-3">Ngày bắt đầu</th>
                        <th class="text-end pe-4 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        
                        <td class="ps-4 py-3">
                            <a href="#" class="fw-bold text-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#classModal-<?php echo e($reg->id); ?>">
                                <?php echo e($reg->classroom->name); ?> <i class="fas fa-info-circle small text-muted ms-1"></i>
                            </a>
                            <div class="small text-muted"><?php echo e($reg->classroom->code ?? ''); ?></div>

                            
                            <div class="modal fade" id="classModal-<?php echo e($reg->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title fw-bold">Chi tiết Lớp học</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-2"><strong><i class="fas fa-calendar-alt me-2 text-muted"></i>Lịch học:</strong> <span class="text-danger fw-bold"><?php echo e($reg->classroom->schedule ?? 'Chưa có'); ?></span></div>
                                            <div class="mb-2"><strong><i class="fas fa-map-marker-alt me-2 text-muted"></i>Phòng:</strong> <span class="badge bg-light text-dark border"><?php echo e($reg->classroom->room ?? 'Chưa xếp'); ?></span></div>
                                            <div><strong><i class="fas fa-clock me-2 text-muted"></i>Ngày bắt đầu:</strong> <?php echo e($reg->classroom->start_date ? \Carbon\Carbon::parse($reg->classroom->start_date)->format('d/m/Y') : '---'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                        
                        <td class="text-center text-muted small">
                            <?php echo e($reg->classroom->start_date ? \Carbon\Carbon::parse($reg->classroom->start_date)->format('d/m/Y') : '---'); ?>

                        </td>

                        
                        <td class="text-end pe-4">
                            <?php if($reg->score !== null): ?>
                                
                                <button class="btn btn-light btn-sm text-muted rounded-pill px-3" disabled>
                                    <i class="fas fa-lock me-1"></i> Đã chốt
                                </button>
                            <?php else: ?>
                                
                                <?php
                                    $canCancel = true; 
                                    $reason = '';

                                    if ($reg->classroom->start_date) {
                                        $startDate = \Carbon\Carbon::parse($reg->classroom->start_date);
                                        // Deadline = Ngày học trừ đi 3 ngày
                                        $deadline = $startDate->copy()->subDays(3)->endOfDay();
                                        
                                        if (now()->greaterThan($deadline)) {
                                            $canCancel = false;
                                            $reason = 'Đã quá hạn hủy (Quy định trước 3 ngày)';
                                        }
                                    }
                                ?>

                                <?php if($canCancel): ?>
                                    
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 hover-scale" 
                                            data-bs-toggle="modal" data-bs-target="#cancelModal-<?php echo e($reg->id); ?>">
                                        <i class="fas fa-times me-1"></i> Hủy
                                    </button>

                                    
                                    <div class="modal fade" id="cancelModal-<?php echo e($reg->id); ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-body text-center p-4">
                                                    <div class="text-danger mb-3">
                                                        <i class="fas fa-exclamation-circle fa-3x"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-2">Xác nhận Hủy?</h5>
                                                    <p class="text-muted small mb-4">
                                                        Bạn có chắc chắn muốn hủy đăng ký lớp <strong><?php echo e($reg->classroom->name); ?></strong>?<br>
                                                        Hành động này sẽ nhường slot cho người khác.
                                                    </p>
                                                    
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Không</button>
                                                        
                                                        
                                                        <form action="<?php echo e(route('student.cancel', $reg->id)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Có, Hủy ngay</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                <?php else: ?>
                                    
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="<?php echo e($reason); ?>">
                                        <button class="btn btn-secondary btn-sm rounded-pill px-3 opacity-75" disabled>
                                            <i class="fas fa-ban me-1"></i> Chốt sổ
                                        </button>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-2x mb-3 opacity-25"></i><br>
                            Bạn chưa đăng ký lớp học nào.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($registrations->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($registrations->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.05); transition: 0.2s; }
</style>


<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/student/my_classes.blade.php ENDPATH**/ ?>