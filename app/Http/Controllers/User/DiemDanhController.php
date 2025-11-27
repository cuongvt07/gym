<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LichTap;
use App\Models\DiemDanh;
use App\Models\ChamCongPt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiemDanhController extends Controller
{
    public function dashboard()
    {
        $khachHang = auth()->user()->khachHang;
        
        // Get today's schedule
        $todaySchedule = LichTap::with(['pt.nguoiDung', 'diemDanh'])
            ->byCustomer($khachHang->id)
            ->today()
            ->where('trang_thai', 'da_xep')
            ->first();
            
        // Get active packages summary
        $packages = $khachHang->dangKyGoi()
            ->with('goiTap')
            ->hoatDong()
            ->get();

        // Get body metrics history for charts
        $metricsHistory = \App\Models\ChiSoCoThe::where('id_khach_hang', $khachHang->id)
            ->latest('ngay_do')
            ->take(10)
            ->get()
            ->reverse();

        $chartData = [
            'dates' => $metricsHistory->pluck('ngay_do')->map(fn($d) => $d->format('d/m'))->values(),
            'weight' => $metricsHistory->pluck('can_nang')->values(),
            'muscle' => $metricsHistory->pluck('khoi_luong_co')->values(),
        ];
            
        return view('user.dashboard', compact('todaySchedule', 'packages', 'chartData'));
    }

    public function checkIn(Request $request)
    {
        $khachHang = auth()->user()->khachHang;
        
        // Find valid schedule for today
        $schedule = LichTap::byCustomer($khachHang->id)
            ->today()
            ->where('trang_thai', 'da_xep')
            ->doesntHave('diemDanh') // Not checked in yet
            ->first();
            
        if (!$schedule) {
            return back()->with('error', 'Không tìm thấy lịch tập hợp lệ hôm nay hoặc bạn đã check-in rồi.');
        }
        
        DiemDanh::create([
            'id_lich_tap' => $schedule->id,
            'id_khach_hang' => $khachHang->id,
            'ngay_diem_danh' => today(),
            'gio_check_in' => now(),
        ]);
        
        return back()->with('success', 'Check-in thành công! Chúc bạn tập luyện vui vẻ.');
    }

    public function checkOut(Request $request)
    {
        $khachHang = auth()->user()->khachHang;
        
        // Find active check-in
        $attendance = DiemDanh::where('id_khach_hang', $khachHang->id)
            ->whereDate('ngay_diem_danh', today())
            ->whereNull('gio_check_out')
            ->first();
            
        if (!$attendance) {
            return back()->with('error', 'Bạn chưa check-in hoặc đã check-out rồi.');
        }
        
        DB::transaction(function () use ($attendance) {
            // 1. Update attendance
            $attendance->checkOut();
            
            // 2. Update schedule status
            $schedule = $attendance->lichTap;
            $schedule->update(['trang_thai' => 'da_hoc']);
            
            // 3. Deduct session
            $schedule->dangKyGoi->deductSession();
            
            // 4. Auto-create PT timesheet
            $duration = $attendance->getDurationInHours();
            // Minimum 1 hour credit if duration is short (business rule?) - Let's stick to actual or scheduled
            // Better: Use scheduled duration for salary calculation, but record actual
            // For now: Use scheduled duration if checked out properly
            $scheduledDuration = $schedule->getDurationInHours();
            
            ChamCongPt::create([
                'id_pt' => $schedule->id_pt,
                'id_lich_tap' => $schedule->id,
                'ngay_lam' => $schedule->ngay_tap,
                'gio_bat_dau' => $schedule->gio_bat_dau,
                'gio_ket_thuc' => $schedule->gio_ket_thuc,
                'so_gio_lam' => $scheduledDuration, // Pay based on scheduled hours
                'trang_thai' => 'chua_duyet',
            ]);
        });
        
        return back()->with('success', 'Check-out thành công! Buổi tập đã được ghi nhận.');
    }
}
