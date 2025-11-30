@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">📚 Thêm Lớp học phần mới</h5>
        </div>
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.class.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Lớp học</label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="VD: Lập trình PHP - K15" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giáo viên giảng dạy</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Chọn Giáo viên --</option>
                        @foreach($teachers as $gv)
                            <option value="{{ $gv->id }}" {{ old('teacher_id') == $gv->id ? 'selected' : '' }}>
                                {{ $gv->name }} ({{ $gv->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sĩ số tối đa (MAX 20)</label>
                    <input type="number" name="max_quantity" class="form-control" 
                           value="{{ old('max_quantity', 20) }}" min="1" max="20">
                    <small class="text-muted">Hệ thống sẽ chặn đăng ký khi đạt con số này.</small>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu Lớp học
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection