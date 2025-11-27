<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ auth()->check() ? route(auth()->user()->getDashboardRoute()) : route('login') }}">
            <i class="bi bi-activity me-2"></i>Joe Fitness Center
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->isUser())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.dashboard') }}">
                                <i class="bi bi-house-door"></i> Trang chủ
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22 viewBox=%220 0 200 200%22%3E%3Ccircle cx=%22100%22 cy=%22100%22 r=%22100%22 fill=%22%23e0e0e0%22/%3E%3Ccircle cx=%22100%22 cy=%2280%22 r=%2235%22 fill=%22%239e9e9e%22/%3E%3Cellipse cx=%22100%22 cy=%22160%22 rx=%2260%22 ry=%2240%22 fill=%22%239e9e9e%22/%3E%3C/svg%3E' }}" 
                                 alt="Avatar" 
                                 class="rounded-circle me-2" 
                                 width="32" height="32"
                                 onerror="this.onerror=null">
                            {{ auth()->user()->ho_ten }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <small class="text-muted">
                                        @if(auth()->user()->isAdmin())
                                            Quản trị viên
                                        @elseif(auth()->user()->isPt())
                                            Personal Trainer
                                        @else
                                            Khách hàng
                                        @endif
                                    </small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person"></i> Thông tin cá nhân
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
