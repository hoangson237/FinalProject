@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Chấm điểm Lớp: <span class="text-success fw-bold">{{ $class->name }}</span></h4>
        <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-secondary"><i class="fas fa-chevron-left me-1"></i> Quay lại</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card border-0 shadow">
        <div class="card-body">
            @if($class->registrations->isEmpty())
                <p class="text-center text-muted my-3">Chưa có sinh viên nào đăng ký lớp này.</p>
            @else
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="120">Mã SV</th>
                            <th>Họ tên Sinh viên</th>
                            <th width="150">Điểm số (0-10)</th>
                            <th width="100">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->registrations as $reg)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $reg->student->code }}</span></td>
                            <td class="fw-bold">{{ $reg->student->name }}</td>
                            
                            <form action="{{ route('teacher.score.update', $reg->id) }}" method="POST">
                                @csrf
                                <td>
                                    <input type="number" name="score" step="0.1" min="0" max="10" 
                                           class="form-control text-center @error('score') is-invalid @enderror" 
                                           value="{{ old('score', $reg->score) }}" placeholder="0.0 - 10.0" required>
                                    
                                    @error('score')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-success btn-sm">Lưu</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection