@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-success" style="font-size: 1.25rem;"><i class="fas fa-chalkboard-teacher me-2"></i>Quản lý Giáo viên</h5>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-success btn-sm shadow-sm" style="font-size: 1rem;">
            <i class="fas fa-plus-circle me-1"></i> Thêm mới
        </a>
    </div>
    
    <div class="card-body bg-light">
        <form action="" method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm mx-1">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Tìm kiếm Giáo viên..." value="{{ request('keyword') }}" style="font-size: 1rem;">
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100 fw-bold" type="submit" style="font-size: 1rem;">Tìm kiếm</button>
            </div>
            @if(request('keyword'))
                <div class="col-12 ps-3 mt-2">
                    <a href="{{ route('admin.teachers.index') }}" class="text-danger text-decoration-none" style="font-size: 0.9rem;">
                        <i class="fas fa-times-circle"></i> Xóa bộ lọc
                    </a>
                </div>
            @endif
        </form>

        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="font-size: 1rem;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div> 
        @endif

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase">
                            <th class="text-center" width="50" style="font-size: 0.9rem;">ID</th>
                            <th class="ps-4" width="90" style="font-size: 0.9rem;">Avatar</th>
                            <th style="font-size: 0.9rem;">Thông tin Giáo viên</th>
                            <th style="font-size: 0.9rem;">Liên hệ</th>
                            <th class="text-center" style="font-size: 0.9rem;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $gv)
                        <tr>
                            <td class="text-center fw-bold text-secondary" style="font-size: 1.1rem;">{{ $gv->id }}</td>
                            
                            <td class="ps-4">
                                @if($gv->avatar)
                                    <img src="{{ asset('storage/'.$gv->avatar) }}" width="50" height="50" class="rounded-circle border shadow-sm" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center shadow-sm" style="width: 50px; height: 50px; font-weight: bold; font-size: 1.4rem;">
                                        {{ substr($gv->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $gv->name }}</div>
                                <span class="badge bg-light text-dark border mt-1" style="font-size: 0.9rem;">MGV: {{ $gv->code }}</span>
                            </td>

                            <td>
                                <div class="text-dark" style="font-size: 1.1rem;">
                                    <i class="fas fa-envelope text-muted me-2"></i> {{ $gv->email }}
                                </div>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.teachers.edit', $gv->id) }}" class="btn btn-light btn-sm text-success shadow-sm border" title="Sửa">
                                    <i class="fas fa-edit fa-lg"></i> </a>
                                <form action="{{ route('admin.teachers.destroy', $gv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa giáo viên này?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-light btn-sm text-danger shadow-sm border ms-1" title="Xóa">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5" style="font-size: 1.2rem;">
                                <i class="fas fa-chalkboard-teacher fa-3x mb-3 opacity-25"></i><br>
                                Không tìm thấy giáo viên nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <div class="text-muted" style="font-size: 1rem;"> Hiển thị <strong>{{ $teachers->firstItem() }}</strong> - <strong>{{ $teachers->lastItem() }}</strong> 
                    / <strong>{{ $teachers->total() }}</strong> kết quả
                </div>
                <div>
                    {{ $teachers->appends(request()->query())->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection