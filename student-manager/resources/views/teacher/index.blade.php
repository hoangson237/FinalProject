@extends('layouts.portal')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white" 
         style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Khu vực Giảng dạy</h4>
                <p class="mb-0 opacity-75">Giảng viên: {{ Auth::user()->name }}</p>
            </div>
            <i class="fas fa-user-edit fa-3x opacity-25"></i>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            
            {{-- *** ADDED: SUCCESS MESSAGE ALERT *** --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- ************************************ --}}

            <h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-success">
                &nbsp;Lớp học được phân công
            </h5>

            <div class="row g-4">
                @forelse($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                                    <i class="fas fa-book-open fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $class->name }}</h6>
                                    <small class="text-muted">Mã lớp: #{{ $class->id }}</small>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Sĩ số hiện tại</span>
                                    <span class="fw-bold text-success">{{ $class->current_quantity }} / {{ $class->max_quantity }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    @php $percent = ($class->max_quantity > 0) ? ($class->current_quantity / $class->max_quantity) * 100 : 0; @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <a href="{{ route('teacher.class.show', $class->id) }}" class="btn btn-outline-success w-100 rounded-pill fw-bold">
                                Vào chấm điểm <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-coffee fa-3x mb-3 opacity-50"></i>
                    <p>Thầy/Cô chưa được phân công lớp nào.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-5">
                <div class="d-flex justify-content-center">
                    {{ $classes->appends(request()->query())->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection