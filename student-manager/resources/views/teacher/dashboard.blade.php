@extends('layouts.portal')

@section('content')
<div class="container py-4">
    
    {{-- [MỚI] 1. KHỐI HIỂN THỊ THÔNG BÁO THÀNH CÔNG --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    {{-- ------------------------------------------------ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Khu vực Giảng dạy</h2>
            <p class="text-muted mb-0">Giảng viên: {{ Auth::user()->name }}</p>
        </div>
    </div>

    {{-- 3 THẺ THỐNG KÊ --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            {{-- Link to Class List --}}
            <a href="{{ route('teacher.classes') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-white hover-lift" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-4 fw-bold mb-0">{{ $stats['count_classes'] }}</h2>
                                <p class="mb-0 opacity-75">Lớp đang phụ trách</p>
                            </div>
                            <i class="fas fa-chalkboard-teacher fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            {{-- Link to Student List (All Students) --}}
            <a href="{{ route('teacher.students') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-white hover-lift" style="background: linear-gradient(45deg, #1cc88a, #13855c);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-4 fw-bold mb-0">{{ $stats['count_students'] }}</h2>
                                <p class="mb-0 opacity-75">Tiến độ Chấm điểm</p>
                            </div>
                            <i class="fas fa-user-graduate fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
             {{-- Link to Student List (Filtered by Graded) --}}
            <a href="{{ route('teacher.students', ['filter' => 'graded']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-white hover-lift" style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-4 fw-bold mb-0">{{ $stats['count_graded'] }}</h2>
                                <p class="mb-0 opacity-75">Bảng Thành Tích</p>
                            </div>
                            <i class="fas fa-check-circle fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- BẢNG HOẠT ĐỘNG GẦN ĐÂY --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2"></i>Sinh viên mới vào lớp</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Sinh viên</th>
                        <th>Vào lớp</th>
                        <th>Thời gian</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_activities as $reg)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                {{-- LOGIC CHỐNG NULL: AVATAR --}}
                                @if($reg->student && $reg->student->avatar)
                                    <img src="{{ asset('storage/' . $reg->student->avatar) }}" 
                                         class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 fw-bold text-primary border" 
                                         style="width: 40px; height: 40px;">
                                        {{ substr($reg->student?->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                
                                <div>
                                    {{-- LOGIC CHỐNG NULL: TÊN --}}
                                    <div class="fw-bold text-dark">{{ $reg->student?->name ?? 'Sinh viên đã xóa' }}</div>
                                    <small class="text-muted">{{ $reg->student?->code ?? '---' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{-- LOGIC CHỐNG NULL: LỚP HỌC --}}
                            <span class="fw-bold text-primary">{{ $reg->classroom?->name ?? 'Lớp đã hủy' }}</span>
                        </td>
                        <td class="text-muted small">
                            {{ $reg->created_at->diffForHumans() }}
                        </td>
                        <td class="text-center">
                            @if($reg->score !== null)
                                <span class="badge bg-success">Đã có điểm</span>
                            @else
                                <span class="badge bg-secondary">Chưa chấm</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Chưa có hoạt động nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-5px); }
</style>
@endsection