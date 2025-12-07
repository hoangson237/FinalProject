@extends('layouts.portal')

@section('content')
<div class="container py-4">
    {{-- 1. HEADER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-book-reader me-2"></i>{{ $class->name }}
                </h4>
                <div class="text-muted">
                    <i class="fas fa-users me-1"></i> Sĩ số: <strong>{{ $class->registrations->count() }}/{{ $class->max_quantity }}</strong>
                </div>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    {{-- 2. ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 3. DANH SÁCH --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-list-ol me-2 text-primary"></i>Danh sách Sinh viên & Nhập điểm
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Thông tin Sinh viên</th>
                        <th class="text-center">Mã SV</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center" style="width: 180px;">Điểm số (0-10)</th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->registrations as $reg)
                    <tr id="reg-{{ $reg->id }}" class="student-row transition-bg">
                        
                        {{-- Cột 1: Thông tin --}}
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($reg->student && $reg->student->avatar)
                                    <img src="{{ asset('storage/' . $reg->student->avatar) }}" 
                                         class="rounded-circle me-3 border shadow-sm" width="45" height="45" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary bg-gradient text-white d-flex justify-content-center align-items-center me-3 fw-bold shadow-sm" 
                                         style="width: 45px; height: 45px; font-size: 1.2rem;">
                                        {{ substr($reg->student?->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                
                                <div>
                                    <div class="fw-bold text-dark">{{ $reg->student?->name ?? 'Sinh viên đã xóa' }}</div>
                                    <small class="text-muted">{{ $reg->student?->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>

                        {{-- Cột 2: Mã SV --}}
                        <td class="text-center fw-bold text-secondary">
                            {{ $reg->student?->code ?? '---' }}
                        </td>

                        {{-- Cột 3: Trạng thái --}}
                        <td class="text-center">
                             <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                Đang học
                            </span>
                        </td>

                        {{-- Cột 4: Input Điểm (Chứa Form) --}}
                        <td class="text-center">
                            <form action="{{ route('teacher.score.update', $reg->id) }}" method="POST" id="form-score-{{ $reg->id }}">
                                @csrf
                                
                                {{-- [QUAN TRỌNG] Input ẩn báo hiệu chuyển hướng sang trang Students --}}
                                <input type="hidden" name="redirect_to" value="students">
                                
                                <input type="number" step="0.01" min="0" max="10" name="score" 
                                       class="form-control text-center fw-bold score-input {{ $reg->score >= 5 ? 'text-success' : ($reg->score !== null ? 'text-danger' : '') }}" 
                                       value="{{ $reg->score }}" 
                                       placeholder="..."
                                       {{ !$reg->student ? 'disabled' : '' }}>
                            </form>
                        </td>

                        {{-- Cột 5: Button Lưu (Nằm ngoài form, dùng JS để submit form bên cạnh) --}}
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" 
                                    onclick="document.getElementById('form-score-{{ $reg->id }}').submit()"
                                    {{ !$reg->student ? 'disabled' : '' }}>
                                <i class="fas fa-save me-1"></i> Lưu
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fas fa-user-slash fa-2x mb-3 opacity-25"></i><br>
                            Lớp này chưa có sinh viên nào đăng ký.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    tr[id] { scroll-margin-top: 150px; }
    .student-row { transition: background-color 0.3s ease; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection