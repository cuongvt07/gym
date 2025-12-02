<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pt;
use App\Models\NguoiDung;
use App\Models\KhachHang;
use App\Models\DangKyGoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PtController extends Controller
{
    /**
     * Display a listing of PTs
     */
    public function index(Request $request)
    {
        $query = Pt::with('nguoiDung');

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $pts = $query->latest()->paginate(15);

        return view('admin.pt.index', compact('pts'));
    }

    /**
     * Show the form for creating a new PT
     */
    public function create()
    {
        $vipCustomers = KhachHang::with('nguoiDung')->vip()->get();
        return view('admin.pt.create', compact('vipCustomers'));
    }

    /**
     * Store a newly created PT
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
            'luong_co_ban' => 'required|numeric|min:0',
            'luong_gio' => 'required|numeric|min:0',
            'so_gio_tieu_chuan' => 'required|integer|min:0',
            'kinh_nghiem' => 'nullable|string',
            'chung_chi' => 'nullable|string',
            'chuyen_mon' => 'nullable|array',
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
                'role' => 'pt',
            ]);

            // Create PT record
            Pt::create([
                'id_nguoi_dung' => $nguoiDung->id,
                'luong_co_ban' => $validated['luong_co_ban'],
                'luong_gio' => $validated['luong_gio'],
                'so_gio_tieu_chuan' => $validated['so_gio_tieu_chuan'],
                'kinh_nghiem' => $validated['kinh_nghiem'] ?? null,
                'chung_chi' => $validated['chung_chi'] ?? null,
                'chuyen_mon' => $validated['chuyen_mon'] ?? [],
                'trang_thai' => 'hoat_dong',
            ]);

            // Assign VIP customers if selected
            if ($request->has('customers')) {
                DangKyGoi::whereIn('id_khach_hang', $request->customers)
                    ->where('trang_thai', 'hoat_dong')
                    ->update(['id_pt' => $pt->id]);
            }

            DB::commit();

            return redirect()->route('admin.pt.index')
                ->with('success', 'Thêm PT thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified PT
     */
    public function show(Pt $pt)
    {
        $pt->load('nguoiDung');
        return view('admin.pt.show', compact('pt'));
    }

    /**
     * Show the form for editing the specified PT
     */
    public function edit(Pt $pt)
    {
        $pt->load('nguoiDung');
        $vipCustomers = KhachHang::with('nguoiDung')->vip()->get();
        
        // Get IDs of customers currently assigned to this PT
        $assignedCustomerIds = DangKyGoi::where('id_pt', $pt->id)
            ->where('trang_thai', 'hoat_dong')
            ->pluck('id_khach_hang')
            ->toArray();

        return view('admin.pt.edit', compact('pt', 'vipCustomers', 'assignedCustomerIds'));
    }

    /**
     * Update the specified PT
     */
    public function update(Request $request, Pt $pt)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email,' . $pt->id_nguoi_dung,
            'password' => 'nullable|min:6',
            'sdt' => 'nullable|string|max:15',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',
            'avatar' => 'nullable|image|max:2048',
            'luong_co_ban' => 'required|numeric|min:0',
            'luong_gio' => 'required|numeric|min:0',
            'so_gio_tieu_chuan' => 'required|integer|min:0',
            'kinh_nghiem' => 'nullable|string',
            'chung_chi' => 'nullable|string',
            'chuyen_mon' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $nguoiDung = $pt->nguoiDung;

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

            // Update PT info
            $pt->update([
                'luong_co_ban' => $validated['luong_co_ban'],
                'luong_gio' => $validated['luong_gio'],
                'so_gio_tieu_chuan' => $validated['so_gio_tieu_chuan'],
                'kinh_nghiem' => $validated['kinh_nghiem'] ?? null,
                'chung_chi' => $validated['chung_chi'] ?? null,
                'chuyen_mon' => $validated['chuyen_mon'] ?? [],
            ]);

            // Update VIP customers assignment
            // 1. Remove PT from unselected customers (who were previously assigned)
            DangKyGoi::where('id_pt', $pt->id)
                ->where('trang_thai', 'hoat_dong')
                ->whereNotIn('id_khach_hang', $request->customers ?? [])
                ->update(['id_pt' => null]);

            // 2. Assign PT to selected customers
            if ($request->has('customers')) {
                DangKyGoi::whereIn('id_khach_hang', $request->customers)
                    ->where('trang_thai', 'hoat_dong')
                    ->update(['id_pt' => $pt->id]);
            }

            DB::commit();

            return redirect()->route('admin.pt.index')
                ->with('success', 'Cập nhật PT thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle PT status (lock/unlock)
     */
    public function toggleStatus(Pt $pt)
    {
        $newStatus = $pt->trang_thai === 'khoa' ? 'hoat_dong' : 'khoa';
        $pt->update(['trang_thai' => $newStatus]);

        $message = $newStatus === 'khoa' ? 'Đã khóa PT' : 'Đã mở khóa PT';
        return back()->with('success', $message);
    }

    /**
     * Remove the specified PT
     */
    public function destroy(Pt $pt)
    {
        DB::beginTransaction();
        try {
            $pt->delete();
            $pt->nguoiDung->delete();

            DB::commit();

            return redirect()->route('admin.pt.index')
                ->with('success', 'Xóa PT thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
