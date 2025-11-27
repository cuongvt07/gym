@extends('layouts.app')

@section('title', 'Quản lý PT')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-people me-2"></i>Quản lý Personal Trainer
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.pt.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm PT mới
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pt.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="nghi_viec" {{ request('trang_thai') == 'nghi_viec' ? 'selected' : '' }}>Nghỉ việc</option>
                    <option value="khoa" {{ request('trang_thai') == 'khoa' ? 'selected' : '' }}>Khóa</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tên, email, số điện thoại..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- PT List -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách PT ({{ $pts->total() }})</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Chuyên môn</th>
                        <th>Lương/giờ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pts as $pt)
                        <tr>
                            <td>
                                <img src="{{ $pt->nguoiDung->avatar ? asset('storage/' . $pt->nguoiDung->avatar) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22 viewBox=%220 0 200 200%22%3E%3Ccircle cx=%22100%22 cy=%22100%22 r=%22100%22 fill=%22%23e0e0e0%22/%3E%3Ccircle cx=%22100%22 cy=%2280%22 r=%2235%22 fill=%22%239e9e9e%22/%3E%3Cellipse cx=%22100%22 cy=%22160%22 rx=%2260%22 ry=%2240%22 fill=%22%239e9e9e%22/%3E%3C/svg%3E' }}" 
                                     alt="Avatar" 
                                     class="rounded-circle avatar-sm"
                                     onerror="this.onerror=null">
                            </td>
                            <td>
                                <strong>{{ $pt->nguoiDung->ho_ten }}</strong>
                            </td>
                            <td>{{ $pt->nguoiDung->email }}</td>
                            <td>{{ $pt->nguoiDung->sdt ?? '-' }}</td>
                            <td>
                                @if($pt->chuyen_mon)
                                    @foreach((array)$pt->chuyen_mon as $cm)
                                        <span class="badge bg-secondary">{{ $cm }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ number_format($pt->luong_gio, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @if($pt->trang_thai == 'hoat_dong')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($pt->trang_thai == 'khoa')
                                    <span class="badge bg-danger">Khóa</span>
                                @else
                                    <span class="badge bg-secondary">Nghỉ việc</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.pt.show', $pt->id) }}" class="btn btn-info" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pt.edit', $pt->id) }}" class="btn btn-warning" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.pt.toggle-status', $pt->id) }}" method="POST" class="d-inline toggle-status-form">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $pt->trang_thai == 'khoa' ? 'success' : 'secondary' }}" title="{{ $pt->trang_thai == 'khoa' ?'Mở khóa' : 'Khóa' }}">
                                            <i class="bi bi-{{ $pt->trang_thai == 'khoa' ? 'unlock' : 'lock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Không có dữ liệu</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pts->hasPages())
        <div class="card-footer bg-white">
            {{ $pts->links() }}
        </div>
    @endif
</div>
@endsection
