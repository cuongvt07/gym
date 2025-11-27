<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichTap;
use App\Models\Pt;
use App\Models\KhachHang;
use App\Models\DangKyGoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LichTapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get data for filters
        $pts = Pt::with('nguoiDung')->get();
        
        // If it's an AJAX request for FullCalendar events
        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;
            
            $query = LichTap::with(['pt.nguoiDung', 'khachHang.nguoiDung'])
                ->whereDate('ngay_tap', '>=', $start)
                ->whereDate('ngay_tap', '<=', $end);
                
            if ($request->id_pt) {
                $query->where('id_pt', $request->id_pt);
            }
            
            $schedules = $query->get();
            
            $events = [];
            foreach ($schedules as $schedule) {
                $color = '#3788d8'; // Default blue for da_xep
                if ($schedule->trang_thai == 'da_hoc') $color = '#28a745'; // Green
                if ($schedule->trang_thai == 'huy') $color = '#dc3545'; // Red
                if ($schedule->trang_thai == 'vang_mat') $color = '#6c757d'; // Grey
                
                $events[] = [
                    'id' => $schedule->id,
                    'title' => $schedule->khachHang->nguoiDung->ho_ten . ' (' . $schedule->pt->nguoiDung->ho_ten . ')',
                    'start' => $schedule->ngay_tap->format('Y-m-d') . 'T' . $schedule->gio_bat_dau,
                    'end' => $schedule->ngay_tap->format('Y-m-d') . 'T' . $schedule->gio_ket_thuc,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'pt_name' => $schedule->pt->nguoiDung->ho_ten,
                        'kh_name' => $schedule->khachHang->nguoiDung->ho_ten,
                        'status' => $schedule->trang_thai,
                        'note' => $schedule->ghi_chu
                    ]
                ];
            }
            
            return response()->json($events);
        }
        
        return view('admin.lich_tap.index', compact('pts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pts = Pt::hoatDong()->with('nguoiDung')->get();
        
        // Only get customers with active packages and remaining sessions
        $khachHangs = KhachHang::whereHas('dangKyGoi', function($q) {
            $q->hoatDong()->where('buoi_con_lai', '>', 0);
        })->with(['nguoiDung', 'dangKyGoi' => function($q) {
            $q->hoatDong()->where('buoi_con_lai', '>', 0)->with('goiTap');
        }])->get();
        
        return view('admin.lich_tap.create', compact('pts', 'khachHangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_khach_hang' => 'required|exists:khach_hang,id',
            'id_pt' => 'required|exists:pt,id',
            'ngay_tap' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'ghi_chu' => 'nullable|string',
        ]);

        // 1. Get active package for customer
        $dangKyGoi = DangKyGoi::where('id_khach_hang', $validated['id_khach_hang'])
            ->where('id_pt', $validated['id_pt']) // Must match assigned PT
            ->hoatDong()
            ->where('buoi_con_lai', '>', 0)
            ->first();

        if (!$dangKyGoi) {
            // Try finding any active package if PT specific one not found (optional business rule)
            // For now, strict rule: Must schedule with assigned PT
            return back()->withInput()->withErrors(['id_pt' => 'Khách hàng không có gói tập hoạt động với PT này hoặc đã hết buổi.']);
        }

        // 2. Validate PT availability
        $conflict = !LichTap::validateNoConflict(
            $validated['id_pt'], 
            $validated['ngay_tap'], 
            $validated['gio_bat_dau'], 
            $validated['gio_ket_thuc']
        );

        if ($conflict) {
            return back()->withInput()->withErrors(['gio_bat_dau' => 'PT đã có lịch dạy trong khung giờ này.']);
        }

        // 3. Create schedule
        LichTap::create([
            'id_dang_ky_goi' => $dangKyGoi->id,
            'id_pt' => $validated['id_pt'],
            'id_khach_hang' => $validated['id_khach_hang'],
            'ngay_tap' => $validated['ngay_tap'],
            'gio_bat_dau' => $validated['gio_bat_dau'],
            'gio_ket_thuc' => $validated['gio_ket_thuc'],
            'ghi_chu' => $validated['ghi_chu'],
            'trang_thai' => 'da_xep',
        ]);

        return redirect()->route('admin.lich-tap.index')
            ->with('success', 'Xếp lịch tập thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LichTap $lichTap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LichTap $lichTap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LichTap $lichTap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LichTap $lichTap)
    {
        // Cancel schedule
        $lichTap->update(['trang_thai' => 'huy']);
        return back()->with('success', 'Đã hủy lịch tập.');
    }

    /**
     * Mark schedule as absent
     */
    public function markAbsent(LichTap $lichTap)
    {
        DB::transaction(function () use ($lichTap) {
            // 1. Update status
            $lichTap->update(['trang_thai' => 'vang_mat']);
            
            // 2. Deduct session from package
            $lichTap->dangKyGoi->deductSession();
            
            // 3. Create timesheet for PT (still paid)
            // This will be handled in Phase 2 part 3
        });

        return back()->with('success', 'Đã đánh dấu vắng mặt (đã trừ buổi).');
    }
}
