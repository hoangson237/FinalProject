@extends('layouts.portal')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 overflow-hidden"> 
        
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-graduation-cap me-2"></i>Cổng Đăng ký Học phần
            </h5>
        </div>
        
        <div class="p-3 bg-light">
            <form action="" method="GET" class="row g-3 p-3 bg-white rounded shadow-sm mx-0 mb-0"> <div class="col-md-10">
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="Nhập tên môn học..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100 fw-bold" type="submit">Tìm kiếm</button>
                </div>
                @if(request('keyword'))
                    <div class="col-12 ps-3 mt-2">
                        <a href="{{ route('student.register') }}" class="text-danger text-decoration-none small">
                            <i class="fas fa-times-circle"></i> Xóa tìm kiếm
                        </a>
                    </div>
                @endif
            </form>

            @if(session('success')) 
                <div class="alert alert-success shadow-sm mt-3 mb-0"><i class="fas fa-check-circle me-1"></i> {{ session('success') }}</div> 
            @endif
            @if(session('error')) 
                <div class="alert alert-danger shadow-sm mt-3 mb-0"><i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}</div> 
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle"> 
                <thead class="table-light text-secondary border-bottom"> <tr>
                        <th class="ps-4 py-3">Tên Lớp học</th>
                        <th class="py-3">Giảng viên</th>
                        <th class="text-center py-3" width="150">Sĩ số</th>
                        <th class="text-center py-3">Trạng thái</th>
                        <th class="text-end pe-4 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($classes as $class)
                    <tr>
                        <td class="ps-4 py-3"><span class="fw-bold text-dark">{{ $class->name }}</span></td>
                        <td>{{ $class->teacher->name ?? 'Chưa gán' }}</td>
                        
                        <td class="text-center">
                            @php 
                                $percent = ($class->max_quantity > 0) ? ($class->current_quantity / $class->max_quantity) * 100 : 100;
                                $color = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-bold">{{ $class->current_quantity }}</span><span class="text-muted">/ {{ $class->max_quantity }}</span>
                            </div>
                            <div class="progress" style="height: 6px;"><div class="progress-bar {{ $color }}" style="width: {{ $percent }}%"></div></div>
                        </td>

                        <td class="text-center">
                            @if($class->current_quantity >= $class->max_quantity)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Hết chỗ</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Đang mở</span>
                            @endif
                        </td>

                        <td class="text-end pe-4">
                            @if(in_array($class->id, $my_registered_ids))
                                <button class="btn btn-secondary btn-sm" disabled><i class="fas fa-check"></i> Đã ĐK</button>
                            @elseif($class->current_quantity >= $class->max_quantity)
                                <button class="btn btn-outline-danger btn-sm" disabled>Full</button>
                            @else
                                <form action="{{ route('student.postRegister', $class->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm">Đăng ký ngay</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Không có lớp học nào đang mở.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3 border-top">
            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <div class="text-muted small">
                    Hiển thị <strong>{{ $classes->firstItem() }}</strong> - <strong>{{ $classes->lastItem() }}</strong> 
                    / <strong>{{ $classes->total() }}</strong> lớp
                </div>
                <div>{{ $classes->appends(request()->query())->links('pagination.admin') }}</div>
            </div>
        </div>

    </div>
</div>
@endsection