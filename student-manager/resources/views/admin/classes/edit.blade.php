@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i>Cập nhật Lớp học phần</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('admin.class.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 1. Tên & GV --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Lớp học</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giáo viên</label>
                        <select name="teacher_id" class="form-select">
                            @foreach($teachers as $gv)
                                <option value="{{ $gv->id }}" {{ $class->teacher_id == $gv->id ? 'selected' : '' }}>
                                    {{ $gv->name }} ({{ $gv->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Lịch học --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lịch học chi tiết</label>
                        <div class="card p-3 bg-light border-0">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="small fw-bold text-muted mb-2">Ngày trong tuần:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'] as $day)
                                            @php 
                                                $isChecked = str_contains($class->schedule, $day); 
                                            @endphp
                                            <input type="checkbox" class="btn-check" name="days[]" id="editBtn{{$day}}" value="{{$day}}" 
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary bg-white" for="editBtn{{$day}}">{{$day}}</label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="small fw-bold text-muted mb-2">Ca học:</label>
                                    <select name="shift" class="form-select bg-white" required>
                                        <option value="Ca 1 (7h - 9h)" {{ str_contains($class->schedule, 'Ca 1') ? 'selected' : '' }}>Ca 1 (7h - 9h)</option>
                                        <option value="Ca 2 (9h - 11h)" {{ str_contains($class->schedule, 'Ca 2') ? 'selected' : '' }}>Ca 2 (9h - 11h)</option>
                                        <option value="Ca 3 (12h - 14h)" {{ str_contains($class->schedule, 'Ca 3') ? 'selected' : '' }}>Ca 3 (12h - 14h)</option>
                                        <option value="Ca 4 (14h - 16h)" {{ str_contains($class->schedule, 'Ca 4') ? 'selected' : '' }}>Ca 4 (14h - 16h)</option>
                                        <option value="Ca Tối (18h - 21h)" {{ str_contains($class->schedule, 'Ca Tối') ? 'selected' : '' }}>Ca Tối (18h - 21h)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Phòng - Ngày - Sĩ số --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phòng học</label>
                            <select name="room" class="form-select" required>
                                @foreach(['Phòng A101 (Tòa A)', 'Phòng A102 (Tòa A)', 'Phòng Lab 1 (Thực hành)', 'Phòng Lab 2 (Thực hành)', 'Hội trường B'] as $r)
                                    <option value="{{ $r }}" {{ str_contains($class->room, $r) ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $class->start_date }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sĩ số tối đa</label>
                            <div class="input-group">
                                <input type="number" name="max_quantity" class="form-control" 
                                       value="{{ $class->max_quantity }}" min="1" max="50">
                                <span class="input-group-text text-muted">Max: 50</span>
                            </div>
                            <div class="form-text">Hiện tại: {{ $class->current_quantity }} SV.</div>
                        </div>
                    </div>

                    {{-- 5. Trạng thái --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Trạng thái</label>
                        @php $isFull = $class->current_quantity >= $class->max_quantity; @endphp
                        @if($isFull)
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white"><i class="fas fa-ban"></i></span>
                                <select class="form-select bg-light" disabled><option>🔴 Đã đóng (Full)</option></select>
                            </div>
                            <input type="hidden" name="status" value="0">
                        @else
                            <select name="status" class="form-select">
                                <option value="1" {{ $class->status == 1 ? 'selected' : '' }}>🟢 Đang mở</option>
                                <option value="0" {{ $class->status == 0 ? 'selected' : '' }}>🔴 Đóng lớp</option>
                            </select>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection