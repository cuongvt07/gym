@extends('layouts.app')

@section('title', 'PT Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h1 class="h2">
        <i class="bi bi-speedometer2 me-2"></i>PT Dashboard
    </h1>
    <p class="text-muted">Chào mừng, {{ auth()->user()->ho_ten }}!</p>
    
    <div class="row">
        <!-- My Clients Widget -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Học viên của tôi</h5>
                </div>
                <div class="list-group list-group-flush">
                    @php
                        $myClients = \App\Models\DangKyGoi::where('id_pt', auth()->user()->pt->id)
                            ->hoatDong()
                            ->with('khachHang.nguoiDung')
                            ->get()
                            ->unique('id_khach_hang')
                            ->take(5);
                    @endphp
                    @forelse($myClients as $client)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $client->khachHang->nguoiDung->ho_ten }}</h6>
                                <small class="text-muted">{{ $client->khachHang->ma_the }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                {{ $client->khachHang->dangKyGoi->where('id_pt', auth()->user()->pt->id)->sum('buoi_con_lai') }} buổi
                            </span>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">Chưa có học viên nào.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Phase 2 - 5:</strong> Chức năng PT Dashboard sẽ được triển khai trong các phase tiếp theo.
    </div>
</div>
@endsection
