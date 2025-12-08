

<?php $__env->startSection('content'); ?>
<div class="card shadow border-0">
    
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary" style="font-size: 1.25rem;">
            <i class="fas fa-chalkboard me-2"></i>Quản lý Lớp học phần
        </h5>
        <div>
            <a href="<?php echo e(route('admin.classes.trash')); ?>" class="btn btn-outline-danger btn-sm shadow-sm me-2" style="font-size: 1rem;">
                <i class="fas fa-trash-alt me-1"></i> Thùng rác
            </a>
            <a href="<?php echo e(route('admin.class.create')); ?>" class="btn btn-primary btn-sm shadow-sm" style="font-size: 1rem;">
                <i class="fas fa-plus-circle me-1"></i> Thêm lớp mới
            </a>
        </div>
    </div>
    
    <div class="card-body bg-light">
        
        <form action="" method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm mx-1">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tên lớp học..." value="<?php echo e(request('keyword')); ?>">
            </div>
            <div class="col-md-3">
                <select name="teacher_id" class="form-select">
                    <option value="">-- Giáo viên --</option>
                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($gv->id); ?>" <?php echo e(request('teacher_id') == $gv->id ? 'selected' : ''); ?>><?php echo e($gv->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>Đang mở</option>
                    <option value="0" <?php echo e(request('status') == '0' ? 'selected' : ''); ?>>Đã đóng</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100 fw-bold" type="submit">Lọc</button>
            </div>
            <?php if(request('keyword') || request('teacher_id') || request('status')): ?>
                <div class="col-12 ps-3 mt-2">
                    <a href="<?php echo e(route('admin.classes.index')); ?>" class="text-danger text-decoration-none"><i class="fas fa-times-circle me-1"></i> Xóa bộ lọc</a>
                </div>
            <?php endif; ?>
        </form>

        <?php if(session('success')): ?> 
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div> 
        <?php endif; ?>

        
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase">
                            <th class="text-center" width="50">ID</th>
                            <th class="ps-4">Tên Lớp học</th>
                            <th>Giáo viên phụ trách</th>
                            <th class="text-center">Sĩ số</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?php echo e($class->id); ?></td>
                            <td class="ps-4"><span class="fw-bold text-dark"><?php echo e($class->name); ?></span></td>
                            <td>
                                <?php if($class->teacher): ?>
                                    <div class="d-flex align-items-center">
                                        
                                        <?php if($class->teacher->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $class->teacher->avatar)); ?>" class="rounded-circle me-2 border shadow-sm" style="width: 35px; height: 35px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2 text-primary fw-bold border" style="width: 35px; height: 35px;"><?php echo e(substr($class->teacher->name, 0, 1)); ?></div>
                                        <?php endif; ?>
                                        <div><div class="fw-bold text-dark" style="font-size: 0.9rem;"><?php echo e($class->teacher->name); ?></div></div>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Chưa gán</span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="text-center" style="width: 150px;">
                                <?php 
                                    $percent = ($class->max_quantity > 0) ? ($class->current_quantity / $class->max_quantity) * 100 : 100;
                                    $color = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success');
                                ?>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-bold"><?php echo e($class->current_quantity); ?></span><span class="text-muted">/ <?php echo e($class->max_quantity); ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar <?php echo e($color); ?>" style="width: <?php echo e($percent); ?>%"></div>
                                </div>
                            </td>

                            
                            <td class="text-center">
                                <?php if($class->current_quantity >= $class->max_quantity): ?>
                                    <span class="badge bg-danger rounded-pill">Đã đầy</span>
                                <?php elseif($class->status == 1): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Mở</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Đóng</span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="text-center">
                                <a href="<?php echo e(route('admin.class.edit', $class->id)); ?>" class="btn btn-light btn-sm text-primary shadow-sm border" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                
                                <button type="button" class="btn btn-light btn-sm text-danger shadow-sm border ms-1" 
                                        data-bs-toggle="modal" data-bs-target="#softDeleteModal-<?php echo e($class->id); ?>" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>

                                
                                <div class="modal fade" id="softDeleteModal-<?php echo e($class->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-body text-center p-4">
                                                <div class="text-warning mb-3"><i class="fas fa-exclamation-triangle fa-3x"></i></div>
                                                <h5 class="fw-bold mb-2">Chuyển vào thùng rác?</h5>
                                                <p class="text-muted small mb-4">Lớp <strong><?php echo e($class->name); ?></strong> sẽ bị ẩn đi nhưng có thể khôi phục lại sau.</p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="<?php echo e(route('admin.class.destroy', $class->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-warning text-white btn-sm px-3 fw-bold">Đồng ý</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy lớp học nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3 border-top-0">
            <?php echo e($classrooms->appends(request()->query())->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/admin/classes/index.blade.php ENDPATH**/ ?>