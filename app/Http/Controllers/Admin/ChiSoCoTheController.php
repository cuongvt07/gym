<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiSoCoThe;
use App\Models\KhachHang;
use Illuminate\Http\Request;

class ChiSoCoTheController extends Controller
{
    public function index(Request $request)
    {
        $khachHangs = KhachHang::with('nguoiDung')->get();
        
        $selectedKhachHang = null;
        $history = collect();
        $chartData = null;

        if ($request->id_khach_hang) {
            $selectedKhachHang = KhachHang::find($request->id_khach_hang);
            
            $history = ChiSoCoThe::where('id_khach_hang', $request->id_khach_hang)
                ->with('pt.nguoiDung')
                ->latest('ngay_do')
                ->get();
                
            // Prepare Chart Data
            $chartData = [
                'dates' => $history->pluck('ngay_do')->map(fn($d) => $d->format('d/m'))->reverse()->values(),
                'weight' => $history->pluck('can_nang')->reverse()->values(),
                'muscle' => $history->pluck('khoi_luong_co')->reverse()->values(),
                'fat' => $history->pluck('ty_le_mo')->reverse()->values(),
            ];
        }

        return view('admin.chi_so.index', compact('khachHangs', 'selectedKhachHang', 'history', 'chartData'));
    }

    public function create(Request $request)
    {
        $khachHang = KhachHang::findOrFail($request->id_khach_hang);
        return view('admin.chi_so.create', compact('khachHang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_khach_hang' => 'required|exists:khach_hang,id',
            'ngay_do' => 'required|date',
            'can_nang' => 'required|numeric|min:20|max:200',
            'chieu_cao' => 'required|numeric|min:100|max:250',
            'ty_le_mo' => 'nullable|numeric|min:1|max:60',
            'khoi_luong_co' => 'nullable|numeric|min:10|max:100',
            'vong_eo' => 'nullable|numeric',
            'vong_hong' => 'nullable|numeric',
            'vong_nguc' => 'nullable|numeric',
            'vong_dui' => 'nullable|numeric',
            'vong_baptay' => 'nullable|numeric',
            'ghi_chu' => 'nullable|string',
        ]);

        $validated['id_pt'] = auth()->user()->isPt() ? auth()->user()->pt->id : null;

        ChiSoCoThe::create($validated);

        return redirect()->route('admin.chi-so.index', ['id_khach_hang' => $validated['id_khach_hang']])
            ->with('success', 'Ghi nhận chỉ số thành công!');
    }
}
