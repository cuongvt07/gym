@extends('layouts.app')

@section('title', 'Theo dõi chỉ số cơ thể')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-graph-up me-2"></i>Theo dõi chỉ số cơ thể
    </h1>
</div>

<!-- Customer Selector -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.chi-so.index') }}" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Chọn khách hàng</label>
                <select name="id_khach_hang" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Chọn khách hàng --</option>
                    @foreach($khachHangs as $kh)
                        <option value="{{ $kh->id }}" {{ request('id_khach_hang') == $kh->id ? 'selected' : '' }}>
                            {{ $kh->nguoiDung->ho_ten }} ({{ $kh->ma_the }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if($selectedKhachHang)
                <div class="col-md-6 text-end">
                    <a href="{{ route('admin.chi-so.create', ['id_khach_hang' => $selectedKhachHang->id]) }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Ghi nhận chỉ số mới
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

@if($selectedKhachHang)
    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Biểu đồ tiến độ</h5>
                </div>
                <div class="card-body">
                    <canvas id="metricsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Chỉ số mới nhất</h5>
                </div>
                <div class="card-body">
                    @if($latest = $history->first())
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Ngày đo</span>
                                <strong>{{ $latest->ngay_do->format('d/m/Y') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Cân nặng</span>
                                <strong>{{ $latest->can_nang }} kg</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>BMI</span>
                                <strong class="{{ $latest->bmi > 25 ? 'text-warning' : 'text-success' }}">{{ $latest->bmi }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Tỷ lệ mỡ</span>
                                <strong>{{ $latest->ty_le_mo ?? '--' }} %</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Khối lượng cơ</span>
                                <strong>{{ $latest->khoi_luong_co ?? '--' }} kg</strong>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center mt-4">Chưa có dữ liệu đo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Lịch sử đo</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ngày đo</th>
                        <th>Cân nặng</th>
                        <th>BMI</th>
                        <th>Mỡ (%)</th>
                        <th>Cơ (kg)</th>
                        <th>Các vòng (Eo/Hông/Ngực)</th>
                        <th>Người đo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $record)
                        <tr>
                            <td>{{ $record->ngay_do->format('d/m/Y') }}</td>
                            <td>{{ $record->can_nang }} kg</td>
                            <td>{{ $record->bmi }}</td>
                            <td>{{ $record->ty_le_mo ?? '-' }}</td>
                            <td>{{ $record->khoi_luong_co ?? '-' }}</td>
                            <td>
                                {{ $record->vong_eo ?? '-' }} / 
                                {{ $record->vong_hong ?? '-' }} / 
                                {{ $record->vong_nguc ?? '-' }}
                            </td>
                            <td>{{ $record->pt ? $record->pt->nguoiDung->ho_ten : 'Admin' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('metricsChart');
        const data = @json($chartData);
        
        if (data && data.dates.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [
                        {
                            label: 'Cân nặng (kg)',
                            data: data.weight,
                            borderColor: '#0d6efd',
                            tension: 0.1
                        },
                        {
                            label: 'Khối lượng cơ (kg)',
                            data: data.muscle,
                            borderColor: '#198754',
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            });
        }
    </script>
    @endpush
@endif
@endsection
