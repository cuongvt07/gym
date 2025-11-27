@extends('layouts.app')

@section('title', 'Giáo án tập luyện')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-journal-text me-2"></i>Giáo án tập luyện
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.giao-an.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tạo giáo án mới
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tên giáo án</th>
                    <th>Mục tiêu</th>
                    <th>Số ngày/tuần</th>
                    <th>Số bài tập</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($giaoAns as $ga)
                    <tr>
                        <td>
                            <a href="{{ route('admin.giao-an.show', $ga->id) }}" class="fw-bold text-decoration-none">
                                {{ $ga->ten_giao_an }}
                            </a>
                            @if($ga->mo_ta)
                                <br><small class="text-muted">{{ Str::limit($ga->mo_ta, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            @switch($ga->muc_tieu)
                                @case('tang_co') <span class="badge bg-primary">Tăng cơ</span> @break
                                @case('giam_mo') <span class="badge bg-warning text-dark">Giảm mỡ</span> @break
                                @case('tang_suc_manh') <span class="badge bg-danger">Tăng sức mạnh</span> @break
                                @default <span class="badge bg-secondary">Duy trì</span>
                            @endswitch
                        </td>
                        <td>{{ $ga->so_ngay }} ngày</td>
                        <td>{{ $ga->chi_tiet_count }} bài</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.giao-an.show', $ga->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.giao-an.destroy', $ga->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa giáo án này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-0">Chưa có giáo án nào.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($giaoAns->hasPages())
        <div class="card-footer bg-white">
            {{ $giaoAns->links() }}
        </div>
    @endif
</div>
@endsection
