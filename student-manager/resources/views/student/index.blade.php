@extends('layouts.portal')

@section('content')
<div class="container py-4">
    
    <div class="row justify-content-center">
        <div class="col-12">

            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle fs-4 me-3"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>Cổng Đăng ký Học phần
                    </h5>
                </div>
                
                <div class="card-body bg-light">
                    <form action="" method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm mx-1">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                                       placeholder="Nhập tên môn học..." value="{{ request('keyword') }}">
                            </div>
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

                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3">Lớp học phần</th>
                                        <th>Giảng viên</th>
                                        <th class="text-center" width="200">Sĩ số</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-end pe-4">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classes as $class)
                                    <tr>
                                        <td class="ps-4"><span class="fw-bold text-dark">{{ $class->name }}</span></td>
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
                    </div>

                    <div class="card-footer bg-white py-3 border-top-0">
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

        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.05); transition: 0.2s; }
</style>
@endsection