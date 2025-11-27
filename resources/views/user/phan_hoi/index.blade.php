@extends('layouts.app')

@section('title', 'Lịch sử phản hồi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lịch sử phản hồi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('user.phan-hoi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Gửi phản hồi mới
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @forelse($phanHois as $ph)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">{{ $ph->tieu_de }}</h5>
                    <span class="text-muted small">{{ $ph->ngay_gui->format('d/m/Y H:i') }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        @if($ph->loai == 'gop_y') <span class="badge bg-info">Góp ý</span>
                        @elseif($ph->loai == 'khieu_nai') <span class="badge bg-danger">Khiếu nại</span>
                        @elseif($ph->loai == 'khen_ngoi') <span class="badge bg-success">Khen ngợi</span>
                        @else <span class="badge bg-secondary">Khác</span>
                        @endif

                        @if($ph->trang_thai == 'cho_xu_ly') <span class="badge bg-warning text-dark ms-2">Chờ xử lý</span>
                        @elseif($ph->trang_thai == 'dang_xu_ly') <span class="badge bg-primary ms-2">Đang xử lý</span>
                        @else <span class="badge bg-success ms-2">Đã xử lý</span>
                        @endif
                    </div>
                    
                    <p class="card-text">{{ $ph->noi_dung }}</p>

                    @if($ph->phan_hoi_lai)
                        <div class="alert alert-secondary mt-3 mb-0">
                            <strong><i class="bi bi-arrow-return-right me-2"></i>Phản hồi từ Admin:</strong>
                            <p class="mb-0 mt-1">{{ $ph->phan_hoi_lai }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <p class="text-muted">Bạn chưa gửi phản hồi nào.</p>
            </div>
        @endforelse

        {{ $phanHois->links() }}
    </div>
</div>
@endsection
