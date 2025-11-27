@extends('layouts.app')

@section('title', 'Sửa thông tin gói tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-box-seam me-2"></i>Sửa thông tin gói tập
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.goi-tap.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.goi-tap.update', $goiTap->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="ten_goi" class="form-label">Tên gói tập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten_goi') is-invalid @enderror" 
                               id="ten_goi" name="ten_goi" value="{{ old('ten_goi', $goiTap->ten_goi) }}" required>
                        @error('ten_goi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="so_buoi" class="form-label">Số buổi <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('so_buoi') is-invalid @enderror" 
                                   id="so_buoi" name="so_buoi" value="{{ old('so_buoi', $goiTap->so_buoi) }}" min="1" required>
                            @error('so_buoi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Số buổi tập có trong gói</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gia" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('gia') is-invalid @enderror" 
                                   id="gia" name="gia" value="{{ old('gia', $goiTap->gia) }}" min="0" required>
                            @error('gia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                  id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta', $goiTap->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai" name="trang_thai" required>
                            <option value="hoat_dong" {{ old('trang_thai', $goiTap->trang_thai) == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="tam_ngung" {{ old('trang_thai', $goiTap->trang_thai) == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Cập nhật gói tập
                    </button>
                    <a href="{{ route('admin.goi-tap.index') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
