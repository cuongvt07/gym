@extends('layouts.app')

@section('title', 'Gửi phản hồi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gửi phản hồi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('user.phan-hoi.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Lịch sử phản hồi
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('user.phan-hoi.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de" value="{{ old('tieu_de') }}" required>
                        @error('tieu_de')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Loại phản hồi <span class="text-danger">*</span></label>
                        <select name="loai" class="form-select @error('loai') is-invalid @enderror" required>
                            <option value="gop_y">Góp ý</option>
                            <option value="khieu_nai">Khiếu nại</option>
                            <option value="khen_ngoi">Khen ngợi</option>
                            <option value="khac">Khác</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('noi_dung') is-invalid @enderror" name="noi_dung" rows="5" required>{{ old('noi_dung') }}</textarea>
                        @error('noi_dung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Gửi phản hồi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
