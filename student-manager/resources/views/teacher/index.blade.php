@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4"><i class="fas fa-user-circle text-primary me-2"></i> Lớp học được phân công</h3>
    <hr>
    <div class="row">
        @forelse($classes as $class)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">{{ $class->name }}</h5>
                    <p class="card-text text-muted">Mã lớp: CL{{ $class->id }}</p>
                    <p class="card-text">
                        <span class="badge bg-info me-2">Sĩ số: {{ $class->current_quantity }} / {{ $class->max_quantity }}</span>
                    </p>
                    <a href="{{ route('teacher.class.show', $class->id) }}" class="btn btn-outline-success w-100 mt-2">
                        Vào chấm điểm <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Bạn chưa được phân công giảng dạy lớp nào.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection