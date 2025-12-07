

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white py-3">
            <h5>✏️ Cập nhật thông tin Giáo viên</h5>
        </div>
        
        <div class="card-body">
            <form action="<?php echo e(route('admin.teachers.update', $teacher->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="mb-3">
                    <label>Mã Giáo viên</label>
                    <input type="text" name="code" class="form-control" value="<?php echo e($teacher->code); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($teacher->name); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($teacher->email); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Ảnh đại diện</label>
                    <?php if($teacher->avatar): ?>
                        <div class="my-2">
                            <img src="<?php echo e(asset('storage/'.$teacher->avatar)); ?>" class="rounded-circle border" width="80" height="80">
                            <br><small class="text-muted">Ảnh hiện tại</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="avatar" class="form-control">
                </div>
                
                <hr>

                <button type="submit" class="btn btn-success w-100 fw-bold">Cập nhật thông tin</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH G:\laragon\www\FinalProject\student-manager\resources\views/admin/teachers/edit.blade.php ENDPATH**/ ?>