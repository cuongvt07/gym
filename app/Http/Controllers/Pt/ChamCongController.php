<?php

namespace App\Http\Controllers\Pt;

use App\Http\Controllers\Controller;
use App\Models\ChamCongPt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChamCongController extends Controller
{
    public function index(Request $request)
    {
        $pt = auth()->user()->pt;
        
        $query = ChamCongPt::with(['lichTap.khachHang.nguoiDung'])
            ->byPt($pt->id);
            
        // Filter by month (default to current month)
        $month = $request->month ?? date('Y-m');
        $date = Carbon::createFromFormat('Y-m', $month);
        
        $query->whereMonth('ngay_lam', $date->month)
              ->whereYear('ngay_lam', $date->year);
              
        $timesheets = $query->latest('ngay_lam')->get();
        
        // Calculate summary
        $totalHours = $timesheets->sum('so_gio_lam');
        $approvedHours = $timesheets->where('trang_thai', 'da_duyet')->sum('so_gio_lam');
        $pendingHours = $timesheets->where('trang_thai', 'chua_duyet')->sum('so_gio_lam');
        
        $estimatedSalary = $pt->luong_co_ban + ($approvedHours * $pt->luong_gio);
        
        return view('pt.cham_cong.index', compact('timesheets', 'totalHours', 'approvedHours', 'pendingHours', 'estimatedSalary'));
    }
}
