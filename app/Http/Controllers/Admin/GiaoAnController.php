<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiaoAnTap;
use App\Models\BaiTap;
use App\Models\ChiTietGiaoAn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiaoAnController extends Controller
{
    public function index()
    {
        $giaoAns = GiaoAnTap::withCount('chiTiet')->latest()->paginate(10);
        return view('admin.giao_an.index', compact('giaoAns'));
    }

    public function create()
    {
        $baiTaps = BaiTap::orderBy('ten_bai_tap')->get();
        return view('admin.giao_an.create', compact('baiTaps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_giao_an' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'muc_tieu' => 'required|in:tang_co,giam_mo,tang_suc_manh,duy_tri',
            'so_ngay' => 'required|integer|min:1|max:7',
            'exercises' => 'required|array',
            'exercises.*.id_bai_tap' => 'required|exists:bai_tap,id',
            'exercises.*.ngay_tap' => 'required|integer|min:1',
            'exercises.*.so_hiep' => 'required|integer|min:1',
            'exercises.*.so_lan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $giaoAn = GiaoAnTap::create([
                'ten_giao_an' => $validated['ten_giao_an'],
                'mo_ta' => $validated['mo_ta'],
                'muc_tieu' => $validated['muc_tieu'],
                'so_ngay' => $validated['so_ngay'],
            ]);

            foreach ($validated['exercises'] as $index => $exercise) {
                ChiTietGiaoAn::create([
                    'id_giao_an' => $giaoAn->id,
                    'id_bai_tap' => $exercise['id_bai_tap'],
                    'ngay_tap' => $exercise['ngay_tap'],
                    'thu_tu' => $index + 1,
                    'so_hiep' => $exercise['so_hiep'],
                    'so_lan' => $exercise['so_lan'],
                    'ghi_chu' => $exercise['ghi_chu'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.giao-an.index')->with('success', 'Tạo giáo án thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function show(GiaoAnTap $giaoAn)
    {
        $giaoAn->load(['chiTiet.baiTap']);
        
        // Group exercises by day
        $schedule = $giaoAn->chiTiet->groupBy('ngay_tap');
        
        return view('admin.giao_an.show', compact('giaoAn', 'schedule'));
    }

    public function destroy(GiaoAnTap $giaoAn)
    {
        $giaoAn->delete();
        return back()->with('success', 'Xóa giáo án thành công!');
    }
}
