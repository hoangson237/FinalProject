@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow border-0" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-warning">✏️ Cập nhật Lớp học phần</h5>
        </div>
        
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    Vui lòng kiểm tra lại thông tin nhập.
                </div>
            @endif

            <form action="{{ route('admin.class.update', $class->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Lớp học</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $class->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giáo viên giảng dạy</label>
                    <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Giáo viên --</option>
                        @foreach($teachers as $gv)
                            <option value="{{ $gv->id }}" 
                                    {{ old('teacher_id', $class->teacher_id) == $gv->id ? 'selected' : '' }}>
                                {{ $gv->name }} ({{ $gv->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sĩ số tối đa (MAX 20)</label>
                    <input type="number" name="max_quantity" class="form-control @error('max_quantity') is-invalid @enderror" 
                           value="{{ old('max_quantity', $class->max_quantity) }}" min="1" max="20">
                    <small class="text-muted">Lưu ý: Sĩ số không được vượt quá 20.</small>
                    
                    @error('max_quantity')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-warning fw-bold">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection