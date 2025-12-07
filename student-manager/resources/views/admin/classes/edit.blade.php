@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-edit me-2"></i>Cập nhật Lớp học phần
                </h5>
            </div>
            
            <div class="card-body p-4">
                {{-- Form Sửa --}}
                <form action="{{ route('admin.class.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 1. Tên lớp --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Lớp học</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $class->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. Giáo viên --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giáo viên giảng dạy</label>
                        <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                            <option value="">-- Chọn Giáo viên --</option>
                            @foreach($teachers as $gv)
                                <option value="{{ $gv->id }}" {{ old('teacher_id', $class->teacher_id) == $gv->id ? 'selected' : '' }}>
                                    {{ $gv->name }} ({{ $gv->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. Sĩ số tối đa --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sĩ số tối đa (MAX)</label>
                        <input type="number" name="max_quantity" class="form-control @error('max_quantity') is-invalid @enderror" 
                               value="{{ old('max_quantity', $class->max_quantity) }}" min="1" required>
                        <div class="form-text">Lưu ý: Sĩ số không được nhỏ hơn sĩ số hiện tại ({{ $class->current_quantity }}).</div>
                        @error('max_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 4. Trạng thái (LOGIC MỚI Ở ĐÂY) --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Trạng thái Lớp học</label>

                        @php
                            // Kiểm tra xem lớp có đầy không
                            $isFull = $class->current_quantity >= $class->max_quantity;
                        @endphp

                        @if($isFull)
                            {{-- TRƯỜNG HỢP 1: LỚP ĐẦY -> Disable Select và hiện cảnh báo --}}
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white"><i class="fas fa-ban"></i></span>
                                <select class="form-select bg-light text-secondary" disabled>
                                    <option selected>🔴 Đã đóng (Lớp đã đầy sĩ số)</option>
                                </select>
                            </div>
                            
                            {{-- Input ẩn để vẫn gửi giá trị 0 (Đóng) về server --}}
                            <input type="hidden" name="status" value="0">

                            <div class="alert alert-warning mt-2 d-flex align-items-center shadow-sm" role="alert">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                                <div>
                                    <strong>Không thể mở lớp này!</strong><br>
                                    Sĩ số hiện tại <strong>({{ $class->current_quantity }}/{{ $class->max_quantity }})</strong> đã đầy. 
                                    Vui lòng tăng "Sĩ số tối đa" ở trên trước nếu muốn mở lại lớp.
                                </div>
                            </div>
                        @else
                            {{-- TRƯỜNG HỢP 2: CÒN CHỖ -> Cho phép chọn bình thường --}}
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status', $class->status) == 1 ? 'selected' : '' }}>
                                    🟢 Đang mở (Cho phép đăng ký)
                                </option>
                                <option value="0" {{ old('status', $class->status) == 0 ? 'selected' : '' }}>
                                    🔴 Đóng lớp (Ngưng tuyển sinh)
                                </option>
                            </select>
                            <div class="form-text text-muted">
                                Nếu đóng, sinh viên sẽ không thấy nút đăng ký nữa.
                            </div>
                        @endif

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nút bấm --}}
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection