@extends('layouts.portal')

@section('content')
<div class="container py-4">
    
    {{-- 1. THÔNG BÁO SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 2. CẤU HÌNH GIAO DIỆN --}}
    @php
        $isGradedPage = request('filter') == 'graded';
        $themeColor = $isGradedPage ? 'warning' : 'primary'; 
    @endphp

    {{-- 3. HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            @if($isGradedPage)
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-trophy me-2 text-warning"></i>Bảng Thành Tích</h3>
                <p class="text-muted mb-0">Danh sách sinh viên đã có điểm.</p>
            @else
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-tasks me-2 text-primary"></i>Tiến độ Chấm điểm</h3>
                <p class="text-muted mb-0">Danh sách cần rà soát và nhập điểm.</p>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($isGradedPage)
                @if(request('sort') == 'desc')
                    <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-undo me-1"></i> Mặc định
                    </a>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-sort-amount-down me-1"></i> Xếp điểm cao
                    </a>
                @endif
            @else
                <a href="{{ route('teacher.students', ['filter' => 'graded']) }}" class="btn btn-warning shadow-sm text-dark fw-bold">
                    <i class="fas fa-star me-1"></i> Xem Bảng điểm
                </a>
            @endif

            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary shadow-sm" title="Quay về Dashboard">
                <i class="fas fa-home me-1"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- [THÊM MỚI] 3.5. KHỐI TÌM KIẾM (GIAO DIỆN GIỐNG SINH VIÊN) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            {{-- Tiêu đề nhỏ cho khối tìm kiếm --}}
            <h5 class="fw-bold text-primary mb-3">
                <i class="fas fa-search me-2"></i>Tra cứu Sinh viên
            </h5>

            <form action="{{ route('teacher.students') }}" method="GET">
                {{-- Giữ lại các tham số filter và sort cũ --}}
                @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                <div class="row g-2">
                    <div class="col">
                        {{-- Ô Input lớn --}}
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="keyword" class="form-control border-start-0 ps-0 bg-white" 
                                   placeholder="Nhập tên hoặc mã sinh viên..." 
                                   value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        {{-- Nút bấm lớn --}}
                        <button class="btn btn-primary btn-lg px-4 fw-bold" type="submit">
                            Tìm kiếm
                        </button>
                    </div>
                </div>

                {{-- Dòng xóa tìm kiếm nằm bên dưới (Màu đỏ) --}}
                @if(request('keyword'))
                <div class="mt-2">
                    <a href="{{ route('teacher.students', ['filter' => request('filter'), 'sort' => request('sort')]) }}" 
                       class="text-danger text-decoration-none small fw-bold">
                        <i class="fas fa-times-circle me-1"></i> Xóa tìm kiếm
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- 4. BẢNG DỮ LIỆU --}}
    <div class="card border-0 shadow-sm border-top border-4 border-{{ $themeColor }}">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary text-uppercase small">
                    <tr>
                        <th class="ps-4">Thông tin Sinh viên</th>
                        <th>Lớp học phần</th>
                        <th class="text-center">@if($isGradedPage) ĐIỂM SỐ @else TRẠNG THÁI @endif</th>
                        
                        @if(!$isGradedPage)
                            <th class="text-center">Hành động</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $reg)
                    <tr class="{{ $isGradedPage ? 'bg-warning bg-opacity-10' : '' }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
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
                        <td>
                            <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                                {{ $reg->classroom?->name ?? 'Lớp đã hủy' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($isGradedPage)
                                <div class="d-inline-block px-3 py-1 rounded-pill bg-warning text-dark fw-bold border border-warning shadow-sm">
                                    {{ number_format($reg->score, 2) }}
                                </div>
                            @else
                                @if($reg->score !== null)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                        <i class="fas fa-check me-1"></i> Đã xong
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 animate-pulse">
                                        <i class="fas fa-clock me-1"></i> Chờ chấm
                                    </span>
                                @endif
                            @endif
                        </td>

                        @if(!$isGradedPage)
                        <td class="text-center">
                            @if($reg->score === null)
                                <a href="{{ route('teacher.class.show', $reg->classroom_id) }}#reg-{{ $reg->id }}" class="btn btn-sm btn-primary shadow-sm px-3">
                                    <i class="fas fa-pen-nib me-1"></i> Chấm ngay
                                </a>
                            @else
                                <a href="{{ route('teacher.class.show', $reg->classroom_id) }}#reg-{{ $reg->id }}" class="btn btn-sm btn-outline-secondary shadow-sm px-3">
                                    <i class="fas fa-edit me-1"></i> Sửa điểm
                                </a>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isGradedPage ? 3 : 4 }}" class="text-center py-5 text-muted">Không tìm thấy dữ liệu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- 5. PHÂN TRANG --}}
        @if($students->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center w-100 px-2">
                <div class="text-muted small mb-2 mb-md-0">
                    Hiển thị <strong>{{ $students->firstItem() }}</strong> - <strong>{{ $students->lastItem() }}</strong> 
                    trên tổng <strong>{{ $students->total() }}</strong> kết quả
                </div>
                <div class="clean-pagination">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .clean-pagination p.small.text-muted { display: none !important; }
    .clean-pagination nav > div.d-none.d-md-flex > div:first-child { display: none !important; }
    .clean-pagination nav > div.d-none.d-md-flex { justify-content: flex-end !important; }
    .clean-pagination ul.pagination { margin-bottom: 0 !important; }
</style>
@endsection