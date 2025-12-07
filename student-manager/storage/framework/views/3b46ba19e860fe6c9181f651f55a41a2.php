

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white" 
         style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Khu vực Giảng dạy</h4>
                <p class="mb-0 opacity-75">Giảng viên: <?php echo e(Auth::user()->name); ?></p>
            </div>
            <i class="fas fa-user-edit fa-3x opacity-25"></i>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            
            
            <?php if(session('success')): ?>
                <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            

            <h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-success">
                &nbsp;Lớp học được phân công
            </h5>

            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                                    <i class="fas fa-book-open fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><?php echo e($class->name); ?></h6>
                                    <small class="text-muted">Mã lớp: #<?php echo e($class->id); ?></small>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Sĩ số hiện tại</span>
                                    <span class="fw-bold text-success"><?php echo e($class->current_quantity); ?> / <?php echo e($class->max_quantity); ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <?php $percent = ($class->max_quantity > 0) ? ($class->current_quantity / $class->max_quantity) * 100 : 0; ?>
                                    <div class="progress-bar bg-success" style="width: <?php echo e($percent); ?>%"></div>
                                </div>
                            </div>

                            <a href="<?php echo e(route('teacher.class.show', $class->id)); ?>" class="btn btn-outline-success w-100 rounded-pill fw-bold">
                                Vào chấm điểm <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-coffee fa-3x mb-3 opacity-50"></i>
                    <p>Thầy/Cô chưa được phân công lớp nào.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-5">
                <div class="d-flex justify-content-center">
                    <?php echo e($classes->appends(request()->query())->links('pagination.admin')); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/teacher/index.blade.php ENDPATH**/ ?>