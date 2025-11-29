@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white py-3"><h5>Thêm Giáo viên mới</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Mã Giáo viên</label>
                    <input type="text" name="code" class="form-control" placeholder="VD: GV002" required>
                </div>
                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Ảnh đại diện (Avatar)</label>
                    <input type="file" name="avatar" class="form-control">
                </div>
                
                <button type="submit" class="btn btn-success w-100">Lưu thông tin</button>
            </form>
        </div>
    </div>
</div>
@endsection