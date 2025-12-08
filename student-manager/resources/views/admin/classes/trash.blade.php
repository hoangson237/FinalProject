@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-trash-alt me-2"></i>Thùng rác Lớp học</h5>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success shadow-sm mb-3 border-0">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tên lớp</th>
                        <th>Ngày xóa (Giờ VN)</th>
                        <th class="text-end pe-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deletedClasses as $class)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-bold text-dark">{{ $class->name }}</span>
                            <br> 
                            {{-- Logic hiển thị tên GV nếu có --}}
                            <small class="text-muted">GV: {{ $class->teacher->name ?? '---' }}</small>
                        </td>
                        
                        {{-- SỬA LỖI GIỜ TẠI ĐÂY: setTimezone('Asia/Ho_Chi_Minh') --}}
                        <td class="text-secondary">
                            <i class="far fa-clock me-1"></i>
                            {{ $class->deleted_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i') }}
                        </td>
                        
                        <td class="text-end pe-3">
                            {{-- Nút Khôi phục (Giữ nguyên) --}}
                            <a href="{{ route('admin.classes.restore', $class->id) }}" class="btn btn-success btn-sm shadow-sm" title="Khôi phục">
                                <i class="fas fa-undo"></i>
                            </a>

                            {{-- Nút Mở Modal Xóa Vĩnh Viễn --}}
                            <button type="button" class="btn btn-danger btn-sm shadow-sm ms-1" 
                                    data-bs-toggle="modal" data-bs-target="#forceDeleteModal-{{ $class->id }}" title="Xóa vĩnh viễn">
                                <i class="fas fa-times"></i>
                            </button>

                            {{-- === MODAL XÓA VĨNH VIỄN === --}}
                            <div class="modal fade" id="forceDeleteModal-{{ $class->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body text-center p-4">
                                            <div class="text-danger mb-3"><i class="fas fa-radiation fa-3x"></i></div>
                                            <h5 class="fw-bold mb-2 text-danger">Xóa vĩnh viễn?</h5>
                                            <p class="text-muted small mb-4">
                                                Lớp <strong>{{ $class->name }}</strong> sẽ bị xóa khỏi cơ sở dữ liệu và <strong>KHÔNG THỂ</strong> khôi phục lại.
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy</button>
                                                <form action="{{ route('admin.classes.forceDelete', $class->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Xóa ngay</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- === END MODAL === --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-5">
                            <i class="fas fa-trash-restore fa-3x mb-3 opacity-25"></i><br>
                            Thùng rác trống!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $deletedClasses->links() }}
    </div>
</div>
@endsection