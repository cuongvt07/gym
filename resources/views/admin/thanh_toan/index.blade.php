@extends('layouts.app')

@section('title', 'Lịch sử thanh toán')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-cash-coin me-2"></i>Lịch sử thanh toán
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.thanh-toan.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i>Thu tiền (POS)
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.thanh-toan.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Mã hóa đơn hoặc tên khách hàng..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search me-1"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Transaction List -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Ngày thanh toán</th>
                    <th>Khách hàng</th>
                    <th>Gói tập</th>
                    <th>Số tiền</th>
                    <th>Người thu</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($thanhToans as $tt)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $tt->ma_hoa_don }}</span></td>
                        <td>{{ $tt->ngay_thanh_toan->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ $tt->dangKyGoi->khachHang->nguoiDung->ho_ten }}
                            <br><small class="text-muted">{{ $tt->dangKyGoi->khachHang->ma_the }}</small>
                        </td>
                        <td>{{ $tt->dangKyGoi->goiTap->ten_goi }}</td>
                        <td class="fw-bold text-success">{{ number_format($tt->so_tien) }} đ</td>
                        <td>{{ $tt->nguoiThu->ho_ten }}</td>
                        <td>
                            <a href="{{ route('admin.thanh-toan.invoice', $tt->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="In hóa đơn">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted mb-0">Chưa có giao dịch nào.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($thanhToans->hasPages())
        <div class="card-footer bg-white">
            {{ $thanhToans->links() }}
        </div>
    @endif
</div>
@endsection
