@extends('layouts.app')

@section('title', 'Báo cáo doanh thu')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-bar-chart-line me-2"></i>Báo cáo doanh thu
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="{{ route('admin.bao-cao.index') }}" method="GET" class="d-flex align-items-center">
            <label class="me-2">Tháng:</label>
            <input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}" onchange="this.form.submit()">
        </form>
    </div>
</div>

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Tổng doanh thu</h5>
                <h2 class="display-6 fw-bold">{{ number_format($totalRevenue) }} đ</h2>
                <p class="card-text"><i class="bi bi-cash-coin me-1"></i>Tháng {{ \Carbon\Carbon::parse($month)->format('m/Y') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Gói tập đã bán</h5>
                <h2 class="display-6 fw-bold">{{ $packagesSold }}</h2>
                <p class="card-text"><i class="bi bi-cart-check me-1"></i>Giao dịch mới</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Khách hàng mới</h5>
                <h2 class="display-6 fw-bold">{{ $newCustomers }}</h2>
                <p class="card-text"><i class="bi bi-person-plus me-1"></i>Đăng ký mới</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <!-- Revenue Trend -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Biểu đồ doanh thu theo ngày</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Package Distribution -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Doanh thu theo gói tập</h5>
            </div>
            <div class="card-body">
                <canvas id="packageChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top PTs -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top 5 PT có doanh thu cao nhất</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PT</th>
                                <th>Doanh thu mang về</th>
                                <th>Tỷ trọng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPts as $index => $pt)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pt->ho_ten }}</td>
                                    <td class="fw-bold text-success">{{ number_format($pt->total) }} đ</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $totalRevenue > 0 ? ($pt->total / $totalRevenue * 100) : 0 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($chartValues),
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Package Pie Chart
    new Chart(document.getElementById('packageChart'), {
        type: 'doughnut',
        data: {
            labels: @json($packageRevenue->pluck('ten_goi')),
            datasets: [{
                data: @json($packageRevenue->pluck('total')),
                backgroundColor: [
                    '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', 
                    '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
