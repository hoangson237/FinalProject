@extends('layouts.admin')

@section('content')
<div class="container" style="max-width: 800px;"> {{-- Tăng max-width lên 800px cho thoáng --}}
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-user-graduate me-2"></i>Thêm Sinh viên mới
            </h5>
        </div>
        
        <div class="card-body p-4">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    {{-- CỘT TRÁI: NHẬP LIỆU --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="fw-bold form-label">Mã Sinh viên <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   placeholder="VD: SV001" value="{{ old('code') }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Nhập họ và tên" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="student@example.com" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- CỘT PHẢI: ẢNH ĐẠI DIỆN --}}
                    <div class="col-md-4">
                        <div class="mb-3 text-center">
                            <label class="fw-bold form-label d-block mb-2">Ảnh đại diện</label>
                            <div class="card bg-light border-0 p-3 mb-2">
                                <i class="fas fa-camera fa-3x text-secondary opacity-25"></i>
                            </div>
                            <input type="file" name="avatar" class="form-control form-control-sm @error('avatar') is-invalid @enderror">
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Sinh viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection