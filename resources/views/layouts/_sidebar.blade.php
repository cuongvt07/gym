<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        @if(auth()->user()->isAdmin())
            <!-- Admin Menu -->
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>QUẢN TRỊ</span>
            </h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.pt.*') ? 'active' : '' }}" href="{{ route('admin.pt.index') }}">
                        <i class="bi bi-people me-2"></i>Quản lý PT
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.khach-hang.*') ? 'active' : '' }}" href="{{ route('admin.khach-hang.index') }}">
                        <i class="bi bi-person-badge me-2"></i>Quản lý Khách hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.goi-tap.*') ? 'active' : '' }}" href="{{ route('admin.goi-tap.index') }}">
                        <i class="bi bi-box-seam me-2"></i>Quản lý Gói tập
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.lich-tap.*') ? 'active' : '' }}" href="{{ route('admin.lich-tap.index') }}">
                        <i class="bi bi-calendar-week me-2"></i>Quản lý Lịch tập
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.cham-cong.*') ? 'active' : '' }}" href="{{ route('admin.cham-cong.index') }}">
                        <i class="bi bi-clipboard-check me-2"></i>Chấm công PT
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bai-tap.*') ? 'active' : '' }}" href="{{ route('admin.bai-tap.index') }}">
                        <i class="bi bi-collection-play me-2"></i>Thư viện bài tập
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.giao-an.*') ? 'active' : '' }}" href="{{ route('admin.giao-an.index') }}">
                        <i class="bi bi-journal-text me-2"></i>Giáo án tập luyện
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.chi-so.*') ? 'active' : '' }}" href="{{ route('admin.chi-so.index') }}">
                        <i class="bi bi-graph-up me-2"></i>Theo dõi chỉ số
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.thanh-toan.*') ? 'active' : '' }}" href="{{ route('admin.thanh-toan.index') }}">
                        <i class="bi bi-cash-coin me-2"></i>Thanh toán
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bao-cao.*') ? 'active' : '' }}" href="{{ route('admin.bao-cao.index') }}">
                        <i class="bi bi-bar-chart-line me-2"></i>Báo cáo doanh thu
                        <i class="bi bi-people me-2"></i>Học viên <small class="badge bg-secondary">Phase 3</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">
                        <i class="bi bi-clipboard-data me-2"></i>Thư viện bài tập <small class="badge bg-secondary">Phase 3</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">
                        <i class="bi bi-journal-text me-2"></i>Giáo án <small class="badge bg-secondary">Phase 3</small>
                    </a>
                </li>
            </ul>
        @endif
    </div>
</nav>
