@extends('layouts.app')

@section('title', 'Cập nhật bài tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cập nhật bài tập</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.bai-tap.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.bai-tap.update', $baiTap->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="ten_bai_tap" class="form-label">Tên bài tập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten_bai_tap') is-invalid @enderror" 
                               id="ten_bai_tap" name="ten_bai_tap" value="{{ old('ten_bai_tap', $baiTap->ten_bai_tap) }}" required>
                        @error('ten_bai_tap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nhom_co" class="form-label">Nhóm cơ <span class="text-danger">*</span></label>
                            <select class="form-select @error('nhom_co') is-invalid @enderror" id="nhom_co" name="nhom_co" required>
                                <option value="">-- Chọn nhóm cơ --</option>
                                <option value="nguc" {{ old('nhom_co', $baiTap->nhom_co) == 'nguc' ? 'selected' : '' }}>Ngực (Chest)</option>
                                <option value="lung" {{ old('nhom_co', $baiTap->nhom_co) == 'lung' ? 'selected' : '' }}>Lưng (Back)</option>
                                <option value="chan" {{ old('nhom_co', $baiTap->nhom_co) == 'chan' ? 'selected' : '' }}>Chân (Legs)</option>
                                <option value="vai" {{ old('nhom_co', $baiTap->nhom_co) == 'vai' ? 'selected' : '' }}>Vai (Shoulders)</option>
                                <option value="tay" {{ old('nhom_co', $baiTap->nhom_co) == 'tay' ? 'selected' : '' }}>Tay (Arms)</option>
                                <option value="bung" {{ old('nhom_co', $baiTap->nhom_co) == 'bung' ? 'selected' : '' }}>Bụng (Abs)</option>
                                <option value="cardio" {{ old('nhom_co', $baiTap->nhom_co) == 'cardio' ? 'selected' : '' }}>Cardio</option>
                            </select>
                            @error('nhom_co')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="do_kho" class="form-label">Độ khó <span class="text-danger">*</span></label>
                            <select class="form-select @error('do_kho') is-invalid @enderror" id="do_kho" name="do_kho" required>
                                <option value="de" {{ old('do_kho', $baiTap->do_kho) == 'de' ? 'selected' : '' }}>Dễ</option>
                                <option value="trung_binh" {{ old('do_kho', $baiTap->do_kho) == 'trung_binh' ? 'selected' : '' }}>Trung bình</option>
                                <option value="kho" {{ old('do_kho', $baiTap->do_kho) == 'kho' ? 'selected' : '' }}>Khó</option>
                            </select>
                            @error('do_kho')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả / Hướng dẫn</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta', $baiTap->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="video_url" class="form-label">Link Video (YouTube)</label>
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" name="video_url" value="{{ old('video_url', $baiTap->video_url) }}" placeholder="https://youtube.com/...">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="hinh_anh" class="form-label">Hình ảnh minh họa</label>
                        <input type="file" class="form-control @error('hinh_anh') is-invalid @enderror" 
                               id="hinh_anh" name="hinh_anh" accept="image/*" onchange="previewImage(this, 'preview')">
                        @error('hinh_anh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            @if($baiTap->hinh_anh)
                                <img id="preview" src="{{ asset('storage/' . $baiTap->hinh_anh) }}" alt="Preview" style="max-width: 200px;" class="img-thumbnail">
                            @else
                                <img id="preview" src="#" alt="Preview" style="max-width: 200px; display: none;" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Cập nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
