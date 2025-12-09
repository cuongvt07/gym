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
                @if($khachHang->nguoiDung->avatar)
                    <img src="{{ asset('storage/' . $khachHang->nguoiDung->avatar) }}" 
                         alt="Avatar" 
                         class="rounded-circle avatar-lg mb-3">
                @else
                    <div class="mb-3">
                        <i class="bi bi-person-circle display-1 text-secondary"></i>
                    </div>
                @endif
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
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button">
                    <i class="bi bi-calendar-check me-1"></i>Điểm danh
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button">
                    <i class="bi bi-cash-coin me-1"></i>Thanh toán
                </button>
            </li> -->
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
                <!-- Membership Card Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-credit-card me-2"></i>Th�ng tin th? th�nh vi�n
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Tr?ng th�i th?</label>
                                <p class="mb-0">
                                    @if($khachHang->trang_thai_the == 'hoat_dong')
                                        <span class="badge bg-success">Ho?t d?ng</span>
                                    @elseif($khachHang->trang_thai_the == 'khoa')
                                        <span class="badge bg-danger">Kh�a</span>
                                    @else
                                        <span class="badge bg-secondary">H?t h?n</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">H?ng th�nh vi�n</label>
                                <p class="mb-0">
                                    @if($khachHang->is_vip)
                                        <span class="badge bg-warning text-dark fs-6">
                                            <i class="bi bi-star-fill me-1"></i>VIP
                                        </span>
                                        <small class="text-muted d-block mt-1">C� g�i t?p ho?t d?ng</small>
                                    @else
                                        <span class="badge bg-light text-dark border">Thu?ng</span>
                                        <small class="text-muted d-block mt-1">Chua c� g�i t?p</small>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Ng�y b?t d?u hi?u l?c</label>
                                <p class="mb-0">
                                    @if($khachHang->ngay_bat_dau)
                                        <i class="bi bi-calendar-event me-1 text-success"></i>
                                        {{ $khachHang->ngay_bat_dau->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Th?i h?n th?</label>
                                <p class="mb-0">
                                    @if($khachHang->thoi_han_thang)
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        {{ $khachHang->thoi_han_thang }} th�ng
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Ng�y h?t h?n</label>
                                <p class="mb-0">
                                    @if($khachHang->ngay_het_han)
                                        <i class="bi bi-calendar-x me-1 text-danger"></i>
                                        {{ $khachHang->ngay_het_han->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">S? ng�y c�n l?i</label>
                                <p class="mb-0">
                                    @if($khachHang->is_expired)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>�� h?t h?n
                                        </span>
                                    @elseif($khachHang->so_ngay_con_lai !== null)
                                        @if($khachHang->so_ngay_con_lai <= 7)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split me-1"></i>{{ $khachHang->so_ngay_con_lai }} ng�y
                                            </span>
                                        @elseif($khachHang->so_ngay_con_lai <= 30)
                                            <span class="badge bg-info">
                                                <i class="bi bi-clock me-1"></i>{{ $khachHang->so_ngay_con_lai }} ng�y
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>{{ $khachHang->so_ngay_con_lai }} ng�y
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
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

