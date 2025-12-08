@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0">
        {{-- Header màu vàng cam (warning) đặc trưng cho Sửa Sinh viên --}}
        <div class="card-header bg-warning text-dark py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-user-graduate me-2"></i>Cập nhật thông tin Sinh viên</h5>
        </div>
        
        <div class="card-body p-4">
            
            {{-- Hiển thị lỗi nếu có --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- 1. Mã Sinh viên --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Mã Sinh viên <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                           value="{{ old('code', $student->code) }}" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 2. Họ tên --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $student->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 3. Email --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $student->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- 4. Ảnh đại diện --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Ảnh đại diện</label>
                    
                    @if($student->avatar)
                        <div class="d-flex align-items-center mb-2 p-2 border rounded bg-light">
                            <img src="{{ asset('storage/'.$student->avatar) }}" 
                                 class="rounded-circle border shadow-sm me-3" 
                                 width="60" height="60" style="object-fit: cover;">
                            <span class="text-muted small">Ảnh hiện tại</span>
                        </div>
                    @endif

                    <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                    <div class="form-text text-muted">Bỏ qua nếu không muốn thay đổi ảnh.</div>
                    @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <hr class="my-4">

                {{-- Khu vực nút bấm --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary px-3">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    
                    {{-- Nút màu vàng cam cho đồng bộ với Header --}}
                    <button type="submit" class="btn btn-warning fw-bold px-3 shadow-sm">
                        <i class="fas fa-save me-1"></i> Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection