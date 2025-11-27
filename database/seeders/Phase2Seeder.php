<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KhachHang;
use App\Models\Pt;
use App\Models\GoiTap;
use App\Models\DangKyGoi;
use App\Models\LichTap;
use Carbon\Carbon;

class Phase2Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Assign packages to customers
        $khachHangs = KhachHang::all();
        $pts = Pt::all();
        $goiTaps = GoiTap::all();
        
        if ($khachHangs->isEmpty() || $pts->isEmpty() || $goiTaps->isEmpty()) {
            $this->command->info('Missing basic data. Please run DatabaseSeeder first.');
            return;
        }

        // Customer 1: Active package with PT 1
        $kh1 = $khachHangs[0];
        $pt1 = $pts[0];
        $goi1 = $goiTaps[0]; // 10 sessions
        
        $dkg1 = DangKyGoi::create([
            'id_khach_hang' => $kh1->id,
            'id_goi_tap' => $goi1->id,
            'id_pt' => $pt1->id,
            'tong_buoi' => $goi1->so_buoi,
            'buoi_da_tap' => 2,
            'buoi_con_lai' => $goi1->so_buoi - 2,
            'ngay_dang_ky' => Carbon::now()->subDays(10),
            'trang_thai' => 'hoat_dong',
            'trang_thai_thanh_toan' => 'da_thanh_toan',
        ]);
        
        // Customer 2: Active package with PT 2
        $kh2 = $khachHangs[1];
        $pt2 = $pts[1];
        $goi2 = $goiTaps[1]; // 20 sessions
        
        $dkg2 = DangKyGoi::create([
            'id_khach_hang' => $kh2->id,
            'id_goi_tap' => $goi2->id,
            'id_pt' => $pt2->id,
            'tong_buoi' => $goi2->so_buoi,
            'buoi_da_tap' => 0,
            'buoi_con_lai' => $goi2->so_buoi,
            'ngay_dang_ky' => Carbon::now()->subDays(2),
            'trang_thai' => 'hoat_dong',
            'trang_thai_thanh_toan' => 'chua_thanh_toan',
        ]);

        // 2. Create schedules
        
        // Schedule for KH1 (Today)
        LichTap::create([
            'id_dang_ky_goi' => $dkg1->id,
            'id_pt' => $pt1->id,
            'id_khach_hang' => $kh1->id,
            'ngay_tap' => Carbon::today(),
            'gio_bat_dau' => '09:00',
            'gio_ket_thuc' => '10:00',
            'ghi_chu' => 'Tập ngực',
            'trang_thai' => 'da_xep',
        ]);
        
        // Schedule for KH1 (Tomorrow)
        LichTap::create([
            'id_dang_ky_goi' => $dkg1->id,
            'id_pt' => $pt1->id,
            'id_khach_hang' => $kh1->id,
            'ngay_tap' => Carbon::tomorrow(),
            'gio_bat_dau' => '09:00',
            'gio_ket_thuc' => '10:00',
            'ghi_chu' => 'Tập chân',
            'trang_thai' => 'da_xep',
        ]);
        
        // Schedule for KH2 (Today - Later)
        LichTap::create([
            'id_dang_ky_goi' => $dkg2->id,
            'id_pt' => $pt2->id,
            'id_khach_hang' => $kh2->id,
            'ngay_tap' => Carbon::today(),
            'gio_bat_dau' => '15:00',
            'gio_ket_thuc' => '16:00',
            'ghi_chu' => 'Buổi đầu tiên',
            'trang_thai' => 'da_xep',
        ]);
        
        // Past schedules (Completed)
        LichTap::create([
            'id_dang_ky_goi' => $dkg1->id,
            'id_pt' => $pt1->id,
            'id_khach_hang' => $kh1->id,
            'ngay_tap' => Carbon::yesterday(),
            'gio_bat_dau' => '09:00',
            'gio_ket_thuc' => '10:00',
            'ghi_chu' => 'Cardio',
            'trang_thai' => 'da_hoc',
        ]);

        $this->command->info('Phase 2 data seeded successfully!');
    }
}
