@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary" style="font-size: 1.25rem;">
            <i class="fas fa-user-graduate me-2"></i>Quản lý Sinh viên
        </h5>
        <div>
            <a href="{{ route('admin.students.export') }}" class="btn btn-success btn-sm shadow-sm me-1" style="font-size: 1rem;">
                <i class="fas fa-file-excel me-1"></i> Xuất Excel
            </a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm shadow-sm" style="font-size: 1rem;">
                <i class="fas fa-plus-circle me-1"></i> Thêm mới
            </a>
        </div>
    </div>
    
    <div class="card-body bg-light">
        <form action="" method="GET" class="row g-3 mb-4 p-3 bg-white rounded shadow-sm mx-1">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Tìm kiếm theo Tên, Mã SV hoặc Email..." value="{{ request('keyword') }}" style="font-size: 1rem;">
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100 fw-bold" type="submit" style="font-size: 1rem;">Tìm kiếm</button>
            </div>
            @if(request('keyword'))
                <div class="col-12 ps-3 mt-2">
                    <a href="{{ route('admin.students.index') }}" class="text-decoration-none small text-danger" style="font-size: 0.9rem;">
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
                            <th class="ps-4" width="80" style="font-size: 0.9rem;">Avatar</th>
                            <th style="font-size: 0.9rem;">Thông tin Sinh viên</th>
                            <th style="font-size: 0.9rem;">Liên hệ</th>
                            <th class="text-center" style="font-size: 0.9rem;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $sv)
                        <tr>
                            <td class="text-center fw-bold text-secondary" style="font-size: 1.1rem;">{{ $sv->id }}</td>
                            
                            <td class="ps-4">
                                @if($sv->avatar)
                                    <img src="{{ asset('storage/'.$sv->avatar) }}" width="50" height="50" class="rounded-circle border shadow-sm" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center shadow-sm" 
                                         style="width: 50px; height: 50px; font-weight: bold; font-size: 1.4rem;">
                                        {{ substr($sv->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $sv->name }}</div>
                                <span class="badge bg-light text-dark border mt-1" style="font-size: 0.9rem;">MSV: {{ $sv->code }}</span>
                            </td>

                            <td>
                                <div class="mb-1 text-dark" style="font-size: 1.1rem;">
                                    <i class="fas fa-envelope text-muted me-2"></i> {{ $sv->email }}
                                </div>
                                @if($sv->phone)
                                    <div class="text-dark" style="font-size: 1rem;">
                                        <i class="fas fa-phone text-muted me-2"></i> {{ $sv->phone }}
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.students.edit', $sv->id) }}" class="btn btn-light btn-sm text-primary shadow-sm border" title="Sửa">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('admin.students.destroy', $sv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sinh viên này?');">
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
                                <i class="fas fa-user-graduate fa-3x mb-3 opacity-25"></i><br>
                                Không tìm thấy sinh viên nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <div class="text-muted" style="font-size: 1rem;">
                    Hiển thị <strong>{{ $students->firstItem() }}</strong> - <strong>{{ $students->lastItem() }}</strong> 
                    / <strong>{{ $students->total() }}</strong> kết quả
                </div>
                <div>
                    {{ $students->appends(request()->query())->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection