@extends('layouts.app') 
{{-- Tạm thời dùng layout mặc định, sau này thích thì tạo layout riêng --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            @if(session('success'))
                <div class="alert alert-success fw-bold"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
            @endif

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Đăng ký Lớp học phần</h5>
                </div>

                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Lớp học</th>
                                <th>Giáo viên</th>
                                <th class="text-center">Sĩ số</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $class)
                            <tr>
                                <td class="fw-bold text-primary">{{ $class->name }}</td>
                                <td>{{ $class->teacher->name ?? '---' }}</td>
                                
                                <td class="text-center">
                                    <span class="badge {{ $class->current_quantity >= $class->max_quantity ? 'bg-danger' : 'bg-success' }}">
                                        {{ $class->current_quantity }} / {{ $class->max_quantity }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($class->current_quantity >= $class->max_quantity)
                                        <span class="text-danger fw-bold">Hết chỗ</span>
                                    @else
                                        <span class="text-success">Còn chỗ</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    @if(in_array($class->id, $my_registered_ids))
                                        <button class="btn btn-secondary btn-sm" disabled>Đã đăng ký</button>
                                    @elseif($class->current_quantity >= $class->max_quantity)
                                        <button class="btn btn-outline-danger btn-sm" disabled>Full</button>
                                    @else
                                        <form action="{{ route('student.postRegister', $class->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">
                                                Đăng ký ngay
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection