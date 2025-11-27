@extends('layouts.app')

@section('title', 'Thu tiền (POS)')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Thu tiền (POS)</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.thanh-toan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Lịch sử
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Thanh toán tiền mặt</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.thanh-toan.store') }}" method="POST" onsubmit="return confirm('Xác nhận thu tiền?');">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Chọn khoản thu <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_dang_ky_goi') is-invalid @enderror" name="id_dang_ky_goi" id="packageSelect" required>
                            <option value="" data-price="0">-- Chọn gói tập chưa thanh toán --</option>
                            @foreach($unpaidPackages as $pkg)
                                <option value="{{ $pkg->id }}" 
                                        data-price="{{ $pkg->goiTap->gia }}"
                                        {{ (isset($selectedPackage) && $selectedPackage->id == $pkg->id) ? 'selected' : '' }}>
                                    {{ $pkg->khachHang->nguoiDung->ho_ten }} - {{ $pkg->goiTap->ten_goi }} ({{ number_format($pkg->goiTap->gia) }} đ)
                                </option>
                            @endforeach
                        </select>
                        @error('id_dang_ky_goi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Số tiền phải thu</h6>
                            <h2 class="text-success fw-bold mb-0" id="displayPrice">0 đ</h2>
                            <input type="hidden" name="so_tien" id="inputPrice" value="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="ghi_chu" rows="2"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>XÁC NHẬN THANH TOÁN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('packageSelect');
        const display = document.getElementById('displayPrice');
        const input = document.getElementById('inputPrice');

        function updatePrice() {
            const option = select.options[select.selectedIndex];
            const price = option.getAttribute('data-price') || 0;
            
            display.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            input.value = price;
        }

        select.addEventListener('change', updatePrice);
        
        // Initial update
        if (select.value) {
            updatePrice();
        }
    });
</script>
@endsection
