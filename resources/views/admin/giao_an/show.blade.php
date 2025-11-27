@extends('layouts.app')

@section('title', $giaoAn->ten_giao_an)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-journal-text me-2"></i>{{ $giaoAn->ten_giao_an }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.giao-an.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin giáo án</h5>
            </div>
            <div class="card-body">
                <p><strong>Mục tiêu:</strong> 
                    @switch($giaoAn->muc_tieu)
                        @case('tang_co') <span class="badge bg-primary">Tăng cơ</span> @break
                        @case('giam_mo') <span class="badge bg-warning text-dark">Giảm mỡ</span> @break
                        @case('tang_suc_manh') <span class="badge bg-danger">Tăng sức mạnh</span> @break
                        @default <span class="badge bg-secondary">Duy trì</span>
                    @endswitch
                </p>
                <p><strong>Số ngày tập:</strong> {{ $giaoAn->so_ngay }} ngày/tuần</p>
                <p><strong>Mô tả:</strong> {{ $giaoAn->mo_ta ?: 'Không có mô tả' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @foreach($schedule as $day => $exercises)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ngày {{ $day }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Bài tập</th>
                                <th>Nhóm cơ</th>
                                <th>Sets x Reps</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exercises as $detail)
                                <tr>
                                    <td>{{ $detail->thu_tu }}</td>
                                    <td>
                                        <strong>{{ $detail->baiTap->ten_bai_tap }}</strong>
                                        @if($detail->baiTap->video_url)
                                            <a href="{{ $detail->baiTap->video_url }}" target="_blank" class="text-danger ms-1"><i class="bi bi-youtube"></i></a>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ ucfirst($detail->baiTap->nhom_co) }}</small></td>
                                    <td>{{ $detail->so_hiep }} x {{ $detail->so_lan }}</td>
                                    <td><small class="text-muted">{{ $detail->ghi_chu }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
