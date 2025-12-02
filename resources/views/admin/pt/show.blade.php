@extends('layouts.app')

@section('title', 'Chi tiết PT')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-circle me-2"></i>Chi tiết Personal Trainer
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.pt.edit', $pt->id) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-1"></i>Sửa thông tin
        </a>
        <a href="{{ route('admin.pt.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                @if($pt->nguoiDung->avatar)
                    <img src="{{ asset('storage/' . $pt->nguoiDung->avatar) }}" 
                         alt="Avatar" 
                         class="rounded-circle avatar-lg mb-3">
                @else
                    <div class="mb-3">
                        <i class="bi bi-person-circle display-1 text-secondary"></i>
                    </div>
                @endif
                <h4>{{ $pt->nguoiDung->ho_ten }}</h4>
                <p class="text-muted mb-2">Personal Trainer</p>
                
                @if($pt->trang_thai == 'hoat_dong')
                    <span class="badge bg-success">Hoạt động</span>
                @elseif($pt->trang_thai == 'khoa')
                    <span class="badge bg-danger">Khóa</span>
                @else
                    <span class="badge bg-secondary">Nghỉ việc</span>
                @endif
                
                <hr>
                
                <div class="text-start">
                    <p class="mb-2">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        {{ $pt->nguoiDung->email }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-phone me-2 text-primary"></i>
                        {{ $pt->nguoiDung->sdt ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-calendar me-2 text-primary"></i>
                        {{ $pt->nguoiDung->ngay_sinh ? $pt->nguoiDung->ngay_sinh->format('d/m/Y') : '-' }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-gender-ambiguous me-2 text-primary"></i>
                        {{ $pt->nguoiDung->gioi_tinh ? ucfirst($pt->nguoiDung->gioi_tinh) : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="ptTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="work-tab" data-bs-toggle="tab" data-bs-target="#work" type="button">
                    <i class="bi bi-briefcase me-1"></i>Thông tin công việc
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button">
                    <i class="bi bi-award me-1"></i>Chuyên môn
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button">
                    <i class="bi bi-calendar-week me-1"></i>Lịch dạy
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="ptTabContent">
            <!-- Work Info Tab -->
            <div class="tab-pane fade show active" id="work" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Lương cơ bản</label>
                                <h5>{{ number_format($pt->luong_co_ban, 0, ',', '.') }} VNĐ</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Lương/giờ</label>
                                <h5>{{ number_format($pt->luong_gio, 0, ',', '.') }} VNĐ</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Số giờ tiêu chuẩn/tháng</label>
                                <h5>{{ $pt->so_gio_tieu_chuan }} giờ</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Lương dự kiến/tháng</label>
                                <h5 class="text-success">
                                    {{ number_format(($pt->so_gio_tieu_chuan * $pt->luong_gio) + $pt->luong_co_ban, 0, ',', '.') }} VNĐ
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Professional Tab -->
            <div class="tab-pane fade" id="professional" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="text-muted">Chuyên môn</label>
                            <div class="mt-2">
                                @if($pt->chuyen_mon)
                                    @foreach((array)$pt->chuyen_mon as $cm)
                                        <span class="badge bg-primary me-1 mb-1">{{ $cm }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted">Chưa cập nhật</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="text-muted">Kinh nghiệm</label>
                            <p class="mt-2">{{ $pt->kinh_nghiem ?? 'Chưa cập nhật' }}</p>
                        </div>
                        
                        <div class="mb-0">
                            <label class="text-muted">Chứng chỉ</label>
                            <p class="mt-2">{{ $pt->chung_chi ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Schedule Tab -->
            <div class="tab-pane fade" id="schedule" role="tabpanel">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Chức năng lịch dạy sẽ được triển khai trong Phase 2
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
