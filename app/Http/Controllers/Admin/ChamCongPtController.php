<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCongPt;
use App\Models\Pt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChamCongPtController extends Controller
{
    public function index(Request $request)
    {
        $query = ChamCongPt::with(['pt.nguoiDung', 'lichTap.khachHang.nguoiDung']);
        
        // Filters
        if ($request->id_pt) {
            $query->where('id_pt', $request->id_pt);
        }
        
        if ($request->month) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereMonth('ngay_lam', $date->month)
                  ->whereYear('ngay_lam', $date->year);
        }
        
        if ($request->trang_thai) {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        $timesheets = $query->latest('ngay_lam')->paginate(20);
        $pts = Pt::with('nguoiDung')->get();
        
        // Calculate summary if PT and Month selected
        $summary = null;
        if ($request->id_pt && $request->month) {
            $pt = Pt::find($request->id_pt);
            $totalHours = $query->sum('so_gio_lam');
            $approvedHours = $query->where('trang_thai', 'da_duyet')->sum('so_gio_lam');
            
            $summary = [
                'total_hours' => $totalHours,
                'approved_hours' => $approvedHours,
                'base_salary' => $pt->luong_co_ban,
                'hourly_salary' => $approvedHours * $pt->luong_gio,
                'total_salary' => $pt->luong_co_ban + ($approvedHours * $pt->luong_gio)
            ];
        }
        
        return view('admin.cham_cong.index', compact('timesheets', 'pts', 'summary'));
    }

    public function approve($id)
    {
        $timesheet = ChamCongPt::findOrFail($id);
        $timesheet->approve();
        
        return back()->with('success', 'Đã duyệt chấm công.');
    }
    
    public function approveAll(Request $request)
    {
        $query = ChamCongPt::where('trang_thai', 'chua_duyet');
        
        if ($request->id_pt) {
            $query->where('id_pt', $request->id_pt);
        }
        
        if ($request->month) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereMonth('ngay_lam', $date->month)
                  ->whereYear('ngay_lam', $date->year);
        }
        
        $count = $query->count();
        $query->update(['trang_thai' => 'da_duyet']);
        
        return back()->with('success', "Đã duyệt tất cả {$count} phiếu chấm công.");
    }
}
