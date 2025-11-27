<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThanhToan;
use App\Models\DangKyGoi;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ThanhToanController extends Controller
{
    public function index(Request $request)
    {
        $query = ThanhToan::with(['dangKyGoi.khachHang.nguoiDung', 'dangKyGoi.goiTap', 'nguoiThu']);

        if ($request->filled('date')) {
            $query->whereDate('ngay_thanh_toan', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_hoa_don', 'like', "%$search%")
                  ->orWhereHas('dangKyGoi.khachHang.nguoiDung', function($sub) use ($search) {
                      $sub->where('ho_ten', 'like', "%$search%");
                  });
            });
        }

        $thanhToans = $query->latest()->paginate(10);

        return view('admin.thanh_toan.index', compact('thanhToans'));
    }

    public function create(Request $request)
    {
        // Get customers who have unpaid packages
        $unpaidPackages = DangKyGoi::where('trang_thai_thanh_toan', 'chua_thanh_toan')
            ->where('trang_thai', 'hoat_dong')
            ->with(['khachHang.nguoiDung', 'goiTap'])
            ->get();

        $selectedPackage = null;
        if ($request->id_dang_ky_goi) {
            $selectedPackage = $unpaidPackages->find($request->id_dang_ky_goi);
        }

        return view('admin.thanh_toan.create', compact('unpaidPackages', 'selectedPackage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_dang_ky_goi' => 'required|exists:dang_ky_goi,id',
            'so_tien' => 'required|numeric|min:0',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $dangKyGoi = DangKyGoi::findOrFail($validated['id_dang_ky_goi']);

            // 1. Create Payment Record
            $thanhToan = ThanhToan::create([
                'id_dang_ky_goi' => $dangKyGoi->id,
                'so_tien' => $validated['so_tien'],
                'ngay_thanh_toan' => now(),
                'phuong_thuc' => 'tien_mat',
                'nguoi_thu' => auth()->id(),
                'ma_hoa_don' => ThanhToan::generateInvoiceCode(),
                'ghi_chu' => $validated['ghi_chu'],
            ]);

            // 2. Update Subscription Status
            $dangKyGoi->update(['trang_thai_thanh_toan' => 'da_thanh_toan']);
        });

        return redirect()->route('admin.thanh-toan.index')
            ->with('success', 'Thanh toán thành công!');
    }

    public function invoice(ThanhToan $thanhToan)
    {
        $thanhToan->load(['dangKyGoi.khachHang.nguoiDung', 'dangKyGoi.goiTap', 'nguoiThu']);
        
        $pdf = Pdf::loadView('admin.thanh_toan.invoice', compact('thanhToan'));
        return $pdf->stream('invoice-' . $thanhToan->ma_hoa_don . '.pdf');
    }
}
