@extends('layouts.app')

@section('title', 'Bảng chấm công')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-clipboard-data me-2"></i>Bảng chấm công của tôi
    </h1>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('pt.cham-cong.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Chọn tháng</label>
                <input type="month" name="month" class="form-control" value="{{ request('month', date('Y-m')) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-eye me-1"></i>Xem
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Tổng giờ làm</h6>
                <h2 class="display-6 fw-bold">{{ $totalHours }}h</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Đã duyệt</h6>
                <h2 class="display-6 fw-bold">{{ $approvedHours }}h</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Chờ duyệt</h6>
                <h2 class="display-6 fw-bold">{{ $pendingHours }}h</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <h6 class="card-title">Lương ước tính</h6>
                <h3 class="fw-bold">{{ number_format($estimatedSalary, 0, ',', '.') }}</h3>
                <small>(Lương cứng + Giờ đã duyệt)</small>
            </div>
        </div>
    </div>
</div>

<!-- Timesheet List -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết chấm công tháng {{ \Carbon\Carbon::parse(request('month', date('Y-m')))->format('m/Y') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ngày</th>
                    <th>Khách hàng</th>
                    <th>Thời gian</th>
                    <th>Số giờ</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($timesheets as $ts)
                    <tr>
                        <td>{{ $ts->ngay_lam->format('d/m/Y') }}</td>
                        <td>{{ $ts->lichTap->khachHang->nguoiDung->ho_ten }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($ts->gio_bat_dau)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($ts->gio_ket_thuc)->format('H:i') }}
                        </td>
                        <td><strong>{{ $ts->so_gio_lam }}h</strong></td>
                        <td>
                            @if($ts->trang_thai == 'da_duyet')
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-warning text-dark">Chưa duyệt</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-0">Không có dữ liệu chấm công trong tháng này</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
