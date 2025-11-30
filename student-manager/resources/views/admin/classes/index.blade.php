@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary" style="font-size: 1.25rem;">
            <i class="fas fa-chalkboard me-2"></i>Quản lý Lớp học phần
        </h5>
        <a href="{{ route('admin.class.create') }}" class="btn btn-primary btn-sm shadow-sm" style="font-size: 1rem;">
            <i class="fas fa-plus-circle me-1"></i> Thêm lớp mới
        </a>
    </div>
    
    <div class="card-body bg-light">
        
        <form action="" method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm mx-1">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" 
                       placeholder="Tên lớp học..." value="{{ request('keyword') }}" style="font-size: 1rem;">
            </div>
            <div class="col-md-3">
                <select name="teacher_id" class="form-select" style="font-size: 1rem;">
                    <option value="">-- Giáo viên --</option>
                    @foreach($teachers as $gv)
                        <option value="{{ $gv->id }}" {{ request('teacher_id') == $gv->id ? 'selected' : '' }}>
                            {{ $gv->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" style="font-size: 1rem;">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang mở</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đã đóng</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100 fw-bold" type="submit" style="font-size: 1rem;">Lọc</button>
            </div>
            
            @if(request('keyword') || request('teacher_id') || request('status'))
                <div class="col-12 ps-3 mt-2">
                    <a href="{{ route('admin.classes.index') }}" class="text-danger text-decoration-none" style="font-size: 0.9rem;">
                        <i class="fas fa-times-circle me-1"></i> Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>

        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="font-size: 1rem;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div> 
        @endif

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase">
                            <th class="text-center" width="50" style="font-size: 0.9rem;">ID</th>
                            <th class="ps-4" style="font-size: 0.9rem;">Tên Lớp học</th>
                            <th style="font-size: 0.9rem;">Giáo viên phụ trách</th>
                            <th class="text-center" style="font-size: 0.9rem;">Sĩ số</th>
                            <th class="text-center" style="font-size: 0.9rem;">Trạng thái</th>
                            <th class="text-center" style="font-size: 0.9rem;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $class)
                        <tr>
                            <td class="text-center fw-bold text-secondary" style="font-size: 1.1rem;">{{ $class->id }}</td>
                            
                            <td class="ps-4">
                                <span class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $class->name }}</span>
                            </td>

                            <td>
                                @if($class->teacher)
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2 text-primary fw-bold border" 
                                             style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            {{ substr($class->teacher->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">{{ $class->teacher->name }}</div>
                                            <div class="text-muted" style="font-size: 0.9rem;">{{ $class->teacher->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-danger-subtle text-danger" style="font-size: 0.9rem;">Chưa gán</span>
                                @endif
                            </td>
                            
                            <td class="text-center" style="width: 200px;">
                                @php 
                                    $percent = ($class->max_quantity > 0) ? ($class->current_quantity / $class->max_quantity) * 100 : 100;
                                    $color = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $class->current_quantity }}</span>
                                    <span class="text-muted" style="font-size: 0.9rem;">/ {{ $class->max_quantity }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $color }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($class->status == 1)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3" style="font-size: 0.9rem;">Mở</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3" style="font-size: 0.9rem;">Đóng</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ route('admin.class.edit', $class->id) }}" class="btn btn-light btn-sm text-primary shadow-sm border" title="Sửa">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('admin.class.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('CẢNH BÁO: Xóa lớp sẽ xóa hết đăng ký của SV!\nBạn chắc chắn chứ?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-light btn-sm text-danger shadow-sm border ms-1" title="Xóa">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5" style="font-size: 1.2rem;">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i><br>
                                Không tìm thấy lớp học nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <div class="text-muted" style="font-size: 1rem;">
                    Hiển thị <strong>{{ $classrooms->firstItem() }}</strong> - <strong>{{ $classrooms->lastItem() }}</strong> 
                    / <strong>{{ $classrooms->total() }}</strong> kết quả
                </div>
                <div>
                    {{ $classrooms->appends(request()->query())->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection