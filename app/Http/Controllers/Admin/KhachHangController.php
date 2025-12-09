<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\GoiTap;
use App\Models\Pt;
use App\Models\DangKyGoi;

class KhachHangController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = KhachHang::with(['nguoiDung', 'dangKyGoi']);

        // Filter by card status
        if ($request->filled('trang_thai_the')) {
            $query->where('trang_thai_the', $request->trang_thai_the);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $khachHangs = $query->latest()->paginate(15);
        
        // Auto-update expired cards
        foreach ($khachHangs as $kh) {
            $kh->checkAndUpdateExpiredStatus();
        }

        return view('admin.khach_hang.index', compact('khachHangs'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('admin.khach_hang.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email',
            'password' => 'required|min:6',
            'sdt' => 'nullable|string|max:15',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',
            'avatar' => 'nullable|image|max:2048',
            'thoi_han_thang' => 'nullable|in:3,6,12',
        ]);

        DB::beginTransaction();
        try {
            // Upload avatar if exists
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            // Create user account
            $nguoiDung = NguoiDung::create([
                'ho_ten' => $validated['ho_ten'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'sdt' => $validated['sdt'] ?? null,
                'ngay_sinh' => $validated['ngay_sinh'] ?? null,
                'gioi_tinh' => $validated['gioi_tinh'] ?? null,
                'avatar' => $avatarPath,
                'role' => 'user',
            ]);

            // Create customer record with auto-generated card number
            $maThe = KhachHang::generateMaThe();
            
            // Set thoi_han_thang (default 3 months)
            $thoiHanThang = $validated['thoi_han_thang'] ?? 3;
            
            $khachHang = KhachHang::create([
                'id_nguoi_dung' => $nguoiDung->id,
                'ma_the' => $maThe,
                'trang_thai_the' => 'hoat_dong',
                'thoi_han_thang' => $thoiHanThang,
            ]);
            
            // Calculate expiration date from created_at + thoi_han_thang
            $ngayHetHan = KhachHang::calculateExpirationDate($khachHang->created_at, $thoiHanThang);
            $khachHang->update(['ngay_het_han' => $ngayHetHan]);

            DB::commit();

            return redirect()->route('admin.khach-hang.index')
                ->with('success', "Đăng ký khách hàng thành công! Mã thẻ: {$maThe}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer
     */
    public function show(KhachHang $khachHang)
    {
        $khachHang->load(['nguoiDung', 'dangKyGoi.goiTap', 'dangKyGoi.pt.nguoiDung']);
        return view('admin.khach_hang.show', compact('khachHang'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(KhachHang $khachHang)
    {
        $khachHang->load('nguoiDung');
        return view('admin.khach_hang.edit', compact('khachHang'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, KhachHang $khachHang)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email,' . $khachHang->id_nguoi_dung,
            'password' => 'nullable|min:6',
            'sdt' => 'nullable|string|max:15',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',
            'avatar' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $nguoiDung = $khachHang->nguoiDung;

            // Upload avatar if exists
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $nguoiDung->avatar = $avatarPath;
            }

            // Update user info
            $nguoiDung->ho_ten = $validated['ho_ten'];
            $nguoiDung->email = $validated['email'];
            $nguoiDung->sdt = $validated['sdt'] ?? null;
            $nguoiDung->ngay_sinh = $validated['ngay_sinh'] ?? null;
            $nguoiDung->gioi_tinh = $validated['gioi_tinh'] ?? null;
            
            if (!empty($validated['password'])) {
                $nguoiDung->password = Hash::make($validated['password']);
            }
            
            $nguoiDung->save();

            DB::commit();

            return redirect()->route('admin.khach-hang.index')
                ->with('success', 'Cập nhật khách hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle card status (lock/unlock)
     */
    public function toggleCard(KhachHang $khachHang)
    {
        $newStatus = $khachHang->trang_thai_the === 'khoa' ? 'hoat_dong' : 'khoa';
        $khachHang->update(['trang_thai_the' => $newStatus]);

        $message = $newStatus === 'khoa' ? 'Đã khóa thẻ' : 'Đã mở thẻ';
        return back()->with('success', $message);
    }

    /**
     * Remove the specified customer
     */
    public function destroy(KhachHang $khachHang)
    {
        DB::beginTransaction();
        try {
            $khachHang->delete();
            $khachHang->nguoiDung->delete();

            DB::commit();

            return redirect()->route('admin.khach-hang.index')
                ->with('success', 'Xóa khách hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    /**
     * Show form to assign package to customer
     */
    public function assignPackage(KhachHang $khachHang)
    {
        $goiTaps = GoiTap::hoatDong()->get();
        $pts = Pt::hoatDong()->with('nguoiDung')->get();
        
        return view('admin.khach_hang.assign_package', compact('khachHang', 'goiTaps', 'pts'));
    }

    /**
     * Store assigned package
     */
    public function storePackage(Request $request, KhachHang $khachHang)
    {
        $validated = $request->validate([
            'id_goi_tap' => 'required|exists:goi_tap,id',
            'id_pt' => 'required|exists:pt,id',
            'ngay_dang_ky' => 'required|date',
            'trang_thai_thanh_toan' => 'required|in:chua_thanh_toan,da_thanh_toan',
        ]);

        $goiTap = GoiTap::findOrFail($validated['id_goi_tap']);

        DangKyGoi::create([
            'id_khach_hang' => $khachHang->id,
            'id_goi_tap' => $validated['id_goi_tap'],
            'id_pt' => $validated['id_pt'],
            'tong_buoi' => $goiTap->so_buoi,
            'buoi_da_tap' => 0,
            'buoi_con_lai' => $goiTap->so_buoi,
            'ngay_dang_ky' => $validated['ngay_dang_ky'],
            'trang_thai' => 'hoat_dong',
            'trang_thai_thanh_toan' => $validated['trang_thai_thanh_toan'],
        ]);

        return redirect()->route('admin.khach-hang.show', $khachHang->id)
            ->with('success', 'Đăng ký gói tập thành công!');
    }
}
