@extends('layouts.app')

@section('title', 'Dashboard Học viên')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Xin chào, {{ auth()->user()->ho_ten }}!</h1>
</div>

<!-- Today's Schedule & Attendance -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Lịch tập hôm nay</h5>
            </div>
            <div class="card-body">
                @if($todaySchedule)
                    <div class="alert alert-info">
                        <strong><i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($todaySchedule->gio_bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($todaySchedule->gio_ket_thuc)->format('H:i') }}</strong>
                        <br>
                        PT hướng dẫn: <strong>{{ $todaySchedule->pt->nguoiDung->ho_ten }}</strong>
                        @if($todaySchedule->ghi_chu)
                            <br>
                            <small class="text-muted">Ghi chú: {{ $todaySchedule->ghi_chu }}</small>
                        @endif
                    </div>

                    <div class="text-center mt-4">
                        @if(!$todaySchedule->diemDanh)
                            <form action="{{ route('user.check-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow">
                                    <i class="bi bi-geo-alt me-2"></i>CHECK-IN NGAY
                                </button>
                                <p class="text-muted mt-2">Nhấn để bắt đầu buổi tập</p>
                            </form>
                        @elseif(!$todaySchedule->diemDanh->gio_check_out)
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>Bạn đã check-in lúc {{ \Carbon\Carbon::parse($todaySchedule->diemDanh->gio_check_in)->format('H:i') }}
                            </div>
                            <form action="{{ route('user.check-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg px-5 py-3 rounded-pill shadow text-white">
                                    <i class="bi bi-box-arrow-right me-2"></i>CHECK-OUT
                                </button>
                                <p class="text-muted mt-2">Nhấn để kết thúc buổi tập</p>
                            </form>
                        @else
                            <div class="alert alert-success">
                                <h4><i class="bi bi-check-circle-fill me-2"></i>Hoàn thành!</h4>
                                <p>Bạn đã hoàn thành buổi tập hôm nay.</p>
                                <hr>
                                <p class="mb-0">
                                    Check-in: {{ \Carbon\Carbon::parse($todaySchedule->diemDanh->gio_check_in)->format('H:i') }} <br>
                                    Check-out: {{ \Carbon\Carbon::parse($todaySchedule->diemDanh->gio_check_out)->format('H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">Bạn không có lịch tập nào hôm nay.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Gói tập của tôi</h5>
            </div>
            <div class="card-body">
                @forelse($packages as $pkg)
                    <div class="border rounded p-3 mb-3 {{ $pkg->buoi_con_lai <= 3 ? 'border-warning' : 'border-success' }}">
                        <h6 class="fw-bold text-primary">{{ $pkg->goiTap->ten_goi }}</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Đã tập: <strong>{{ $pkg->buoi_da_tap }}</strong></span>
                            <span>Còn lại: <strong class="{{ $pkg->buoi_con_lai <= 3 ? 'text-danger' : 'text-success' }}">{{ $pkg->buoi_con_lai }}</strong></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar {{ $pkg->buoi_con_lai <= 3 ? 'bg-warning' : 'bg-success' }}" 
                                 role="progressbar" 
                                 style="width: {{ $pkg->tien_do }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Bạn chưa đăng ký gói tập nào.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Progress Charts -->
@if(isset($chartData) && count($chartData['dates']) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Tiến độ tập luyện</h5>
            </div>
            <div class="card-body">
                <canvas id="progressChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('progressChart');
    const data = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.dates,
            datasets: [
                {
                    label: 'Cân nặng (kg)',
                    data: data.weight,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.1,
                    yAxisID: 'y'
                },
                {
                    label: 'Khối lượng cơ (kg)',
                    data: data.muscle,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.1,
                    yAxisID: 'y'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Cân nặng / Cơ (kg)'
                    }
                }
            }
        }
    });
</script>
@endpush
@endif
@endsection
