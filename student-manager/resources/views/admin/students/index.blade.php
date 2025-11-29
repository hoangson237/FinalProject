@extends('layouts.admin')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary">👨‍🎓 Quản lý Sinh viên</h5>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>
    
    <div class="card-body">
        
        <form action="" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" 
                       placeholder="Nhập tên, mã SV hoặc email để tìm..." 
                       value="{{ request('keyword') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                
                @if(request('keyword'))
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync"></i> Đặt lại
                    </a>
                @endif
            </div>
        </form>
        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> 
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80">Ảnh</th>
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $sv)
                    <tr>
                        <td>
                            @if($sv->avatar)
                                <img src="{{ asset('storage/'.$sv->avatar) }}" width="40" height="40" class="rounded-circle object-fit-cover border">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($sv->name) }}&background=random" width="40" height="40" class="rounded-circle">
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $sv->code }}</span></td>
                        <td class="fw-bold">{{ $sv->name }}</td>
                        <td>{{ $sv->email }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.students.edit', $sv->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.students.destroy', $sv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa sinh viên này?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Không tìm thấy sinh viên nào phù hợp với từ khóa <strong>"{{ request('keyword') }}"</strong>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection