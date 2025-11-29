@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-warning">✏️ Cập nhật thông tin Sinh viên</h5>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Bắt buộc để nhận route PUT --}}

                <div class="mb-3">
                    <label class="fw-bold">Mã Sinh viên</label>
                    <input type="text" name="code" class="form-control" value="{{ $student->code }}" required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Họ tên</label>
                    <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Ảnh đại diện</label>
                    
                    @if($student->avatar)
                        <div class="my-2">
                            <img src="{{ asset('storage/'.$student->avatar) }}" class="rounded-circle border" width="80" height="80">
                            <br><small class="text-muted">Ảnh hiện tại</small>
                        </div>
                    @endif

                    <input type="file" name="avatar" class="form-control">
                    <small class="text-muted">Bỏ qua nếu không muốn thay đổi ảnh.</small>
                </div>
                
                <hr>

                <button type="submit" class="btn btn-warning w-100 fw-bold">Cập nhật thông tin</button>
            </form>
        </div>
    </div>
</div>
@endsection