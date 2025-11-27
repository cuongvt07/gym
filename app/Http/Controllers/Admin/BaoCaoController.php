<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThanhToan;
use App\Models\DangKyGoi;
use App\Models\Pt;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BaoCaoController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month
        $month = $request->input('month', date('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        // 1. Revenue Chart Data (Daily)
        $revenueData = ThanhToan::select(
            DB::raw('DATE(ngay_thanh_toan) as date'),
            DB::raw('SUM(so_tien) as total')
        )
        ->whereBetween('ngay_thanh_toan', [$startOfMonth, $endOfMonth])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $chartLabels = [];
        $chartValues = [];
        
        // Fill missing days with 0
        $current = $startOfMonth->copy();
        while ($current <= $endOfMonth && $current <= now()) {
            $dateStr = $current->format('Y-m-d');
            $record = $revenueData->firstWhere('date', $dateStr);
            
            $chartLabels[] = $current->format('d/m');
            $chartValues[] = $record ? $record->total : 0;
            
            $current->addDay();
        }

        // 2. Revenue by Package (Pie Chart)
        $packageRevenue = DangKyGoi::join('thanh_toan', 'dang_ky_goi.id', '=', 'thanh_toan.id_dang_ky_goi')
            ->join('goi_tap', 'dang_ky_goi.id_goi_tap', '=', 'goi_tap.id')
            ->whereBetween('thanh_toan.ngay_thanh_toan', [$startOfMonth, $endOfMonth])
            ->select('goi_tap.ten_goi', DB::raw('SUM(thanh_toan.so_tien) as total'))
            ->groupBy('goi_tap.ten_goi')
            ->get();

        // 3. Top PTs (by Revenue)
        $topPts = DangKyGoi::join('thanh_toan', 'dang_ky_goi.id', '=', 'thanh_toan.id_dang_ky_goi')
            ->join('pt', 'dang_ky_goi.id_pt', '=', 'pt.id')
            ->join('nguoi_dung', 'pt.id_nguoi_dung', '=', 'nguoi_dung.id')
            ->whereBetween('thanh_toan.ngay_thanh_toan', [$startOfMonth, $endOfMonth])
            ->select('nguoi_dung.ho_ten', DB::raw('SUM(thanh_toan.so_tien) as total'))
            ->groupBy('nguoi_dung.ho_ten')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 4. Key Metrics
        $totalRevenue = ThanhToan::whereBetween('ngay_thanh_toan', [$startOfMonth, $endOfMonth])->sum('so_tien');
        $newCustomers = KhachHang::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $packagesSold = DangKyGoi::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        return view('admin.bao_cao.index', compact(
            'month', 
            'chartLabels', 'chartValues', 
            'packageRevenue', 'topPts',
            'totalRevenue', 'newCustomers', 'packagesSold'
        ));
    }
}
