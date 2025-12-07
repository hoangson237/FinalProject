@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Tổng quan Hệ thống</h2>
            <p class="text-muted mb-0">Chào mừng quay trở lại, {{ Auth::user()->name }}!</p>
        </div>
        <div class="text-end text-muted small">
            <i class="far fa-calendar-alt me-1"></i> {{ date('d/m/Y') }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-5">
        {{-- Card Sinh viên --}}
        <div class="col-md-4">
            <a href="{{ route('admin.students.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden hover-lift card-blue" 
                     style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border-radius: 15px;">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold text-uppercase small opacity-75" style="color: white;">Sinh viên</div>
                            <span class="badge bg-white text-primary rounded-pill px-3">Quản lý</span>
                        </div>
                        <h3 class="display-5 fw-bold mb-0 text-white">{{ $stats['students'] }}</h3>
                        <i class="fas fa-user-graduate fa-6x position-absolute opacity-10 text-white" style="right: -10px; bottom: -10px; transform: rotate(-15deg);"></i>
                        <div class="mt-3 small text-white opacity-75">
                            Xem chi tiết <i class="fas fa-arrow-circle-right ms-1"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card Giáo viên --}}
        <div class="col-md-4">
            <a href="{{ route('admin.teachers.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden hover-lift card-green" 
                     style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border-radius: 15px;">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold text-uppercase small opacity-75" style="color: white;">Giáo viên</div>
                            <span class="badge bg-white text-success rounded-pill px-3">Nhân sự</span>
                        </div>
                        <h3 class="display-5 fw-bold mb-0 text-white">{{ $stats['teachers'] }}</h3>
                        <i class="fas fa-chalkboard-teacher fa-6x position-absolute opacity-10 text-white" style="right: -10px; bottom: -10px; transform: rotate(-15deg);"></i>
                        <div class="mt-3 small text-white opacity-75">
                            Xem chi tiết <i class="fas fa-arrow-circle-right ms-1"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card Lớp học --}}
        <div class="col-md-4">
            <a href="{{ route('admin.classes.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 overflow-hidden hover-lift card-orange" 
                     style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); border-radius: 15px;">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold text-uppercase small opacity-75" style="color: white;">Lớp học phần</div>
                            <span class="badge bg-white text-warning rounded-pill px-3">Đào tạo</span>
                        </div>
                        <h3 class="display-5 fw-bold mb-0 text-white">{{ $stats['classes'] }}</h3>
                        <i class="fas fa-book-open fa-6x position-absolute opacity-10 text-white" style="right: -10px; bottom: -10px; transform: rotate(-15deg);"></i>
                        <div class="mt-3 small text-white opacity-75 fw-bold">
                            Quản lý ngay <i class="fas fa-arrow-circle-right ms-1"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Bảng Hoạt động --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-info"></i>Hoạt động đăng ký mới nhất</h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Sinh viên</th>
                        <th>Vừa đăng ký lớp</th>
                        <th>Thời gian</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_registrations as $reg)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                {{-- FIX LỖI NULL: Dùng toán tử ?? để hiển thị dấu ? nếu không tìm thấy tên --}}
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-2 small fw-bold" 
                                     style="width: 35px; height: 35px;">
                                    {{ substr($reg->student?->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    {{-- FIX LỖI NULL: Kiểm tra nếu student null thì hiện "Sinh viên đã xóa" --}}
                                    <div class="fw-bold text-dark">{{ $reg->student?->name ?? 'Sinh viên đã xóa' }}</div>
                                    <small class="text-muted">{{ $reg->student?->code ?? '---' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{-- FIX LỖI NULL: Kiểm tra classroom --}}
                            <span class="text-primary fw-bold">{{ $reg->classroom?->name ?? 'Lớp học đã xóa' }}</span>
                        </td>
                        <td class="text-muted small">
                            <i class="far fa-clock me-1"></i>
                            {{ $reg->created_at->diffForHumans() }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                Thành công
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Chưa có hoạt động đăng ký nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; }
</style>
@endsection