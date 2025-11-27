@extends('layouts.app')

@section('title', 'Ghi nhận chỉ số')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Ghi nhận chỉ số cơ thể</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.chi-so.index', ['id_khach_hang' => $khachHang->id]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Khách hàng: {{ $khachHang->nguoiDung->ho_ten }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.chi-so.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_khach_hang" value="{{ $khachHang->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Ngày đo <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="ngay_do" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <h6 class="mb-3 text-primary border-bottom pb-2">Chỉ số cơ bản</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cân nặng (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" class="form-control" name="can_nang" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chiều cao (cm) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" class="form-control" name="chieu_cao" required>
                        </div>
                    </div>

                    <h6 class="mb-3 text-primary border-bottom pb-2 mt-3">Thành phần cơ thể (Tùy chọn)</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tỷ lệ mỡ (%)</label>
                            <input type="number" step="0.1" class="form-control" name="ty_le_mo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Khối lượng cơ (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="khoi_luong_co">
                        </div>
                    </div>

                    <h6 class="mb-3 text-primary border-bottom pb-2 mt-3">Số đo các vòng (cm) (Tùy chọn)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vòng ngực</label>
                            <input type="number" step="0.1" class="form-control" name="vong_nguc">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vòng eo</label>
                            <input type="number" step="0.1" class="form-control" name="vong_eo">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vòng hông</label>
                            <input type="number" step="0.1" class="form-control" name="vong_hong">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vòng đùi</label>
                            <input type="number" step="0.1" class="form-control" name="vong_dui">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vòng bắp tay</label>
                            <input type="number" step="0.1" class="form-control" name="vong_baptay">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú thêm</label>
                        <textarea class="form-control" name="ghi_chu" rows="3"></textarea>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu kết quả
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
