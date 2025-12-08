@extends('layouts.portal')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-success">
                <i class="fas fa-tasks me-2"></i>Lớp học đã đăng ký
            </h5>
        </div>

        {{-- Thông báo Flash Message (Hiển thị kết quả sau khi hủy) --}}
        @if(session('success') || session('error'))
            <div class="p-3 bg-light border-bottom">
                @if(session('success'))
                    <div class="alert alert-success shadow-sm border-0 rounded-3 mb-0">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-0 mt-2">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase border-bottom">
                    <tr>
                        <th class="ps-4 py-3">Lớp học phần</th>
                        <th class="py-3">Giáo viên</th>
                        <th class="text-center bg-primary bg-opacity-10 text-primary py-3">Điểm số</th>
                        <th class="text-center py-3">Kết quả</th>
                        <th class="text-center py-3">Ngày bắt đầu</th>
                        <th class="text-end pe-4 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($registrations as $reg)
                    <tr>
                        {{-- 1. Tên Lớp & Modal Chi tiết --}}
                        <td class="ps-4 py-3">
                            <a href="#" class="fw-bold text-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#classModal-{{ $reg->id }}">
                                {{ $reg->classroom->name }} <i class="fas fa-info-circle small text-muted ms-1"></i>
                            </a>
                            <div class="small text-muted">{{ $reg->classroom->code ?? '' }}</div>

                            {{-- MODAL CHI TIẾT LỚP HỌC --}}
                            <div class="modal fade" id="classModal-{{ $reg->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title fw-bold">Chi tiết Lớp học</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-2"><strong><i class="fas fa-calendar-alt me-2 text-muted"></i>Lịch học:</strong> <span class="text-danger fw-bold">{{ $reg->classroom->schedule ?? 'Chưa có' }}</span></div>
                                            <div class="mb-2"><strong><i class="fas fa-map-marker-alt me-2 text-muted"></i>Phòng:</strong> <span class="badge bg-light text-dark border">{{ $reg->classroom->room ?? 'Chưa xếp' }}</span></div>
                                            <div><strong><i class="fas fa-clock me-2 text-muted"></i>Ngày bắt đầu:</strong> {{ $reg->classroom->start_date ? \Carbon\Carbon::parse($reg->classroom->start_date)->format('d/m/Y') : '---' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Giáo viên --}}
                        <td>
                            <span class="text-muted fw-bold">
                                {{ $reg->classroom->teacher->name ?? 'Chưa gán' }}
                            </span>
                        </td>
                        
                        {{-- 3. Điểm số --}}
                        <td class="text-center bg-light">
                            @if($reg->score !== null)
                                <span class="fw-bold fs-5 text-primary">{{ $reg->score }}</span>
                            @else
                                <span class="text-muted small fst-italic">-- Chưa chấm --</span>
                            @endif
                        </td>

                        {{-- 4. Kết quả --}}
                        <td class="text-center">
                            @if($reg->score === null)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Đang học</span>
                            @elseif($reg->score >= 5.0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">ĐẠT</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">HỌC LẠI</span>
                            @endif
                        </td>

                        {{-- 5. Ngày bắt đầu --}}
                        <td class="text-center text-muted small">
                            {{ $reg->classroom->start_date ? \Carbon\Carbon::parse($reg->classroom->start_date)->format('d/m/Y') : '---' }}
                        </td>

                        {{-- 6. HÀNH ĐỘNG (Xử lý Modal Hủy) --}}
                        <td class="text-end pe-4">
                            @if($reg->score !== null)
                                {{-- Có điểm -> Khóa --}}
                                <button class="btn btn-light btn-sm text-muted rounded-pill px-3" disabled>
                                    <i class="fas fa-lock me-1"></i> Đã chốt
                                </button>
                            @else
                                {{-- LOGIC KIỂM TRA HẠN HỦY (3 NGÀY) --}}
                                @php
                                    $canCancel = true; 
                                    $reason = '';

                                    if ($reg->classroom->start_date) {
                                        $startDate = \Carbon\Carbon::parse($reg->classroom->start_date);
                                        // Deadline = Ngày học trừ đi 3 ngày
                                        $deadline = $startDate->copy()->subDays(3)->endOfDay();
                                        
                                        if (now()->greaterThan($deadline)) {
                                            $canCancel = false;
                                            $reason = 'Đã quá hạn hủy (Quy định trước 3 ngày)';
                                        }
                                    }
                                @endphp

                                @if($canCancel)
                                    {{-- Thay vì submit form ngay, ta mở Modal xác nhận --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 hover-scale" 
                                            data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $reg->id }}">
                                        <i class="fas fa-times me-1"></i> Hủy
                                    </button>

                                    {{-- === MODAL XÁC NHẬN HỦY (Thay cho Javascript confirm) === --}}
                                    <div class="modal fade" id="cancelModal-{{ $reg->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-body text-center p-4">
                                                    <div class="text-danger mb-3">
                                                        <i class="fas fa-exclamation-circle fa-3x"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-2">Xác nhận Hủy?</h5>
                                                    <p class="text-muted small mb-4">
                                                        Bạn có chắc chắn muốn hủy đăng ký lớp <strong>{{ $reg->classroom->name }}</strong>?<br>
                                                        Hành động này sẽ nhường slot cho người khác.
                                                    </p>
                                                    
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Không</button>
                                                        
                                                        {{-- Form thực sự gửi lệnh hủy --}}
                                                        <form action="{{ route('student.cancel', $reg->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Có, Hủy ngay</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- === END MODAL HỦY === --}}

                                @else
                                    {{-- Quá hạn --}}
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{ $reason }}">
                                        <button class="btn btn-secondary btn-sm rounded-pill px-3 opacity-75" disabled>
                                            <i class="fas fa-ban me-1"></i> Chốt sổ
                                        </button>
                                    </span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-2x mb-3 opacity-25"></i><br>
                            Bạn chưa đăng ký lớp học nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($registrations->hasPages())
            <div class="card-footer bg-white">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.05); transition: 0.2s; }
</style>

{{-- Kích hoạt tooltip --}}
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection