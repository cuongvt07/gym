<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhanHoi;
use Illuminate\Http\Request;

class PhanHoiController extends Controller
{
    public function index(Request $request)
    {
        $query = PhanHoi::with('nguoiDung');

        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $phanHois = $query->latest('ngay_gui')->paginate(10);

        return view('admin.phan_hoi.index', compact('phanHois'));
    }

    public function update(Request $request, PhanHoi $phanHoi)
    {
        $validated = $request->validate([
            'phan_hoi_lai' => 'required|string',
            'trang_thai' => 'required|in:dang_xu_ly,da_xu_ly',
        ]);

        $phanHoi->update($validated);

        return back()->with('success', 'Đã cập nhật phản hồi.');
    }
}
