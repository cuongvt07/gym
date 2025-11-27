@extends('layouts.app')

@section('title', 'Quản lý Phản hồi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-chat-square-text me-2"></i>Phản hồi khách hàng
    </h1>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.phan-hoi.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="loai" class="form-select">
                    <option value="">-- Tất cả loại --</option>
                    <option value="gop_y" {{ request('loai') == 'gop_y' ? 'selected' : '' }}>Góp ý</option>
                    <option value="khieu_nai" {{ request('loai') == 'khieu_nai' ? 'selected' : '' }}>Khiếu nại</option>
                    <option value="khen_ngoi" {{ request('loai') == 'khen_ngoi' ? 'selected' : '' }}>Khen ngợi</option>
                    <option value="khac" {{ request('loai') == 'khac' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="trang_thai" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="cho_xu_ly" {{ request('trang_thai') == 'cho_xu_ly' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="dang_xu_ly" {{ request('trang_thai') == 'dang_xu_ly' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="da_xu_ly" {{ request('trang_thai') == 'da_xu_ly' ? 'selected' : '' }}>Đã xử lý</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-filter me-1"></i>Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Feedback List -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ngày gửi</th>
                    <th>Người gửi</th>
                    <th>Tiêu đề</th>
                    <th>Loại</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($phanHois as $ph)
                    <tr>
                        <td>{{ $ph->ngay_gui->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ $ph->nguoiDung->ho_ten }}
                            <br><small class="text-muted">{{ $ph->nguoiDung->role }}</small>
                        </td>
                        <td>{{ $ph->tieu_de }}</td>
                        <td>
                            @if($ph->loai == 'gop_y') <span class="badge bg-info">Góp ý</span>
                            @elseif($ph->loai == 'khieu_nai') <span class="badge bg-danger">Khiếu nại</span>
                            @elseif($ph->loai == 'khen_ngoi') <span class="badge bg-success">Khen ngợi</span>
                            @else <span class="badge bg-secondary">Khác</span>
                            @endif
                        </td>
                        <td>
                            @if($ph->trang_thai == 'cho_xu_ly') <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            @elseif($ph->trang_thai == 'dang_xu_ly') <span class="badge bg-primary">Đang xử lý</span>
                            @else <span class="badge bg-success">Đã xử lý</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $ph->id }}">
                                <i class="bi bi-reply-fill"></i> Xử lý
                            </button>
                        </td>
                    </tr>

                    <!-- Reply Modal -->
                    <div class="modal fade" id="replyModal{{ $ph->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.phan-hoi.update', $ph->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Phản hồi: {{ $ph->tieu_de }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="fw-bold">Nội dung:</label>
                                            <p class="bg-light p-2 rounded">{{ $ph->noi_dung }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái xử lý</label>
                                            <select name="trang_thai" class="form-select">
                                                <option value="dang_xu_ly" {{ $ph->trang_thai == 'dang_xu_ly' ? 'selected' : '' }}>Đang xử lý</option>
                                                <option value="da_xu_ly" {{ $ph->trang_thai == 'da_xu_ly' ? 'selected' : '' }}>Đã xử lý</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phản hồi lại</label>
                                            <textarea name="phan_hoi_lai" class="form-control" rows="4" required>{{ $ph->phan_hoi_lai }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu cập nhật</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Chưa có phản hồi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $phanHois->links() }}
    </div>
</div>
@endsection
