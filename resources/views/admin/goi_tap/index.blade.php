@extends('layouts.app')

@section('title', 'Quản lý Gói tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-box-seam me-2"></i>Quản lý Gói tập
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.goi-tap.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tạo gói tập mới
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.goi-tap.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="tam_ngung" {{ request('trang_thai') == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Package List -->
<div class="row">
    @forelse($goiTaps as $goiTap)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-{{ $goiTap->trang_thai == 'hoat_dong' ? 'primary' : 'secondary' }} text-white">
                    <h5 class="mb-0">{{ $goiTap->ten_goi }}</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="text-primary mb-0">{{ $goiTap->so_buoi }}</h2>
                        <small class="text-muted">buổi tập</small>
                    </div>
                    
                    <h3 class="text-center text-success mb-3">
                        {{ number_format($goiTap->gia, 0, ',', '.') }} VNĐ
                    </h3>
                    
                    <p class="card-text text-muted">{{ $goiTap->mo_ta }}</p>
                    
                    <div class="mb-3">
                        @if($goiTap->trang_thai == 'hoat_dong')
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Tạm ngưng</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.goi-tap.edit', $goiTap->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <form action="{{ route('admin.goi-tap.toggle-status', $goiTap->id) }}" method="POST" class="flex-fill toggle-status-form">
                            @csrf
                            <button type="submit" class="btn btn-{{ $goiTap->trang_thai == 'tam_ngung' ? 'success' : 'secondary' }} btn-sm w-100">
                                <i class="bi bi-{{ $goiTap->trang_thai == 'tam_ngung' ? 'play' : 'pause' }}"></i> 
                                {{ $goiTap->trang_thai == 'tam_ngung' ? 'Kích hoạt' : 'Tạm ngưng' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">Chưa có gói tập nào</p>
                    <a href="{{ route('admin.goi-tap.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tạo gói tập đầu tiên
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($goiTaps->hasPages())
    <div class="d-flex justify-content-center">
        {{ $goiTaps->links() }}
    </div>
@endif
@endsection
