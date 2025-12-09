@extends('layouts.app')

@section('title', 'Đăng ký Khách hàng mới')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-plus me-2"></i>Đăng ký Khách hàng mới
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <form action="{{ route('admin.khach-hang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ho_ten') is-invalid @enderror" 
                                   id="ho_ten" name="ho_ten" value="{{ old('ho_ten') }}" required>
                            @error('ho_ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('sdt') is-invalid @enderror" 
                                   id="sdt" name="sdt" value="{{ old('sdt') }}">
                            @error('sdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control @error('ngay_sinh') is-invalid @enderror" 
                                   id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh') }}">
                            @error('ngay_sinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label">Giới tính</label>
                            <select class="form-select @error('gioi_tinh') is-invalid @enderror" id="gioi_tinh" name="gioi_tinh">
                                <option value="">Chọn giới tính</option>
                                <option value="nam" {{ old('gioi_tinh') == 'nam' ? 'selected' : '' }}>Nam</option>
                                <option value="nu" {{ old('gioi_tinh') == 'nu' ? 'selected' : '' }}>Nữ</option>
                                <option value="khac" {{ old('gioi_tinh') == 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="thoi_han_thang" class="form-label">Thời hạn thẻ</label>
                            <select class="form-select @error('thoi_han_thang') is-invalid @enderror" id="thoi_han_thang" name="thoi_han_thang">
                                <option value="3" {{ old('thoi_han_thang', '3') == '3' ? 'selected' : '' }}>3 tháng (Mặc định)</option>
                                <option value="6" {{ old('thoi_han_thang') == '6' ? 'selected' : '' }}>6 tháng</option>
                                <option value="12" {{ old('thoi_han_thang') == '12' ? 'selected' : '' }}>12 tháng</option>
                            </select>
                            @error('thoi_han_thang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-calendar-check me-1"></i>Ngày bắt đầu = thời điểm tạo khách hàng
                            </small>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="avatar" class="form-label">Ảnh thẻ</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="avatar-preview" class="rounded mt-3" style="display:none; max-width: 200px;">
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Mã thẻ sẽ được tạo tự động sau khi đăng ký thành công.
                        Các chức năng chọn gói tập và thanh toán sẽ có trong Phase 4.
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Đăng ký
                    </button>
                    <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
