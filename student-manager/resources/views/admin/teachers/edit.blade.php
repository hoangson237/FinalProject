@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white py-3">
            <h5>✏️ Cập nhật thông tin Giáo viên</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label>Mã Giáo viên</label>
                    <input type="text" name="code" class="form-control" value="{{ $teacher->code }}" required>
                </div>

                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="name" class="form-control" value="{{ $teacher->name }}" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $teacher->email }}" required>
                </div>

                <div class="mb-3">
                    <label>Ảnh đại diện</label>
                    @if($teacher->avatar)
                        <div class="my-2">
                            <img src="{{ asset('storage/'.$teacher->avatar) }}" class="rounded-circle border" width="80" height="80">
                            <br><small class="text-muted">Ảnh hiện tại</small>
                        </div>
                    @endif
                    <input type="file" name="avatar" class="form-control">
                </div>
                
                <hr>

                <button type="submit" class="btn btn-success w-100 fw-bold">Cập nhật thông tin</button>
            </form>
        </div>
    </div>
</div>
@endsection