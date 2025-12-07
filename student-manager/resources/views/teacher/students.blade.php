@extends('layouts.portal')

@section('content')
<div class="container py-4">
    
    {{-- 1. CẤU HÌNH GIAO DIỆN ĐỘNG --}}
    @php
        $isGradedPage = request('filter') == 'graded';
        // Logic màu: Nếu là trang điểm (graded) thì màu Vàng, còn lại là Xanh (primary)
        $themeColor = $isGradedPage ? 'warning' : 'primary'; 
    @endphp

    {{-- 2. HEADER: TIÊU ĐỀ & THANH CHUYỂN ĐỔI (ĐÃ SỬA) --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        
        {{-- Phần Tiêu đề: Đổi nội dung tùy theo Tab đang chọn --}}
        <div>
            @if($isGradedPage)
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-trophy me-2 text-warning"></i>Bảng Thành Tích
                </h3>
                <p class="text-muted mb-0">Danh sách sinh viên đã có điểm.</p>
            @else
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-tasks me-2 text-primary"></i>Tiến độ Chấm điểm
                </h3>
                <p class="text-muted mb-0">Danh sách cần rà soát và nhập điểm.</p>
            @endif
        </div>

            {{-- Nút Dashboard tách riêng --}}
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary shadow-sm rounded-circle p-2" title="Quay về Dashboard" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-home"></i>
            </a>
        </div>
    </div>

    {{-- 3. BẢNG DỮ LIỆU --}}
    <div class="card border-0 shadow-sm border-top border-4 border-{{ $themeColor }}">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary text-uppercase small">
                    <tr>
                        <th class="ps-4">Thông tin Sinh viên</th>
                        <th>Lớp học phần</th>
                        
                        {{-- Tiêu đề cột giữa thay đổi theo trang --}}
                        <th class="text-center">
                            @if($isGradedPage)
                                ĐIỂM SỐ
                            @else
                                TRẠNG THÁI
                            @endif
                        </th>
                        
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $reg)
                    {{-- Row background đổi màu nhẹ nếu là trang graded --}}
                    <tr class="{{ $isGradedPage ? 'bg-warning bg-opacity-10' : '' }}">
                        
                        {{-- Cột 1: Sinh viên --}}
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                {{-- Avatar --}}
                                <div class="rounded-circle text-white d-flex justify-content-center align-items-center me-3 fw-bold shadow-sm" 
                                     style="width: 45px; height: 45px; font-size: 1.2rem; background-color: {{ $isGradedPage ? '#f6c23e' : '#4e73df' }}">
                                    {{ substr($reg->student?->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $reg->student?->name ?? 'Sinh viên đã xóa' }}</div>
                                    <small class="text-muted">{{ $reg->student?->code ?? '---' }}</small>
                                </div>
                            </div>
                        </td>

                        {{-- Cột 2: Lớp --}}
                        <td>
                            <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                                {{ $reg->classroom?->name ?? 'Lớp đã hủy' }}
                            </span>
                        </td>
                        
                        {{-- Cột 3: Nội dung Động --}}
                        <td class="text-center">
                            @if($isGradedPage)
                                {{-- TRANG VÀNG: HIỆN ĐIỂM SỐ --}}
                                <div class="d-inline-block px-3 py-1 rounded-pill bg-warning text-dark fw-bold border border-warning shadow-sm">
                                    {{ number_format($reg->score, 2) }}
                                </div>
                            @else
                                {{-- TRANG XANH: HIỆN TRẠNG THÁI --}}
                                @if($reg->score !== null)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                        <i class="fas fa-check me-1"></i> Đã châ
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                        <i class="fas fa-clock me-1"></i> Chờ chấm
                                    </span>
                                @endif
                            @endif
                        </td>

                        {{-- Cột 4: Nút Hành động --}}
                        <td class="text-center">
                            @if($isGradedPage)
                                <a href="{{ route('teacher.class.show', $reg->classroom_id) }}#reg-{{ $reg->id }}" 
                                   class="btn btn-sm btn-outline-dark shadow-sm px-3">
                                    <i class="fas fa-search me-1"></i> Xem lại
                                </a>
                            @else
                                @if($reg->score === null)
                                    <a href="{{ route('teacher.class.show', $reg->classroom_id) }}#reg-{{ $reg->id }}" 
                                       class="btn btn-sm btn-primary shadow-sm px-3">
                                        <i class="fas fa-pen-nib me-1"></i> Chấm ngay
                                    </a>
                                @else
                                    <a href="{{ route('teacher.class.show', $reg->classroom_id) }}#reg-{{ $reg->id }}" 
                                       class="btn btn-sm btn-outline-secondary shadow-sm px-3">
                                        <i class="fas fa-edit me-1"></i> Sửa điểm
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3 opacity-25"></i><br>
                            Không tìm thấy dữ liệu phù hợp.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- 4. PHÂN TRANG --}}
        @if($students->hasPages())
        <div class="card-footer bg-white py-3">
             {{-- Giữ filter khi chuyển trang --}}
            {{ $students->appends(['filter' => request('filter')])->links() }}
        </div>
        @endif
    </div>
</div>
@endsection