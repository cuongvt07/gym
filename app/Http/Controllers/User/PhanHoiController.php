<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PhanHoi;
use Illuminate\Http\Request;

class PhanHoiController extends Controller
{
    public function index()
    {
        $phanHois = PhanHoi::where('id_nguoi_dung', auth()->id())
            ->latest('ngay_gui')
            ->paginate(10);
            
        return view('user.phan_hoi.index', compact('phanHois'));
    }

    public function create()
    {
        return view('user.phan_hoi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'loai' => 'required|in:gop_y,khieu_nai,khen_ngoi,khac',
            'noi_dung' => 'required|string',
        ]);

        PhanHoi::create([
            'id_nguoi_dung' => auth()->id(),
            'tieu_de' => $validated['tieu_de'],
            'loai' => $validated['loai'],
            'noi_dung' => $validated['noi_dung'],
            'trang_thai' => 'cho_xu_ly',
        ]);

        return redirect()->route('user.phan-hoi.index')
            ->with('success', 'Cảm ơn bạn đã gửi phản hồi. Chúng tôi sẽ xử lý sớm nhất có thể.');
    }
}
