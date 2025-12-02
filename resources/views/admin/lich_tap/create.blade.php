@extends('layouts.app')

@section('title', 'Xếp lịch tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-calendar-plus me-2"></i>Xếp lịch tập mới
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.lich-tap.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.lich-tap.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="id_khach_hang" class="form-label">Khách hàng <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_khach_hang') is-invalid @enderror" id="id_khach_hang" name="id_khach_hang" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($khachHangs as $kh)
                                <option value="{{ $kh->id }}" data-pt="{{ $kh->dangKyGoi->first()->id_pt }}" {{ old('id_khach_hang') == $kh->id ? 'selected' : '' }}>
                                    {{ $kh->nguoiDung->ho_ten }} ({{ $kh->ma_the }}) - Còn {{ $kh->dangKyGoi->sum('buoi_con_lai') }} buổi
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Chỉ hiển thị khách hàng có gói tập còn hạn và còn buổi.</div>
                        @error('id_khach_hang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_pt" class="form-label">PT hướng dẫn <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_pt') is-invalid @enderror" id="id_pt_display" disabled>
                            <option value="">-- Chọn PT --</option>
                            @foreach($pts as $pt)
                                <option value="{{ $pt->id }}" {{ old('id_pt') == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->nguoiDung->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="id_pt" id="id_pt" value="{{ old('id_pt') }}">
                        <div class="form-text">PT được tự động chọn theo gói tập của khách hàng.</div>
                        @error('id_pt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="ngay_tap" class="form-label">Ngày tập <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('ngay_tap') is-invalid @enderror" 
                                   id="ngay_tap" name="ngay_tap" value="{{ old('ngay_tap') }}" required>
                            @error('ngay_tap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="gio_bat_dau" class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('gio_bat_dau') is-invalid @enderror" 
                                   id="gio_bat_dau" name="gio_bat_dau" value="{{ old('gio_bat_dau') }}" required>
                            @error('gio_bat_dau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="gio_ket_thuc" class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('gio_ket_thuc') is-invalid @enderror" 
                                   id="gio_ket_thuc" name="gio_ket_thuc" value="{{ old('gio_ket_thuc') }}" required>
                            @error('gio_ket_thuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label">Ghi chú</label>
                        <textarea class="form-control @error('ghi_chu') is-invalid @enderror" id="ghi_chu" name="ghi_chu" rows="3">{{ old('ghi_chu') }}</textarea>
                        @error('ghi_chu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Lưu lịch tập
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('id_khach_hang').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var ptId = selectedOption.getAttribute('data-pt');
        var ptDisplay = document.getElementById('id_pt_display');
        var ptInput = document.getElementById('id_pt');
        
        if (ptId) {
            ptDisplay.value = ptId;
            ptInput.value = ptId;
        } else {
            ptDisplay.value = "";
            ptInput.value = "";
        }
    });
    
    // Auto-set end time to +1 hour
    document.getElementById('gio_bat_dau').addEventListener('change', function() {
        var startTime = this.value;
        if (startTime) {
            var [hours, minutes] = startTime.split(':');
            var date = new Date();
            date.setHours(parseInt(hours) + 1);
            date.setMinutes(parseInt(minutes));
            
            var endHours = String(date.getHours()).padStart(2, '0');
            var endMinutes = String(date.getMinutes()).padStart(2, '0');
            
            document.getElementById('gio_ket_thuc').value = `${endHours}:${endMinutes}`;
        }
    });
</script>
@endsection
