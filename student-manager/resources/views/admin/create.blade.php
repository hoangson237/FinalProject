@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow border-0" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">Thêm Lớp học phần mới</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.class.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Lớp học</label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Lập trình PHP - K16" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giáo viên giảng dạy</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Chọn Giáo viên --</option>
                        @foreach($teachers as $gv)
                            <option value="{{ $gv->id }}">{{ $gv->name }} ({{ $gv->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sĩ số tối đa (Quota)</label>
                    <input type="number" name="max_quantity" class="form-control" value="40" min="1">
                    <small class="text-muted">Hệ thống sẽ tự động chặn đăng ký khi đạt con số này.</small>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu Lớp học</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection