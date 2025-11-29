@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none">
            <div class="card bg-primary text-white shadow border-0 h-100 hover-scale">
                <div class="card-body position-relative">
                    <h3 class="display-4 fw-bold">{{ $stats['students'] }}</h3>
                    <p class="fs-5 mb-0">Tổng Sinh viên</p>
                    <i class="fas fa-user-graduate fa-4x opacity-25 position-absolute end-0 top-50 translate-middle-y me-3"></i>
                    <div class="mt-3 small">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white shadow border-0 h-100">
            <div class="card-body position-relative">
                <h3 class="display-4 fw-bold">{{ $stats['teachers'] }}</h3>
                <p class="fs-5 mb-0">Tổng Giáo viên</p>
                <i class="fas fa-chalkboard-teacher fa-4x opacity-25 position-absolute end-0 top-50 translate-middle-y me-3"></i>
                <div class="mt-3 small">
                    Đang giảng dạy
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('admin.classes.index') }}" class="text-decoration-none">
            <div class="card bg-warning text-dark shadow border-0 h-100 hover-scale">
                <div class="card-body position-relative">
                    <h3 class="display-4 fw-bold">{{ $stats['classes'] }}</h3>
                    <p class="fs-5 mb-0">Lớp học phần</p>
                    <i class="fas fa-book-open fa-4x opacity-25 position-absolute end-0 top-50 translate-middle-y me-3"></i>
                    <div class="mt-3 small fw-bold">
                        Quản lý ngay <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    /* Hiệu ứng khi di chuột vào thẻ: Phóng to nhẹ */
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.03); cursor: pointer; }
</style>
@endsection