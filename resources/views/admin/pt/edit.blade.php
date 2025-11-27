@extends('layouts.app')

@section('title', 'Sửa thông tin PT')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil me-2"></i>Sửa thông tin Personal Trainer
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.pt.show', $pt->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <form action="{{ route('admin.pt.update', $pt->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Thông tin cá nhân -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ho_ten') is-invalid @enderror" 
                                   id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $pt->nguoiDung->ho_ten) }}" required>
                            @error('ho_ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $pt->nguoiDung->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mật khẩu mới <small class="text-muted">(để trống nếu không đổi)</small></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('sdt') is-invalid @enderror" 
                                   id="sdt" name="sdt" value="{{ old('sdt', $pt->nguoiDung->sdt) }}">
                            @error('sdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control @error('ngay_sinh') is-invalid @enderror" 
                                   id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh', $pt->nguoiDung->ngay_sinh?->format('Y-m-d')) }}">
                            @error('ngay_sinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label">Giới tính</label>
                            <select class="form-select @error('gioi_tinh') is-invalid @enderror" id="gioi_tinh" name="gioi_tinh">
                                <option value="">Chọn giới tính</option>
                                <option value="nam" {{ old('gioi_tinh', $pt->nguoiDung->gioi_tinh) == 'nam' ? 'selected' : '' }}>Nam</option>
                                <option value="nu" {{ old('gioi_tinh', $pt->nguoiDung->gioi_tinh) == 'nu' ? 'selected' : '' }}>Nữ</option>
                                <option value="khac" {{ old('gioi_tinh', $pt->nguoiDung->gioi_tinh) == 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            @if($pt->nguoiDung->avatar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $pt->nguoiDung->avatar) }}" class="rounded" style="max-width: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="avatar-preview" class="rounded mt-3" style="display:none; max-width: 200px;">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin công việc -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Thông tin công việc</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="luong_co_ban" class="form-label">Lương cơ bản (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('luong_co_ban') is-invalid @enderror" 
                                   id="luong_co_ban" name="luong_co_ban" value="{{ old('luong_co_ban', $pt->luong_co_ban) }}" min="0" required>
                            @error('luong_co_ban')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="luong_gio" class="form-label">Lương/giờ (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('luong_gio') is-invalid @enderror" 
                                   id="luong_gio" name="luong_gio" value="{{ old('luong_gio', $pt->luong_gio) }}" min="0" required>
                            @error('luong_gio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="so_gio_tieu_chuan" class="form-label">Số giờ tiêu chuẩn/tháng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('so_gio_tieu_chuan') is-invalid @enderror" 
                                   id="so_gio_tieu_chuan" name="so_gio_tieu_chuan" value="{{ old('so_gio_tieu_chuan', $pt->so_gio_tieu_chuan) }}" min="0" required>
                            @error('so_gio_tieu_chuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin chuyên môn -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-award me-2"></i>Thông tin chuyên môn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kinh_nghiem" class="form-label">Kinh nghiệm</label>
                            <textarea class="form-control @error('kinh_nghiem') is-invalid @enderror" 
                                      id="kinh_nghiem" name="kinh_nghiem" rows="3">{{ old('kinh_nghiem', $pt->kinh_nghiem) }}</textarea>
                            @error('kinh_nghiem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="chung_chi" class="form-label">Chứng chỉ</label>
                            <textarea class="form-control @error('chung_chi') is-invalid @enderror" 
                                      id="chung_chi" name="chung_chi" rows="3">{{ old('chung_chi', $pt->chung_chi) }}</textarea>
                            @error('chung_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Chuyên môn</label>
                            <div class="row">
                                @php
                                    $currentCM = old('chuyen_mon', (array)$pt->chuyen_mon);
                                @endphp
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="chuyen_mon[]" value="Gym" id="cm_gym" 
                                               {{ in_array('Gym', $currentCM) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cm_gym">Gym</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="chuyen_mon[]" value="Yoga" id="cm_yoga"
                                               {{ in_array('Yoga', $currentCM) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cm_yoga">Yoga</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="chuyen_mon[]" value="Boxing" id="cm_boxing"
                                               {{ in_array('Boxing', $currentCM) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cm_boxing">Boxing</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="chuyen_mon[]" value="Cardio" id="cm_cardio"
                                               {{ in_array('Cardio', $currentCM) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cm_cardio">Cardio</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Cập nhật thông tin
                    </button>
                    <a href="{{ route('admin.pt.show', $pt->id) }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
