@extends('layouts.app')

@section('title', 'Quản lý Lịch tập')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-calendar-week me-2"></i>Quản lý Lịch tập
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.lich-tap.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Xếp lịch mới
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Lọc theo PT</label>
                <select id="filter-pt" class="form-select">
                    <option value="">Tất cả PT</option>
                    @foreach($pts as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->nguoiDung->ho_ten }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>


<!-- Calendar (Collapsible) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-calendar-week me-2"></i>Lịch tập
        </h6>
        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#calendarCollapse" aria-expanded="true" aria-controls="calendarCollapse">
            <i class="bi bi-chevron-up" id="calendarToggleIcon"></i>
            <span id="calendarToggleText">Thu gọn</span>
        </button>
    </div>
    <div class="collapse show" id="calendarCollapse">
        <div class="card-body p-0">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Upcoming Schedules List -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách lịch tập sắp tới</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>PT</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingSchedules as $schedule)
                        <tr>
                            <td>
                                <strong>{{ $schedule->khachHang->nguoiDung->ho_ten }}</strong><br>
                                <small class="text-muted">{{ $schedule->khachHang->ma_the }}</small>
                            </td>
                            <td>{{ $schedule->pt->nguoiDung->ho_ten }}</td>
                            <td>
                                {{ $schedule->ngay_tap->format('d/m/Y') }}<br>
                                <small>{{ \Carbon\Carbon::parse($schedule->gio_bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->gio_ket_thuc)->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($schedule->trang_thai == 'da_xep')
                                    <span class="badge bg-primary">Đã xếp</span>
                                @elseif($schedule->trang_thai == 'da_hoc')
                                    <span class="badge bg-success">Đã học</span>
                                @elseif($schedule->trang_thai == 'huy')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @elseif($schedule->trang_thai == 'vang_mat')
                                    <span class="badge bg-secondary">Vắng mặt</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($schedule->ghi_chu, 30) }}</td>
                            <td>
                                @if($schedule->trang_thai == 'da_xep')
                                    <form action="{{ route('admin.lich-tap.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy lịch này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hủy lịch">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.lich-tap.mark-absent', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Đánh dấu vắng mặt (sẽ trừ buổi)?');">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" title="Vắng mặt">
                                            <i class="bi bi-person-x"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Không có lịch tập sắp tới</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Schedule Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xếp lịch tập mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lich-tap.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_id_khach_hang" class="form-label">Khách hàng <span class="text-danger">*</span></label>
                        <select class="form-select" id="create_id_khach_hang" name="id_khach_hang" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($khachHangs as $kh)
                                <option value="{{ $kh->id }}" data-pt="{{ $kh->dangKyGoi->first()->id_pt }}">
                                    {{ $kh->nguoiDung->ho_ten }} ({{ $kh->ma_the }}) - Còn {{ $kh->dangKyGoi->sum('buoi_con_lai') }} buổi
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_id_pt" class="form-label">PT hướng dẫn <span class="text-danger">*</span></label>
                        <select class="form-select" id="create_id_pt_display" disabled>
                            <option value="">-- Chọn PT --</option>
                            @foreach($pts as $pt)
                                <option value="{{ $pt->id }}">{{ $pt->nguoiDung->ho_ten }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="id_pt" id="create_id_pt">
                        <div class="form-text">PT được tự động chọn theo gói tập của khách hàng.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_ngay_tap" class="form-label">Ngày tập</label>
                            <input type="date" class="form-control" id="create_ngay_tap" name="ngay_tap" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thời gian</label>
                            <div class="input-group">
                                <input type="time" class="form-control" id="create_gio_bat_dau" name="gio_bat_dau" required>
                                <span class="input-group-text">-</span>
                                <input type="time" class="form-control" id="create_gio_ket_thuc" name="gio_ket_thuc" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="create_ghi_chu" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="create_ghi_chu" name="ghi_chu" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu lịch tập</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventTitle">Chi tiết lịch tập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>PT:</strong> <span id="eventPT"></span></p>
                <p><strong>Khách hàng:</strong> <span id="eventKH"></span></p>
                <p><strong>Thời gian:</strong> <span id="eventTime"></span></p>
                <p><strong>Trạng thái:</strong> <span id="eventStatus"></span></p>
                <p><strong>Ghi chú:</strong> <span id="eventNote"></span></p>
            </div>
            <div class="modal-footer">
                <form id="cancelForm" method="POST" action="" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn hủy lịch này?')">Hủy lịch</button>
                </form>
                <form id="absentForm" method="POST" action="" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Đánh dấu vắng mặt (sẽ trừ buổi)?')">Vắng mặt</button>
                </form>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    #calendar {
        height: 800px;
        padding: 20px;
    }
    .fc-event {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var filterPt = document.getElementById('filter-pt');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'vi',
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            allDaySlot: false,
            selectable: true,
            selectMirror: true,
            events: {
                url: '{{ route("admin.lich-tap.index") }}',
                extraParams: function() {
                    return {
                        id_pt: filterPt.value
                    };
                }
            },
            select: function(info) {
                // Open create modal
                var modal = new bootstrap.Modal(document.getElementById('createModal'));
                
                // Pre-fill date and time
                document.getElementById('create_ngay_tap').value = info.startStr.split('T')[0];
                document.getElementById('create_gio_bat_dau').value = info.startStr.split('T')[1].substring(0, 5);
                document.getElementById('create_gio_ket_thuc').value = info.endStr.split('T')[1].substring(0, 5);
                
                modal.show();
                calendar.unselect();
            },
            eventClick: function(info) {
                var event = info.event;
                var props = event.extendedProps;
                
                document.getElementById('eventTitle').textContent = event.title;
                document.getElementById('eventPT').textContent = props.pt_name;
                document.getElementById('eventKH').textContent = props.kh_name;
                document.getElementById('eventTime').textContent = event.start.toLocaleString() + ' - ' + event.end.toLocaleString();
                
                var statusText = '';
                var statusClass = '';
                switch(props.status) {
                    case 'da_xep': statusText = 'Đã xếp'; statusClass = 'text-primary'; break;
                    case 'da_hoc': statusText = 'Đã học'; statusClass = 'text-success'; break;
                    case 'huy': statusText = 'Đã hủy'; statusClass = 'text-danger'; break;
                    case 'vang_mat': statusText = 'Vắng mặt'; statusClass = 'text-secondary'; break;
                }
                document.getElementById('eventStatus').innerHTML = `<span class="${statusClass} fw-bold">${statusText}</span>`;
                document.getElementById('eventNote').textContent = props.note || 'Không có';
                
                // Update action forms
                var cancelForm = document.getElementById('cancelForm');
                var absentForm = document.getElementById('absentForm');
                
                cancelForm.action = `/admin/lich-tap/${event.id}`;
                absentForm.action = `/admin/lich-tap/${event.id}/mark-absent`;
                
                // Show/hide buttons based on status
                if (props.status === 'da_xep') {
                    cancelForm.style.display = 'inline';
                    absentForm.style.display = 'inline';
                } else {
                    cancelForm.style.display = 'none';
                    absentForm.style.display = 'none';
                }
                
                var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            }
        });
        
        calendar.render();
        
        filterPt.addEventListener('change', function() {
            calendar.refetchEvents();
        });

        // Handle customer selection in create modal
        document.getElementById('create_id_khach_hang').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var ptId = selectedOption.getAttribute('data-pt');
            var ptDisplay = document.getElementById('create_id_pt_display');
            var ptInput = document.getElementById('create_id_pt');
            
            if (ptId) {
                ptDisplay.value = ptId;
                ptInput.value = ptId;
            } else {
                ptDisplay.value = "";
                ptInput.value = "";
            }
        });
        
        // Handle collapse/expand icon toggle
        var calendarCollapse = document.getElementById('calendarCollapse');
        var toggleIcon = document.getElementById('calendarToggleIcon');
        var toggleText = document.getElementById('calendarToggleText');
        
        calendarCollapse.addEventListener('show.bs.collapse', function () {
            toggleIcon.classList.remove('bi-chevron-down');
            toggleIcon.classList.add('bi-chevron-up');
            toggleText.textContent = 'Thu gọn';
        });
        
        calendarCollapse.addEventListener('hide.bs.collapse', function () {
            toggleIcon.classList.remove('bi-chevron-up');
            toggleIcon.classList.add('bi-chevron-down');
            toggleText.textContent = 'Mở rộng';
        });
    });
</script>
@endpush
@endsection
