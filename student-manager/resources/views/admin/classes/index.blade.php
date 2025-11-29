@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary">📚 Quản lý Lớp học phần</h5>
        <a href="{{ route('admin.class.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm lớp mới
        </a>
    </div>
    
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="50">#ID</th>
                        <th>Tên Lớp học</th>
                        <th>Giáo viên phụ trách</th>
                        <th class="text-center">Sĩ số (Đã ĐK / Max)</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center" width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $class)
                    <tr>
                        <td class="text-center">{{ $class->id }}</td>
                        
                        <td class="fw-bold text-primary">{{ $class->name }}</td>
                        
                        <td>
                            @if($class->teacher)
                                <span class="fw-bold">{{ $class->teacher->name }}</span><br>
                                <small class="text-muted">{{ $class->teacher->email }}</small>
                            @else
                                <span class="text-danger fst-italic">--- Chưa gán ---</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($class->current_quantity >= $class->max_quantity)
                                <span class="badge bg-danger rounded-pill px-3">
                                    {{ $class->current_quantity }} / {{ $class->max_quantity }} (Full)
                                </span>
                            @else
                                <span class="badge bg-success rounded-pill px-3">
                                    {{ $class->current_quantity }} / {{ $class->max_quantity }}
                                </span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($class->status == 1)
                                <span class="badge bg-primary">Đang mở</span>
                            @else
                                <span class="badge bg-secondary">Đã đóng</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            <a href="{{ route('admin.class.edit', $class->id) }}" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.class.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('CẢNH BÁO: Xóa lớp học sẽ xóa toàn bộ danh sách đăng ký của sinh viên trong lớp này.\nBạn có chắc chắn muốn xóa không?');">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa lớp">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Chưa có lớp học nào. Hãy bấm "Thêm lớp mới" để bắt đầu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $classrooms->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection