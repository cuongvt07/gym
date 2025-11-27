@extends('layouts.app')

@section('title', 'Chi tiết Khách hàng')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-badge me-2"></i>Chi tiết Khách hàng
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.khach-hang.edit', $khachHang->id) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-1"></i>Sửa thông tin
        </a>
        <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <img src="{{ $khachHang->nguoiDung->avatar ? asset('storage/' . $khachHang->nguoiDung->avatar) : asset('images/default-avatar.png') }}" 
                     alt="Avatar" 
                     class="rounded-circle avatar-lg mb-3"
                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                <h4>{{ $khachHang->nguoiDung->ho_ten }}</h4>
                <p class="text-muted mb-2">Khách hàng</p>
                
                <div class="mb-3">
                    <h5 class="text-primary">{{ $khachHang->ma_the }}</h5>
                    <small class="text-muted">Mã thẻ</small>
                </div>
                
                @if($khachHang->trang_thai_the == 'hoat_dong')
                    <span class="badge bg-success">Thẻ hoạt động</span>
                @elseif($khachHang->trang_thai_the == 'khoa')
                    <span class="badge bg-danger">Thẻ bị khóa</span>
                @else
                    <span class="badge bg-secondary">Thẻ hết hạn</span>
                @endif
                
                <hr>
                
                <div class="text-start">
                    <p class="mb-2">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        {{ $khachHang->nguoiDung->email }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-phone me-2 text-primary"></i>
                        {{ $khachHang->nguoiDung->sdt ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-calendar me-2 text-primary"></i>
                        {{ $khachHang->nguoiDung->ngay_sinh ? $khachHang->nguoiDung->ngay_sinh->format('d/m/Y') : '-' }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-gender-ambiguous me-2 text-primary"></i>
                        {{ $khachHang->nguoiDung->gioi_tinh ? ucfirst($khachHang->nguoiDung->gioi_tinh) : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="customerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                    <i class="bi bi-info-circle me-1"></i>Thông tin
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="package-tab" data-bs-toggle="tab" data-bs-target="#package" type="button">
                    <i class="bi bi-box-seam me-1"></i>Gói tập
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button">
                    <i class="bi bi-calendar-check me-1"></i>Điểm danh
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button">
                    <i class="bi bi-cash-coin me-1"></i>Thanh toán
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="customerTabContent">
            <!-- Info Tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Thông tin cá nhân</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Họ và tên</label>
                                <p class="mb-0"><strong>{{ $khachHang->nguoiDung->ho_ten }}</strong></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0">{{ $khachHang->nguoiDung->email }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Số điện thoại</label>
                                <p class="mb-0">{{ $khachHang->nguoiDung->sdt ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Ngày sinh</label>
                                <p class="mb-0">{{ $khachHang->nguoiDung->ngay_sinh ? $khachHang->nguoiDung->ngay_sinh->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Giới tính</label>
                                <p class="mb-0">{{ $khachHang->nguoiDung->gioi_tinh ? ucfirst($khachHang->nguoiDung->gioi_tinh) : '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Mã thẻ</label>
                                <p class="mb-0"><strong class="text-primary">{{ $khachHang->ma_the }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Package Tab -->
            <div class="tab-pane fade" id="package" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Các gói tập đã đăng ký</h5>
                            <a href="{{ route('admin.khach-hang.assign-package', $khachHang->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Đăng ký gói mới
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Gói tập</th>
                                        <th>PT hướng dẫn</th>
                                        <th>Ngày đăng ký</th>
                                        <th>Tiến độ</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($khachHang->dangKyGoi as $dkg)
                                        <tr>
                                            <td>
                                                <strong>{{ $dkg->goiTap->ten_goi }}</strong><br>
                                                <small class="text-muted">{{ $dkg->tong_buoi }} buổi</small>
                                            </td>
                                            <td>{{ $dkg->pt->nguoiDung->ho_ten }}</td>
                                            <td>{{ $dkg->ngay_dang_ky->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                        <div class="progress-bar" role="progressbar" 
                                                             style="width: {{ $dkg->tien_do }}%" 
                                                             aria-valuenow="{{ $dkg->tien_do }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <small>{{ $dkg->buoi_da_tap }}/{{ $dkg->tong_buoi }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($dkg->trang_thai == 'hoat_dong')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @elseif($dkg->trang_thai == 'het_han')
                                                    <span class="badge bg-secondary">Hết hạn</span>
                                                @else
                                                    <span class="badge bg-danger">Hủy</span>
                                                @endif
                                                <br>
                                                <small class="{{ $dkg->trang_thai_thanh_toan == 'da_thanh_toan' ? 'text-success' : 'text-warning' }}">
                                                    {{ $dkg->trang_thai_thanh_toan == 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">Khách hàng chưa đăng ký gói tập nào</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Attendance Tab -->
            <div class="tab-pane fade" id="attendance" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Chức năng điểm danh sẽ được triển khai trong Phase 2
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Tab -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Chức năng thanh toán sẽ được triển khai trong Phase 4
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
