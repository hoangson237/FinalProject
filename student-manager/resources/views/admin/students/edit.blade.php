@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-warning">✏️ Cập nhật thông tin Sinh viên</h5>
        </div>
        
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại dữ liệu:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Bắt buộc để Laravel hiểu đây là lệnh Cập nhật --}}

                <div class="mb-3">
                    <label class="fw-bold">Mã Sinh viên</label>
                    <input type="text" name="code" 
                           class="form-control @error('code') is-invalid @enderror" 
                           value="{{ old('code', $student->code) }}" required>
                    
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Họ tên</label>
                    <input type="text" name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $student->name) }}" required>
                    
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Email</label>
                    <input type="email" name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $student->email) }}" required>
                    
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Ảnh đại diện</label>
                    
                    @if($student->avatar)
                        <div class="my-2">
                            <img src="{{ asset('storage/'.$student->avatar) }}" 
                                 class="rounded-circle border" 
                                 width="80" height="80" 
                                 style="object-fit: cover;">
                            <br><small class="text-muted">Ảnh hiện tại</small>
                        </div>
                    @endif

                    <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    <small class="text-muted">Bỏ qua nếu không muốn thay đổi ảnh.</small>
                    
                    @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Quay lại</a>
                    
                    <button type="submit" class="btn btn-warning fw-bold">Cập nhật thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection