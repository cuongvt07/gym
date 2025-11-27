<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiTap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaiTapController extends Controller
{
    public function index(Request $request)
    {
        $query = BaiTap::query();

        if ($request->filled('search')) {
            $query->where('ten_bai_tap', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('nhom_co')) {
            $query->where('nhom_co', $request->nhom_co);
        }

        $baiTaps = $query->latest()->paginate(12); // Grid view needs fewer items per page

        return view('admin.bai_tap.index', compact('baiTaps'));
    }

    public function create()
    {
        return view('admin.bai_tap.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_bai_tap' => 'required|string|max:255',
            'nhom_co' => 'required|string',
            'do_kho' => 'required|in:de,trung_binh,kho',
            'mo_ta' => 'nullable|string',
            'video_url' => 'nullable|url',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('hinh_anh')) {
            $validated['hinh_anh'] = $request->file('hinh_anh')->store('exercises', 'public');
        }

        BaiTap::create($validated);

        return redirect()->route('admin.bai-tap.index')
            ->with('success', 'Thêm bài tập thành công!');
    }

    public function edit(BaiTap $baiTap)
    {
        return view('admin.bai_tap.edit', compact('baiTap'));
    }

    public function update(Request $request, BaiTap $baiTap)
    {
        $validated = $request->validate([
            'ten_bai_tap' => 'required|string|max:255',
            'nhom_co' => 'required|string',
            'do_kho' => 'required|in:de,trung_binh,kho',
            'mo_ta' => 'nullable|string',
            'video_url' => 'nullable|url',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('hinh_anh')) {
            // Delete old image
            if ($baiTap->hinh_anh) {
                Storage::disk('public')->delete($baiTap->hinh_anh);
            }
            $validated['hinh_anh'] = $request->file('hinh_anh')->store('exercises', 'public');
        }

        $baiTap->update($validated);

        return redirect()->route('admin.bai-tap.index')
            ->with('success', 'Cập nhật bài tập thành công!');
    }

    public function destroy(BaiTap $baiTap)
    {
        if ($baiTap->hinh_anh) {
            Storage::disk('public')->delete($baiTap->hinh_anh);
        }
        $baiTap->delete();

        return back()->with('success', 'Xóa bài tập thành công!');
    }
}
