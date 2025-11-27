@extends('layouts.app')

@section('title', 'Thư viện bài tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-collection-play me-2"></i>Thư viện bài tập
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.bai-tap.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Thêm bài tập
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bai-tap.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên bài tập..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="nhom_co" class="form-select">
                    <option value="">Tất cả nhóm cơ</option>
                    <option value="nguc" {{ request('nhom_co') == 'nguc' ? 'selected' : '' }}>Ngực (Chest)</option>
                    <option value="lung" {{ request('nhom_co') == 'lung' ? 'selected' : '' }}>Lưng (Back)</option>
                    <option value="chan" {{ request('nhom_co') == 'chan' ? 'selected' : '' }}>Chân (Legs)</option>
                    <option value="vai" {{ request('nhom_co') == 'vai' ? 'selected' : '' }}>Vai (Shoulders)</option>
                    <option value="tay" {{ request('nhom_co') == 'tay' ? 'selected' : '' }}>Tay (Arms)</option>
                    <option value="bung" {{ request('nhom_co') == 'bung' ? 'selected' : '' }}>Bụng (Abs)</option>
                    <option value="cardio" {{ request('nhom_co') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search me-1"></i>Tìm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Exercise Grid -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
    @forelse($baiTaps as $baiTap)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="position-relative" style="height: 200px; overflow: hidden;">
                    @if($baiTap->hinh_anh)
                        <img src="{{ asset('storage/' . $baiTap->hinh_anh) }}" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="{{ $baiTap->ten_bai_tap }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($baiTap->do_kho == 'de')
                            <span class="badge bg-success">Dễ</span>
                        @elseif($baiTap->do_kho == 'trung_binh')
                            <span class="badge bg-warning text-dark">Trung bình</span>
                        @else
                            <span class="badge bg-danger">Khó</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-truncate" title="{{ $baiTap->ten_bai_tap }}">{{ $baiTap->ten_bai_tap }}</h5>
                    <p class="card-text text-muted small mb-2">
                        <i class="bi bi-tag me-1"></i>{{ ucfirst($baiTap->nhom_co) }}
                    </p>
                    <p class="card-text small text-truncate">{{ $baiTap->mo_ta }}</p>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                    @if($baiTap->video_url)
                        <a href="{{ $baiTap->video_url }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Xem Video">
                            <i class="bi bi-youtube"></i>
                        </a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-youtube"></i></button>
                    @endif
                    
                    <div class="btn-group">
                        <a href="{{ route('admin.bai-tap.edit', $baiTap->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.bai-tap.destroy', $baiTap->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài tập này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">Chưa có bài tập nào trong thư viện.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $baiTaps->links() }}
</div>
@endsection
