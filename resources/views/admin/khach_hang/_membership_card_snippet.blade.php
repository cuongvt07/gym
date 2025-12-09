                
                <!-- Membership Card Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-credit-card me-2"></i>Thông tin thẻ thành viên
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Trạng thái thẻ</label>
                                <p class="mb-0">
                                    @if($khachHang->trang_thai_the == 'hoat_dong')
                                        <span class="badge bg-success">Hoạt động</span>
                                    @elseif($khachHang->trang_thai_the == 'khoa')
                                        <span class="badge bg-danger">Khóa</span>
                                    @else
                                        <span class="badge bg-secondary">Hết hạn</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Hạng thành viên</label>
                                <p class="mb-0">
                                    @if($khachHang->is_vip)
                                        <span class="badge bg-warning text-dark fs-6">
                                            <i class="bi bi-star-fill me-1"></i>VIP
                                        </span>
                                        <small class="text-muted d-block mt-1">Có gói tập hoạt động</small>
                                    @else
                                        <span class="badge bg-light text-dark border">Thường</span>
                                        <small class="text-muted d-block mt-1">Chưa có gói tập</small>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Ngày bắt đầu hiệu lực</label>
                                <p class="mb-0">
                                    @if($khachHang->ngay_bat_dau)
                                        <i class="bi bi-calendar-event me-1 text-success"></i>
                                        {{ $khachHang->ngay_bat_dau->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Thời hạn thẻ</label>
                                <p class="mb-0">
                                    @if($khachHang->thoi_han_thang)
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        {{ $khachHang->thoi_han_thang }} tháng
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Ngày hết hạn</label>
                                <p class="mb-0">
                                    @if($khachHang->ngay_het_han)
                                        <i class="bi bi-calendar-x me-1 text-danger"></i>
                                        {{ $khachHang->ngay_het_han->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Số ngày còn lại</label>
                                <p class="mb-0">
                                    @if($khachHang->is_expired)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Đã hết hạn
                                        </span>
                                    @elseif($khachHang->so_ngay_con_lai !== null)
                                        @if($khachHang->so_ngay_con_lai <= 7)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split me-1"></i>{{ $khachHang->so_ngay_con_lai }} ngày
                                            </span>
                                        @elseif($khachHang->so_ngay_con_lai <= 30)
                                            <span class="badge bg-info">
                                                <i class="bi bi-clock me-1"></i>{{ $khachHang->so_ngay_con_lai }} ngày
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>{{ $khachHang->so_ngay_con_lai }} ngày
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
