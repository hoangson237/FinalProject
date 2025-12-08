@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">📚 Thêm Lớp học phần mới</h5>
        </div>
        
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-0">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.class.store') }}" method="POST">
                @csrf
                
                {{-- 1. Tên lớp --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Lớp học <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" 
                           placeholder="VD: Lập trình PHP - K15" value="{{ old('name') }}" required>
                </div>

                {{-- 2. Giáo viên --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Giáo viên giảng dạy <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Chọn Giáo viên --</option>
                        @foreach($teachers as $gv)
                            <option value="{{ $gv->id }}" {{ old('teacher_id') == $gv->id ? 'selected' : '' }}>
                                {{ $gv->name }} ({{ $gv->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Lịch học --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Lịch học chi tiết <span class="text-danger">*</span></label>
                    <div class="card p-3 bg-light border-0">
                        <div class="row">
                            <div class="col-md-7">
                                <label class="small fw-bold text-muted mb-2">Ngày trong tuần (Chọn ít nhất 1):</label>
                                <div class="d-flex flex-wrap gap-2">
                                    {{-- Nếu không học T7, CN thì Admin chỉ cần không tích vào là được --}}
                                    @foreach(['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'] as $day)
                                        <input type="checkbox" class="btn-check" name="days[]" id="btn{{$day}}" value="{{$day}}" 
                                            {{ is_array(old('days')) && in_array($day, old('days')) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary bg-white" for="btn{{$day}}">{{$day}}</label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="small fw-bold text-muted mb-2">Ca học:</label>
                                <select name="shift" class="form-select bg-white" required>
                                    <option value="">-- Chọn Ca --</option>
                                    <option value="Ca 1 (7h - 9h)" {{ old('shift') == 'Ca 1 (7h - 9h)' ? 'selected' : '' }}>Ca 1 (7h - 9h)</option>
                                    <option value="Ca 2 (9h - 11h)" {{ old('shift') == 'Ca 2 (9h - 11h)' ? 'selected' : '' }}>Ca 2 (9h - 11h)</option>
                                    <option value="Ca 3 (12h - 14h)" {{ old('shift') == 'Ca 3 (12h - 14h)' ? 'selected' : '' }}>Ca 3 (12h - 14h)</option>
                                    <option value="Ca 4 (14h - 16h)" {{ old('shift') == 'Ca 4 (14h - 16h)' ? 'selected' : '' }}>Ca 4 (14h - 16h)</option>
                                    <option value="Ca Tối (18h - 21h)" {{ old('shift') == 'Ca Tối (18h - 21h)' ? 'selected' : '' }}>Ca Tối (18h - 21h)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Phòng - Ngày - Sĩ số --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Phòng học <span class="text-danger">*</span></label>
                        <select name="room" class="form-select" required>
                            <option value="">-- Chọn Phòng --</option>
                            <option value="Phòng A101 (Tòa A)" {{ old('room') == 'Phòng A101 (Tòa A)' ? 'selected' : '' }}>Phòng A101 (Tòa A)</option>
                            <option value="Phòng A102 (Tòa A)" {{ old('room') == 'Phòng A102 (Tòa A)' ? 'selected' : '' }}>Phòng A102 (Tòa A)</option>
                            <option value="Phòng Lab 1 (Thực hành)" {{ old('room') == 'Phòng Lab 1 (Thực hành)' ? 'selected' : '' }}>Phòng Lab 1 (Thực hành)</option>
                            <option value="Phòng Lab 2 (Thực hành)" {{ old('room') == 'Phòng Lab 2 (Thực hành)' ? 'selected' : '' }}>Phòng Lab 2 (Thực hành)</option>
                            <option value="Hội trường B" {{ old('room') == 'Hội trường B' ? 'selected' : '' }}>Hội trường B</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sĩ số tối đa</label>
                        <div class="input-group">
                            <input type="number" name="max_quantity" class="form-control" 
                                   value="{{ old('max_quantity', 40) }}" min="1" max="50">
                            <span class="input-group-text text-muted">Max: 50</span>
                        </div>
                        <div class="form-text text-muted small">Giới hạn tối đa 50 sinh viên/lớp.</div>
                    </div>
                </div>

                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Lớp học
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection