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

<!-- Calendar -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div id="calendar"></div>
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
            events: {
                url: '{{ route("admin.lich-tap.index") }}',
                extraParams: function() {
                    return {
                        id_pt: filterPt.value
                    };
                }
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
    });
</script>
@endpush
@endsection
