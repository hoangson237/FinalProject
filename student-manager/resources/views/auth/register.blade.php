@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3 text-center rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i>Đăng ký Tài khoản Sinh viên</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end fw-bold">Họ và Tên <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nguyễn Văn A">
                                    @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="code" class="col-md-4 col-form-label text-md-end fw-bold text-primary">Mã Sinh viên <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-id-card text-primary"></i></span>
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required placeholder="VD: SV2025">
                                    @error('code') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end fw-bold">Địa chỉ Email <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@example.com">
                                    @error('email') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="0912345678">
                                    @error('phone') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end fw-bold">Địa chỉ liên hệ <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required placeholder="Số nhà, Đường, Phường, Quận...">
                                    @error('address') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 w-75 mx-auto text-muted">

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required placeholder="Bắt buộc đúng 8 ký tự"
                                           maxlength="8" minlength="8">
                                    @error('password') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                                <small class="text-muted fst-italic ms-1">Yêu cầu độ dài chính xác 8 ký tự.</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end fw-bold">Xác nhận Mật khẩu <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-check-circle text-muted"></i></span>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Nhập lại mật khẩu trên" maxlength="8">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-7 offset-md-4">
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-pill hover-scale">
                                    <i class="fas fa-user-plus me-2"></i> HOÀN TẤT ĐĂNG KÝ
                                </button>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <span class="text-muted">Đã có tài khoản?</span> 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Đăng nhập ngay</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.02); transition: 0.2s; }
    .input-group-text { width: 45px; justify-content: center; }
</style>
@endsection