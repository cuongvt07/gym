<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoiTap;
use Illuminate\Http\Request;

class GoiTapController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index(Request $request)
    {
        $query = GoiTap::query();

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $goiTaps = $query->latest()->paginate(15);

        return view('admin.goi_tap.index', compact('goiTaps'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.goi_tap.create');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_goi' => 'required|string|max:255',
            'so_buoi' => 'required|integer|min:1',
            'gia' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoat_dong,tam_ngung',
        ]);

        GoiTap::create($validated);

        return redirect()->route('admin.goi-tap.index')
            ->with('success', 'Thêm gói tập thành công!');
    }

    /**
     * Display the specified package
     */
    public function show(GoiTap $goiTap)
    {
        return view('admin.goi_tap.show', compact('goiTap'));
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit(GoiTap $goiTap)
    {
        return view('admin.goi_tap.edit', compact('goiTap'));
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, GoiTap $goiTap)
    {
        $validated = $request->validate([
            'ten_goi' => 'required|string|max:255',
            'so_buoi' => 'required|integer|min:1',
            'gia' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoat_dong,tam_ngung',
        ]);

        $goiTap->update($validated);

        return redirect()->route('admin.goi-tap.index')
            ->with('success', 'Cập nhật gói tập thành công!');
    }

    /**
     * Toggle package status
     */
    public function toggleStatus(GoiTap $goiTap)
    {
        $newStatus = $goiTap->trang_thai === 'tam_ngung' ? 'hoat_dong' : 'tam_ngung';
        $goiTap->update(['trang_thai' => $newStatus]);

        $message = $newStatus === 'tam_ngung' ? 'Đã tạm ngưng gói tập' : 'Đã kích hoạt gói tập';
        return back()->with('success', $message);
    }

    /**
     * Remove the specified package
     */
    public function destroy(GoiTap $goiTap)
    {
        $goiTap->delete();

        return redirect()->route('admin.goi-tap.index')
            ->with('success', 'Xóa gói tập thành công!');
    }
}
