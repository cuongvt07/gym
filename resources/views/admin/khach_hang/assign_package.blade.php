@extends('layouts.app')

@section('title', 'Đăng ký gói tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-cart-plus me-2"></i>Đăng ký gói tập
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.khach-hang.show', $khachHang->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Khách hàng: {{ $khachHang->nguoiDung->ho_ten }} 
                    <small>({{ $khachHang->ma_the }})</small>
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.khach-hang.store-package', $khachHang->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="id_goi_tap" class="form-label">Chọn gói tập <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_goi_tap') is-invalid @enderror" id="id_goi_tap" name="id_goi_tap" required>
                            <option value="">-- Chọn gói tập --</option>
                            @foreach($goiTaps as $goi)
                                <option value="{{ $goi->id }}" data-price="{{ $goi->gia }}" {{ old('id_goi_tap') == $goi->id ? 'selected' : '' }}>
                                    {{ $goi->ten_goi }} - {{ $goi->so_buoi }} buổi - {{ number_format($goi->gia, 0, ',', '.') }} VNĐ
                                </option>
                            @endforeach
                        </select>
                        @error('id_goi_tap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_pt" class="form-label">Chọn PT hướng dẫn <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_pt') is-invalid @enderror" id="id_pt" name="id_pt" required>
                            <option value="">-- Chọn PT --</option>
                            @foreach($pts as $pt)
                                <option value="{{ $pt->id }}" {{ old('id_pt') == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->nguoiDung->ho_ten }} - {{ implode(', ', (array)$pt->chuyen_mon) }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ngay_dang_ky" class="form-label">Ngày đăng ký <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('ngay_dang_ky') is-invalid @enderror" 
                                   id="ngay_dang_ky" name="ngay_dang_ky" value="{{ old('ngay_dang_ky', date('Y-m-d')) }}" required>
                            @error('ngay_dang_ky')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="trang_thai_thanh_toan" class="form-label">Trạng thái thanh toán <span class="text-danger">*</span></label>
                            <select class="form-select @error('trang_thai_thanh_toan') is-invalid @enderror" id="trang_thai_thanh_toan" name="trang_thai_thanh_toan" required>
                                <option value="chua_thanh_toan" {{ old('trang_thai_thanh_toan') == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="da_thanh_toan" {{ old('trang_thai_thanh_toan') == 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                            </select>
                            @error('trang_thai_thanh_toan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info" id="price-alert" style="display: none;">
                        <strong>Thành tiền: </strong> <span id="price-display">0</span> VNĐ
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Xác nhận đăng ký
                    </button>
                    <a href="{{ route('admin.khach-hang.show', $khachHang->id) }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('id_goi_tap').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        var display = document.getElementById('price-display');
        var alert = document.getElementById('price-alert');
        
        if (price) {
            display.textContent = new Intl.NumberFormat('vi-VN').format(price);
            alert.style.display = 'block';
        } else {
            alert.style.display = 'none';
        }
    });
</script>
@endsection
