@extends('layouts.portal')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-success">
                <i class="fas fa-tasks me-2"></i>Lớp học đã đăng ký
            </h5>
        </div>

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
                        <th class="text-end pe-4 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($registrations as $reg)
                    <tr>
                        <td class="ps-4 py-3">
                            <span class="fw-bold text-dark">{{ $reg->classroom->name }}</span>
                        </td>
                        <td>
                            <span class="text-muted fw-bold">
                                {{ $reg->classroom->teacher->name ?? 'Chưa gán' }}
                            </span>
                        </td>
                        
                        <td class="text-center bg-light">
                            @if($reg->score !== null)
                                <span class="fw-bold fs-5 text-primary">{{ $reg->score }}</span>
                            @else
                                <span class="text-muted small fst-italic">-- Chưa chấm --</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($reg->score === null)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Đang học</span>
                            @elseif($reg->score >= 5.0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">ĐẠT</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">HỌC LẠI</span>
                            @endif
                        </td>

                        <td class="text-end pe-4">
                            @if($reg->score === null)
                                <form action="{{ route('student.cancel', $reg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn HỦY lớp này? Slot sẽ được nhường cho người khác.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 hover-scale">
                                        <i class="fas fa-times me-1"></i> Hủy
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-light btn-sm text-muted rounded-pill px-3" disabled>
                                    <i class="fas fa-lock me-1"></i> Đã chốt
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-2x mb-3 opacity-25"></i><br>
                            Bạn chưa đăng ký lớp học nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.05); transition: 0.2s; }
</style>
@endsection