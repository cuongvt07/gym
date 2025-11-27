@extends('layouts.app')

@section('title', 'Quản lý Khách hàng')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-badge me-2"></i>Quản lý Khách hàng
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.khach-hang.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>Đăng ký khách hàng mới
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.khach-hang.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Trạng thái thẻ</label>
                <select name="trang_thai_the" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="hoat_dong" {{ request('trang_thai_the') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="khoa" {{ request('trang_thai_the') == 'khoa' ? 'selected' : '' }}>Khóa</option>
                    <option value="het_han" {{ request('trang_thai_the') == 'het_han' ? 'selected' : '' }}>Hết hạn</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Mã thẻ, tên, số điện thoại..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Customer List -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách Khách hàng ({{ $khachHangs->total() }})</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã thẻ</th>
                        <th>Avatar</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Trạng thái thẻ</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khachHangs as $kh)
                        <tr>
                            <td><strong class="text-primary">{{ $kh->ma_the }}</strong></td>
                            <td>
                                <img src="{{ $kh->nguoiDung->avatar ? asset('storage/' . $kh->nguoiDung->avatar) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22 viewBox=%220 0 200 200%22%3E%3Ccircle cx=%22100%22 cy=%22100%22 r=%22100%22 fill=%22%23e0e0e0%22/%3E%3Ccircle cx=%22100%22 cy=%2280%22 r=%2235%22 fill=%22%239e9e9e%22/%3E%3Cellipse cx=%22100%22 cy=%22160%22 rx=%2260%22 ry=%2240%22 fill=%22%239e9e9e%22/%3E%3C/svg%3E' }}" 
                                     alt="Avatar" 
                                     class="rounded-circle avatar-sm"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22 viewBox=%220 0 200 200%22%3E%3Ccircle cx=%22100%22 cy=%22100%22 r=%22100%22 fill=%22%23e0e0e0%22/%3E%3Ccircle cx=%22100%22 cy=%2280%22 r=%2235%22 fill=%22%239e9e9e%22/%3E%3Cellipse cx=%22100%22 cy=%22160%22 rx=%2260%22 ry=%2240%22 fill=%22%239e9e9e%22/%3E%3C/svg%3E'">
                            </td>
                            <td>{{ $kh->nguoiDung->ho_ten }}</td>
                            <td>{{ $kh->nguoiDung->email }}</td>
                            <td>{{ $kh->nguoiDung->sdt ?? '-' }}</td>
                            <td>
                                @if($kh->trang_thai_the == 'hoat_dong')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($kh->trang_thai_the == 'khoa')
                                    <span class="badge bg-danger">Khóa</span>
                                @else
                                    <span class="badge bg-secondary">Hết hạn</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.khach-hang.show', $kh->id) }}" class="btn btn-info" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.khach-hang.edit', $kh->id) }}" class="btn btn-warning" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.khach-hang.toggle-card', $kh->id) }}" method="POST" class="d-inline toggle-status-form">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $kh->trang_thai_the == 'khoa' ? 'success' : 'secondary' }}" title="{{ $kh->trang_thai_the == 'khoa' ? 'Mở thẻ' : 'Khóa thẻ' }}">
                                            <i class="bi bi-{{ $kh->trang_thai_the == 'khoa' ? 'unlock' : 'lock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Không có dữ liệu</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($khachHangs->hasPages())
        <div class="card-footer bg-white">
            {{ $khachHangs->links() }}
        </div>
    @endif
</div>
@endsection
