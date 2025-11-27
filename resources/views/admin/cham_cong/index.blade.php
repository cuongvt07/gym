@extends('layouts.app')

@section('title', 'Quản lý Chấm công PT')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-clipboard-check me-2"></i>Chấm công PT
    </h1>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.cham-cong.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">PT</label>
                <select name="id_pt" class="form-select">
                    <option value="">Tất cả PT</option>
                    @foreach($pts as $pt)
                        <option value="{{ $pt->id }}" {{ request('id_pt') == $pt->id ? 'selected' : '' }}>
                            {{ $pt->nguoiDung->ho_ten }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tháng</label>
                <input type="month" name="month" class="form-control" value="{{ request('month', date('Y-m')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="chua_duyet" {{ request('trang_thai') == 'chua_duyet' ? 'selected' : '' }}>Chưa duyệt</option>
                    <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Card (Only if PT selected) -->
@if($summary)
<div class="card shadow-sm mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tổng hợp lương tháng {{ \Carbon\Carbon::parse(request('month'))->format('m/Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3 border-end">
                <h6 class="text-muted">Tổng giờ làm</h6>
                <h3>{{ $summary['total_hours'] }}h</h3>
            </div>
            <div class="col-md-3 border-end">
                <h6 class="text-muted">Giờ được duyệt</h6>
                <h3 class="text-success">{{ $summary['approved_hours'] }}h</h3>
            </div>
            <div class="col-md-3 border-end">
                <h6 class="text-muted">Lương cứng</h6>
                <h3>{{ number_format($summary['base_salary'], 0, ',', '.') }}</h3>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted">Tổng thực nhận</h6>
                <h3 class="text-primary fw-bold">{{ number_format($summary['total_salary'], 0, ',', '.') }} VNĐ</h3>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Timesheet List -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách chấm công ({{ $timesheets->total() }})</h6>
        @if(request('id_pt') && $timesheets->where('trang_thai', 'chua_duyet')->count() > 0)
            <form action="{{ route('admin.cham-cong.approve-all') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="id_pt" value="{{ request('id_pt') }}">
                <input type="hidden" name="month" value="{{ request('month') }}">
                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Duyệt tất cả các mục chưa duyệt?')">
                    <i class="bi bi-check-all me-1"></i>Duyệt tất cả
                </button>
            </form>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ngày</th>
                    <th>PT</th>
                    <th>Khách hàng</th>
                    <th>Thời gian</th>
                    <th>Số giờ</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($timesheets as $ts)
                    <tr>
                        <td>{{ $ts->ngay_lam->format('d/m/Y') }}</td>
                        <td>{{ $ts->pt->nguoiDung->ho_ten }}</td>
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
                        <td>
                            @if($ts->trang_thai == 'chua_duyet')
                                <form action="{{ route('admin.cham-cong.approve', $ts->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Duyệt">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted mb-0">Không có dữ liệu chấm công</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($timesheets->hasPages())
        <div class="card-footer bg-white">
            {{ $timesheets->links() }}
        </div>
    @endif
</div>
@endsection
