@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    {{-- Header --}}
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary" style="font-size: 1.25rem;">
            <i class="fas fa-chalkboard me-2"></i>Quản lý Lớp học phần
        </h5>
        <div>
            <a href="{{ route('admin.classes.trash') }}" class="btn btn-outline-danger btn-sm shadow-sm me-2" style="font-size: 1rem;">
                <i class="fas fa-trash-alt me-1"></i> Thùng rác
            </a>
            <a href="{{ route('admin.class.create') }}" class="btn btn-primary btn-sm shadow-sm" style="font-size: 1rem;">
                <i class="fas fa-plus-circle me-1"></i> Thêm lớp mới
            </a>
        </div>
    </div>
    
    <div class="card-body bg-light">
        {{-- Form Lọc (Giữ nguyên code cũ của bạn) --}}
        <form action="" method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm mx-1">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tên lớp học..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-3">
                <select name="teacher_id" class="form-select">
                    <option value="">-- Giáo viên --</option>
                    @foreach($teachers as $gv)
                        <option value="{{ $gv->id }}" {{ request('teacher_id') == $gv->id ? 'selected' : '' }}>{{ $gv->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang mở</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đã đóng</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100 fw-bold" type="submit">Lọc</button>
            </div>
            @if(request('keyword') || request('teacher_id') || request('status'))
                <div class="col-12 ps-3 mt-2">
                    <a href="{{ route('admin.classes.index') }}" class="text-danger text-decoration-none"><i class="fas fa-times-circle me-1"></i> Xóa bộ lọc</a>
                </div>
            @endif
        </form>

        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div> 
        @endif

        {{-- Bảng Danh sách --}}
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
                        @forelse($classrooms as $class)
                        <tr>
                            <td class="text-center fw-bold text-secondary">{{ $class->id }}</td>
                            <td class="ps-4"><span class="fw-bold text-dark">{{ $class->name }}</span></td>
                            <td>
                                @if($class->teacher)
                                    <div class="d-flex align-items-center">
                                        {{-- Logic hiển thị Avatar (Giữ nguyên) --}}
                                        @if($class->teacher->avatar)
                                            <img src="{{ asset('storage/' . $class->teacher->avatar) }}" class="rounded-circle me-2 border shadow-sm" style="width: 35px; height: 35px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2 text-primary fw-bold border" style="width: 35px; height: 35px;">{{ substr($class->teacher->name, 0, 1) }}</div>
                                        @endif
                                        <div><div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $class->teacher->name }}</div></div>
                                    </div>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Chưa gán</span>
                                @endif
                            </td>
                            
                            {{-- Cột Sĩ số --}}
                            <td class="text-center" style="width: 150px;">
                                @php 
                                    $percent = ($class->max_quantity > 0) ? ($class->current_quantity / $class->max_quantity) * 100 : 100;
                                    $color = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-bold">{{ $class->current_quantity }}</span><span class="text-muted">/ {{ $class->max_quantity }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $color }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>

                            {{-- Cột Trạng thái --}}
                            <td class="text-center">
                                @if($class->current_quantity >= $class->max_quantity)
                                    <span class="badge bg-danger rounded-pill">Đã đầy</span>
                                @elseif($class->status == 1)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Mở</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Đóng</span>
                                @endif
                            </td>
                            
                            {{-- Cột Hành động (CÓ MODAL) --}}
                            <td class="text-center">
                                <a href="{{ route('admin.class.edit', $class->id) }}" class="btn btn-light btn-sm text-primary shadow-sm border" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                {{-- Nút Mở Modal Xóa Mềm --}}
                                <button type="button" class="btn btn-light btn-sm text-danger shadow-sm border ms-1" 
                                        data-bs-toggle="modal" data-bs-target="#softDeleteModal-{{ $class->id }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>

                                {{-- === MODAL XÓA MỀM === --}}
                                <div class="modal fade" id="softDeleteModal-{{ $class->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-body text-center p-4">
                                                <div class="text-warning mb-3"><i class="fas fa-exclamation-triangle fa-3x"></i></div>
                                                <h5 class="fw-bold mb-2">Chuyển vào thùng rác?</h5>
                                                <p class="text-muted small mb-4">Lớp <strong>{{ $class->name }}</strong> sẽ bị ẩn đi nhưng có thể khôi phục lại sau.</p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('admin.class.destroy', $class->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-warning text-white btn-sm px-3 fw-bold">Đồng ý</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- === END MODAL === --}}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy lớp học nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3 border-top-0">
            {{ $classrooms->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection